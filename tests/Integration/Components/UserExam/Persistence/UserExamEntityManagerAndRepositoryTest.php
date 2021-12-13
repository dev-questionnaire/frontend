<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\UserExam\Persistence;

use App\Components\Exam\Persistence\EntityManager\ExamEntityManager;
use App\Components\Exam\Persistence\EntityManager\ExamEntityManagerInterface;
use App\Components\ExamQuestion\Persistence\EntityManager\ExamQuestionEntityManager;
use App\Components\ExamQuestion\Persistence\EntityManager\ExamQuestionEntityManagerInterface;
use App\Components\ExamQuestion\Persistence\Mapper\ExamQuestionMapper;
use App\Components\ExamQuestion\Persistence\Repository\ExamQuestionRepository;
use App\Components\ExamQuestion\Persistence\Repository\ExamQuestionRepositoryInterface;
use App\Components\UserExam\Persistence\EntityManager\UserExamEntityManager;
use App\Components\UserExam\Persistence\EntityManager\UserExamEntityManagerInterface;
use App\Components\UserExam\Persistence\Mapper\UserExamMapper;
use App\Components\UserExam\Persistence\Repository\UserExamRepository;
use App\Components\UserExam\Persistence\Repository\UserExamRepositoryInterface;
use App\DataTransferObject\ExamDataProvider;
use App\DataTransferObject\ExamQuestionDataProvider;
use App\DataTransferObject\UserExamDataProvider;
use App\Entity\Exam;
use App\Entity\Answer;
use App\Entity\User;
use App\Repository\ExamRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserExamEntityManagerAndRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?ExamQuestionEntityManagerInterface $examQuestionEntityManager;
    private ?UserExamEntityManagerInterface $userExamEntityManager;
    private ?UserExamRepositoryInterface $userExamRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userExamRepository = new UserExamRepository(
            self::$container->get(\App\Repository\UserQuestionRepository::class),
            self::$container->get(UserRepository::class),
            self::$container->get(\App\Repository\ExamAnswerRepository::class),
            new UserExamMapper()
        );
        $this->userExamEntityManager = new UserExamEntityManager(
            $this->entityManager,
            self::$container->get(\App\Repository\UserExamRepository::class),
            self::$container->get(UserRepository::class),
            self::$container->get(\App\Repository\QuestionAnswerRepository::class),
        );
        $this->examQuestionEntityManager = new ExamQuestionEntityManager($this->entityManager, self::$container->get(ExamRepository::class));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeQuery('DELETE FROM user_exam');
        $connection->executeQuery('ALTER TABLE user_exam AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM exam_question');
        $connection->executeQuery('ALTER TABLE exam_question AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM exam');
        $connection->executeQuery('ALTER TABLE exam AUTO_INCREMENT=0');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');

        $connection->close();

        $this->examQuestionEntityManager = null;
        $this->userExamEntityManager = null;
        $this->userExamRepository = null;
        $this->entityManager = null;
    }

    public function testCreateAndGetByExamId(): void
    {
        $user = new User();
        $user
            ->setEmail('test@email.com')
            ->setRoles(['ROL_TEST'])
            ->setPassword('123');

        $this->entityManager->persist($user);

        $exam = new Exam();
        $exam
            ->setName('exam');

        $this->entityManager->persist($exam);
        $this->entityManager->flush();

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

        $userExamDataProvider = new UserExamDataProvider();
        $userExamDataProvider
            ->setUserId(1)
            ->setExamQuestionId(1)
            ->setAnswer(true);

        $this->userExamEntityManager->create($userExamDataProvider);

        $userExamDataProvider
            ->setUserId(1)
            ->setExamQuestionId(2)
            ->setAnswer(null);

        $this->userExamEntityManager->create($userExamDataProvider);


        $currentDate = (new \DateTime())->format('d.m.Y');
        $userExamDataProvider = $this->userExamRepository->getByExamQuestionAndUser(1, 1);

        self::assertSame(1, $userExamDataProvider->getUserId());
        self::assertSame(1, $userExamDataProvider->getExamQuestionId());
        self::assertTrue($userExamDataProvider->getAnswer());
        self::assertSame($currentDate, $userExamDataProvider->getCreatedAt());
        self::assertSame($currentDate, $userExamDataProvider->getUpdatedAt());

        $userExamDataProvider = $this->userExamRepository->getByExamQuestionAndUser(1, 2);

        self::assertSame(1, $userExamDataProvider->getUserId());
        self::assertSame(2, $userExamDataProvider->getExamQuestionId());
        self::assertNull($userExamDataProvider->getAnswer());
        self::assertSame($currentDate, $userExamDataProvider->getCreatedAt());
        self::assertSame($currentDate, $userExamDataProvider->getUpdatedAt());

        $userExamDataProvider
            ->setAnswer(false);

        $this->userExamEntityManager->updateAnswer($userExamDataProvider);

        $userExamDataProvider = $this->userExamRepository->getByExamQuestionAndUser(1, 2);

        self::assertFalse($userExamDataProvider->getAnswer());
    }

    public function testUserExamNotFound(): void
    {
        self::assertNull($this->userExamRepository->getByExamQuestionAndUser(0,0));
    }
}