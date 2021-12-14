<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Answer\Persistence;

use App\Components\Answer\Persistence\EntityManager\AnswerEntityManager;
use App\Components\Answer\Persistence\EntityManager\AnswerEntityManagerInterface;
use App\Components\Answer\Persistence\Mapper\AnswerMapper;
use App\Components\Answer\Persistence\Repository\AnswerRepository;
use App\Components\Answer\Persistence\Repository\AnswerRepositoryInterface;
use App\Components\Exam\Persistence\EntityManager\ExamEntityManager;
use App\Components\Exam\Persistence\EntityManager\ExamEntityManagerInterface;
use App\Components\Question\Persistence\EntityManager\QuestionEntityManager;
use App\Components\Question\Persistence\EntityManager\QuestionEntityManagerInterface;
use App\Components\Question\Persistence\Mapper\QuestionMapper;
use App\Components\Question\Persistence\Repository\QuestionRepository;
use App\DataTransferObject\AnswerDataProvider;
use App\DataTransferObject\ExamDataProvider;
use App\DataTransferObject\QuestionDataProvider;
use App\Repository\ExamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AnswerEntityManagerAndRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?ExamEntityManagerInterface $examEntityManager;
    private ?QuestionEntityManagerInterface $questionEntityManager;
    private ?AnswerEntityManagerInterface $answerEntityManager;
    private ?AnswerRepositoryInterface $answerRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->questionEntityManager = new QuestionEntityManager($this->entityManager, $container->get(ExamRepository::class));
        $this->examEntityManager = new ExamEntityManager($this->entityManager);
        $this->answerEntityManager = new AnswerEntityManager($this->entityManager, $container->get(\App\Repository\QuestionRepository::class));
        $this->answerRepository = new AnswerRepository($container->get(\App\Repository\AnswerRepository::class), $container->get(\App\Repository\QuestionRepository::class), new AnswerMapper());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeQuery('DELETE FROM answer');
        $connection->executeQuery('ALTER TABLE answer AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM question');
        $connection->executeQuery('ALTER TABLE question AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM exam');
        $connection->executeQuery('ALTER TABLE exam AUTO_INCREMENT=0');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');

        $connection->close();

        $this->answerRepository = null;
        $this->answerEntityManager = null;
        $this->questionEntityManager = null;
        $this->examEntityManager = null;
        $this->entityManager = null;
    }

    public function testCreateAndGet(): void
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

        $answerDataProvider = new AnswerDataProvider();
        $answerDataProvider
            ->setQuestionId(1)
            ->setAnswer('Duck')
            ->setCorrect(false);
        $this->answerEntityManager->create($answerDataProvider);

        $answerDataProvider
            ->setQuestionId(1)
            ->setAnswer('Fish')
            ->setCorrect(true);
        $this->answerEntityManager->create($answerDataProvider);

        $answerList = $this->answerRepository->getByQuestion(1);

        $currentDate = (new \DateTime())->format('d.m.Y');

        self::assertCount(2, $answerList);

        self::assertSame(1, $answerList[0]->getQuestionId());
        self::assertSame(1, $answerList[1]->getQuestionId());
        self::assertSame('Duck', $answerList[0]->getAnswer());
        self::assertSame('Fish', $answerList[1]->getAnswer());
        self::assertFalse($answerList[0]->getCorrect());
        self::assertTrue($answerList[1]->getCorrect());
        self::assertSame($currentDate, $answerList[0]->getCreatedAt());
        self::assertSame($currentDate, $answerList[1]->getCreatedAt());
        self::assertSame($currentDate, $answerList[0]->getUpdatedAt());
        self::assertSame($currentDate, $answerList[1]->getUpdatedAt());
    }
}