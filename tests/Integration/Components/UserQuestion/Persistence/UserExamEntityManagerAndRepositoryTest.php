<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\UserQuestion\Persistence;

use App\Components\Question\Persistence\EntityManager\QuestionEntityManager;
use App\Components\Question\Persistence\EntityManager\QuestionEntityManagerInterface;
use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManager;
use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManagerInterface;
use App\Components\UserQuestion\Persistence\Mapper\UserQuestionMapper;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepository;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\Exam;
use App\Entity\User;
use App\Repository\ExamRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserExamEntityManagerAndRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?QuestionEntityManagerInterface $questionEntityManager;
    private ?UserQuestionEntityManagerInterface $userQuestionEntityManager;
    private ?UserQuestionRepositoryInterface $userExamRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userExamRepository = new UserQuestionRepository(
            $container->get(\App\Repository\UserQuestionRepository::class),
            $container->get(UserRepository::class),
            $container->get(QuestionRepository::class),
            new UserQuestionMapper()
        );
        $this->userQuestionEntityManager = new UserQuestionEntityManager(
            $this->entityManager,
            $container->get(\App\Repository\UserQuestionRepository::class),
            $container->get(UserRepository::class),
            $container->get(QuestionRepository::class),
        );


        $this->questionEntityManager = new QuestionEntityManager($this->entityManager, $container->get(ExamRepository::class));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeQuery('DELETE FROM user_question');
        $connection->executeQuery('ALTER TABLE user_question AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM question');
        $connection->executeQuery('ALTER TABLE question AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM exam');
        $connection->executeQuery('ALTER TABLE exam AUTO_INCREMENT=0');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');

        $connection->close();

        $this->questionEntityManager = null;
        $this->userQuestionEntityManager = null;
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

        $questionDataProvider = new QuestionDataProvider();
        $questionDataProvider
            ->setExamId(1)
            ->setQuestion('Question_1?');

        $this->questionEntityManager->create($questionDataProvider);

        $questionDataProvider
            ->setExamId(1)
            ->setQuestion('Question_2?');

        $this->questionEntityManager->create($questionDataProvider);

        $userQuestionDataProvider = new UserQuestionDataProvider();
        $userQuestionDataProvider
            ->setUserId(1)
            ->setQuestionId(1)
            ->setAnswer(true);

        $this->userQuestionEntityManager->create($userQuestionDataProvider);

        $userQuestionDataProvider
            ->setUserId(1)
            ->setQuestionId(2)
            ->setAnswer(null);

        $this->userQuestionEntityManager->create($userQuestionDataProvider);


        $currentDate = (new \DateTime())->format('d.m.Y');
        $userQuestionDataProvider = $this->userExamRepository->getByExamQuestionAndUser(1, 1);

        self::assertSame(1, $userQuestionDataProvider->getUserId());
        self::assertSame(1, $userQuestionDataProvider->getQuestionId());
        self::assertTrue($userQuestionDataProvider->getAnswer());
        self::assertSame($currentDate, $userQuestionDataProvider->getCreatedAt());
        self::assertSame($currentDate, $userQuestionDataProvider->getUpdatedAt());

        $userQuestionDataProvider = $this->userExamRepository->getByExamQuestionAndUser(1, 2);

        self::assertSame(1, $userQuestionDataProvider->getUserId());
        self::assertSame(2, $userQuestionDataProvider->getQuestionId());
        self::assertNull($userQuestionDataProvider->getAnswer());
        self::assertSame($currentDate, $userQuestionDataProvider->getCreatedAt());
        self::assertSame($currentDate, $userQuestionDataProvider->getUpdatedAt());

        $userQuestionDataProvider
            ->setAnswer(false);

        $this->userQuestionEntityManager->updateAnswer($userQuestionDataProvider);

        $userQuestionDataProvider = $this->userExamRepository->getByExamQuestionAndUser(1, 2);

        self::assertFalse($userQuestionDataProvider->getAnswer());
    }

    public function testUserExamNotFound(): void
    {
        self::assertNull($this->userExamRepository->getByExamQuestionAndUser(0,0));
    }
}