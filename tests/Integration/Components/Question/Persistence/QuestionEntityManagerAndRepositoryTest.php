<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Question\Persistence;

use App\Components\Exam\Persistence\EntityManager\ExamEntityManager;
use App\Components\Exam\Persistence\EntityManager\ExamEntityManagerInterface;
use App\Components\Exam\Persistence\Mapper\ExamMapperEntity;
use App\Components\Exam\Persistence\Repository\ExamRepository;
use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\Components\Question\Persistence\EntityManager\QuestionEntityManager;
use App\Components\Question\Persistence\EntityManager\QuestionEntityManagerInterface;
use App\Components\Question\Persistence\Mapper\QuestionMapper;
use App\Components\Question\Persistence\Repository\QuestionRepository;
use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;
use App\DataTransferObject\ExamDataProvider;
use App\DataTransferObject\QuestionDataProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuestionEntityManagerAndRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private ?QuestionEntityManagerInterface $questionEntityManager;
    private ?ExamEntityManagerInterface $examEntityManager;
    private ?QuestionRepositoryInterface $questionRepository;
    private ?ExamRepositoryInterface $examRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->questionRepository = new QuestionRepository(self::$container->get(\App\Repository\QuestionRepository::class), new QuestionMapper());
        $this->questionEntityManager = new QuestionEntityManager($this->entityManager, self::$container->get(\App\Repository\ExamRepository::class));

        $this->examRepository = new ExamRepository(self::$container->get(\App\Repository\ExamRepository::class), new ExamMapperEntity());
        $this->examEntityManager = new ExamEntityManager($this->entityManager, $this->examRepository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM question');
        $connection->executeQuery('ALTER TABLE question AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM exam');
        $connection->executeQuery('ALTER TABLE exam AUTO_INCREMENT=0');

        $connection->close();

        $this->questionEntityManager = null;
        $this->examEntityManager = null;
        $this->questionRepository = null;
        $this->examRepository = null;
        $this->entityManager = null;
    }

    public function testCreateAndGet(): void
    {
        $currentDate = (new \DateTime())->format('d.m.Y');

        $examDTO = new ExamDataProvider();
        $examDTO->setName('testExam');

        $this->examEntityManager->create($examDTO);

        $questionDTO = new QuestionDataProvider();
        $questionDTO
            ->setName('testQuestion')
            ->setAnswers([
                'Question1' => 'f',
                'Question2' => 'f',
                'Question3' => 'f',
                'Question4' => 'c',
            ])
            ->setExamId($this->examRepository->getByName('testExam')->getId());

        $this->questionEntityManager->create($questionDTO);

        $questionDTO = $this->questionRepository->getByName('testQuestion');

        self::assertSame('testQuestion', $questionDTO->getName());
        self::assertSame('f', $questionDTO->getAnswers()['Question1']);
        self::assertSame('f', $questionDTO->getAnswers()['Question2']);
        self::assertSame('f', $questionDTO->getAnswers()['Question3']);
        self::assertSame('c', $questionDTO->getAnswers()['Question4']);
        self::assertSame($currentDate, $questionDTO->getCreatedAt());
        self::assertSame($currentDate, $questionDTO->getUpdatedAt());
        self::assertSame(1, $questionDTO->getExamId());

        self::assertInstanceOf(QuestionDataProvider::class, $this->questionRepository->getById(1));
    }

    public function testGetNegativ(): void
    {
        self::assertNull($this->questionRepository->getById(1000));
        self::assertNull($this->questionRepository->getByName(''));
    }
}