<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\ExamQuestion\Persistence;

use App\Components\Exam\Persistence\EntityManager\ExamEntityManager;
use App\Components\Exam\Persistence\EntityManager\ExamEntityManagerInterface;
use App\Components\ExamQuestion\Persistence\EntityManager\ExamQuestionEntityManager;
use App\Components\ExamQuestion\Persistence\EntityManager\ExamQuestionEntityManagerInterface;
use App\Components\ExamQuestion\Persistence\Mapper\QuestionMapper;
use App\Components\ExamQuestion\Persistence\Repository\ExamQuestionRepository;
use App\Components\ExamQuestion\Persistence\Repository\QuestionRepositoryInterface;
use App\DataTransferObject\ExamDataProvider;
use App\DataTransferObject\ExamQuestionDataProvider;
use App\Repository\ExamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExamQuestionEntityManagerAndRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?ExamEntityManagerInterface $examEntityManager;
    private ?ExamQuestionEntityManagerInterface $examQuestionEntityManager;
    private ?QuestionRepositoryInterface $examQuestionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->examQuestionRepository = new ExamQuestionRepository(self::$container->get(\App\Repository\AnswerRepository::class), self::$container->get(ExamRepository::class) ,new QuestionMapper());
        $this->examQuestionEntityManager = new ExamQuestionEntityManager($this->entityManager, self::$container->get(ExamRepository::class));
        $this->examEntityManager = new ExamEntityManager($this->entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeQuery('DELETE FROM exam_question');
        $connection->executeQuery('ALTER TABLE exam_question AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM exam');
        $connection->executeQuery('ALTER TABLE exam AUTO_INCREMENT=0');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');

        $connection->close();

        $this->examQuestionEntityManager = null;
        $this->examEntityManager = null;
        $this->examQuestionRepository = null;
        $this->entityManager = null;
    }

    public function testCreateAndGetByExamId(): void
    {
        $examDataProvider = new ExamDataProvider();
        $examDataProvider
            ->setName('exam');

        $this->examEntityManager->create($examDataProvider);

        $examQuestionDataProvider = new ExamQuestionDataProvider();
        $examQuestionDataProvider
            ->setExamId(1)
            ->setQuestion('Question_1?')
            ->setCorrect(true);

        $this->examQuestionEntityManager->create($examQuestionDataProvider);

        $examQuestionDataProvider
            ->setExamId(1)
            ->setQuestion('Question_2?')
            ->setCorrect(false);

        $this->examQuestionEntityManager->create($examQuestionDataProvider);

        $examQuestionDataProviderList = $this->examQuestionRepository->getByExamId(1);

        self::assertSame('Question_1?', $examQuestionDataProviderList[0]->getQuestion());
        self::assertSame('Question_2?', $examQuestionDataProviderList[1]->getQuestion());
        self::assertTrue($examQuestionDataProviderList[0]->getCorrect());
        self::assertFalse($examQuestionDataProviderList[1]->getCorrect());
        self::assertSame(1, $examQuestionDataProviderList[0]->getExamId());
        self::assertSame(1, $examQuestionDataProviderList[1]->getExamId());

        $currentDate = (new \DateTime())->format('d.m.Y');

        self::assertSame($currentDate, $examQuestionDataProviderList[0]->getCreatedAt());
        self::assertSame($currentDate, $examQuestionDataProviderList[1]->getCreatedAt());
        self::assertSame($currentDate, $examQuestionDataProviderList[0]->getUpdatedAt());
        self::assertSame($currentDate, $examQuestionDataProviderList[1]->getUpdatedAt());
    }
}