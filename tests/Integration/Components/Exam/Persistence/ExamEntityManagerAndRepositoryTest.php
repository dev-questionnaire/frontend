<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Exam\Persistence;

use App\Components\Exam\Persistence\EntityManager\ExamEntityManager;
use App\Components\Exam\Persistence\EntityManager\ExamEntityManagerInterface;
use App\Components\Exam\Persistence\Mapper\ExamMapperEntity;
use App\Components\Exam\Persistence\Repository\ExamRepository;
use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\DataTransferObject\ExamDataProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExamEntityManagerAndRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private ?ExamEntityManagerInterface $examEntityManager;
    private ?ExamRepositoryInterface $examRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->examRepository = new ExamRepository(self::$container->get(\App\Repository\ExamRepository::class), new ExamMapperEntity());
        $this->examEntityManager = new ExamEntityManager($this->entityManager, $this->examRepository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM exam');
        $connection->executeQuery('ALTER TABLE exam AUTO_INCREMENT=0');

        $connection->close();

        $this->examEntityManager = null;
        $this->examRepository = null;
        $this->entityManager = null;
    }

    public function testCreateAndGet(): void
    {
        $currentDate = (new \DateTime())->format('d.m.Y');

        $examDTO = new ExamDataProvider();
        $examDTO->setName('testExam');

        $this->examEntityManager->create($examDTO);

        $examDTO = $this->examRepository->getByName('testExam');

        self::assertSame('testExam', $examDTO->getName());
        self::assertSame($currentDate, $examDTO->getCreatedAt());
        self::assertSame($currentDate, $examDTO->getUpdatedAt());

        self::assertInstanceOf(ExamDataProvider::class, $this->examRepository->getById($examDTO->getId()));

        $examDataProviderList = $this->examRepository->getAll();

        self::assertCount(1, $examDataProviderList);
    }

    public function testGetNegativ(): void
    {
        self::assertNull($this->examRepository->getById(1000));
        self::assertNull($this->examRepository->getByName(''));
        self::assertEmpty($this->examRepository->getAll());
    }
}