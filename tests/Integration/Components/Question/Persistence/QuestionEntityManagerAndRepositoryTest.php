<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Question\Persistence;

use App\Components\Exam\Persistence\EntityManager\ExamEntityManager;
use App\Components\Exam\Persistence\EntityManager\ExamEntityManagerInterface;
use App\Components\Question\Persistence\Mapper\QuestionMapper;
use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;
use App\Components\Question\Persistence\EntityManager\QuestionEntityManager;
use App\Components\Question\Persistence\EntityManager\QuestionEntityManagerInterface;
use App\Components\Question\Persistence\Repository\QuestionRepository;
use App\DataTransferObject\ExamDataProvider;
use App\DataTransferObject\QuestionDataProvider;
use App\Repository\ExamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuestionEntityManagerAndRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?ExamEntityManagerInterface $examEntityManager;
    private ?QuestionEntityManagerInterface $questionEntityManager;
    private ?QuestionRepositoryInterface $questionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->questionRepository = new QuestionRepository($container->get(\App\Repository\QuestionRepository::class), $container->get(ExamRepository::class) ,new QuestionMapper());
        $this->questionEntityManager = new QuestionEntityManager($this->entityManager, $container->get(ExamRepository::class));
        $this->examEntityManager = new ExamEntityManager($this->entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeQuery('DELETE FROM question');
        $connection->executeQuery('ALTER TABLE question AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM exam');
        $connection->executeQuery('ALTER TABLE exam AUTO_INCREMENT=0');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');

        $connection->close();

        $this->questionEntityManager = null;
        $this->examEntityManager = null;
        $this->questionRepository = null;
        $this->entityManager = null;
    }

    public function testCreateAndGetByExamId(): void
    {
        $examDataProvider = new ExamDataProvider();
        $examDataProvider
            ->setName('exam');

        $this->examEntityManager->create($examDataProvider);

        $questionDataProvider = new QuestionDataProvider();
        $questionDataProvider
            ->setExamId(1)
            ->setQuestion('Question_1?');

        $this->questionEntityManager->create($questionDataProvider);

        $questionDataProvider
            ->setExamId(1)
            ->setQuestion('Question_2?');

        $this->questionEntityManager->create($questionDataProvider);

        $examQuestionDataProviderList = $this->questionRepository->getByExamId(1);

        self::assertSame('Question_1?', $examQuestionDataProviderList[0]->getQuestion());
        self::assertSame('Question_2?', $examQuestionDataProviderList[1]->getQuestion());
        self::assertSame(1, $examQuestionDataProviderList[0]->getExamId());
        self::assertSame(1, $examQuestionDataProviderList[1]->getExamId());

        $currentDate = (new \DateTime())->format('d.m.Y');

        self::assertSame($currentDate, $examQuestionDataProviderList[0]->getCreatedAt());
        self::assertSame($currentDate, $examQuestionDataProviderList[1]->getCreatedAt());
        self::assertSame($currentDate, $examQuestionDataProviderList[0]->getUpdatedAt());
        self::assertSame($currentDate, $examQuestionDataProviderList[1]->getUpdatedAt());
    }
}