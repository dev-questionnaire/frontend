<?php
declare(strict_types=1);

namespace App\Tests\Integration\Service\Import;

use App\Components\Exam\Business\Model\ExamMapperCSV;
use App\Components\Exam\Persistence\EntityManager\ExamEntityManager;
use App\Components\Exam\Persistence\Mapper\ExamMapperEntity;
use App\Repository\ExamRepository;
use App\Service\Import\CSVFileReader;
use App\Service\Import\ExamImport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class examImportTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?ExamRepository $examRepository;
    private ?ExamImport $examImport;

    protected function setUp(): void
    {
        parent::setUp();
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->examRepository = self::$container->get(ExamRepository::class);

        $erxamRepo = new \App\Components\Exam\Persistence\Repository\ExamRepository($this->examRepository,
            new ExamMapperEntity());
        $this->examImport = new ExamImport(
            new ExamMapperCSV(),
            $erxamRepo,
            new CSVFileReader(),
            new ExamEntityManager($this->entityManager, $erxamRepo));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM exam');
        $connection->executeQuery('ALTER TABLE exam AUTO_INCREMENT=0');

        $connection->close();
        $this->examRepository = null;
        $this->examImport = null;
        $this->entityManager = null;
    }

    public function testImport(): void
    {
        $countedImports = $this->examImport->import(__DIR__ . '/exam.test.csv');

        self::assertSame(2, $countedImports[0]);
        self::assertSame(3, $countedImports[1]);

        $examList = $this->examRepository->findAll();

        self::assertCount(2, $examList);

        self::assertSame('testExam_1', $examList[0]->getName());
        self::assertSame('07.12.2021', $examList[0]->getCreatedAt()->format('d.m.Y'));
        self::assertSame('07.12.2021', $examList[0]->getUpdatedAt()->format('d.m.Y'));

        self::assertSame('testExam_2', $examList[1]->getName());
        self::assertSame('07.12.2021', $examList[1]->getCreatedAt()->format('d.m.Y'));
        self::assertSame('07.12.2021', $examList[1]->getUpdatedAt()->format('d.m.Y'));
    }
}