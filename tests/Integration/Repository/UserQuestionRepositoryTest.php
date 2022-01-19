<?php
declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\DataFixtures\AppFixtures;
use App\Entity\UserQuestion;
use App\Repository\UserQuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserQuestionRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $appFixtures = $container->get(AppFixtures::class);
        $appFixtures->load($this->entityManager, ['test' => true]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeQuery('DELETE FROM user_question');
        $connection->executeQuery('ALTER TABLE user_question AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');

        $connection->close();
    }

    public function testFindeOneByQuestionAndUser(): void
    {
        $container = static::getContainer();
        $userQuestionRepository = $container->get(UserQuestionRepository::class);

        $userQuestion = $userQuestionRepository->findeOneByQuestionAndUser('s_in_solid', 2);

        self::assertInstanceOf(UserQuestion::class, $userQuestion);
    }

    public function testFindeOneByQuestionAndUserNegativ(): void
    {
        $container = static::getContainer();
        $userQuestionRepository = $container->get(UserQuestionRepository::class);

        $userQuestion = $userQuestionRepository->findeOneByQuestionAndUser('s_in_solid', 12);

        self::assertNull($userQuestion);

        $userQuestion = $userQuestionRepository->findeOneByQuestionAndUser('', 2);

        self::assertNull($userQuestion);

        $userQuestion = $userQuestionRepository->findeOneByQuestionAndUser('', 0);

        self::assertNull($userQuestion);
    }

    public function testFindeByExamAndUser(): void
    {
        $container = static::getContainer();
        $userQuestionRepository = $container->get(UserQuestionRepository::class);

        $userQuestion = $userQuestionRepository->findeByExamAndUser('solid', 2);

        self::assertCount(2, $userQuestion);
    }

    public function testFindeByExamAndUserNegativ(): void
    {
        $container = static::getContainer();
        $userQuestionRepository = $container->get(UserQuestionRepository::class);

        $userQuestionList = $userQuestionRepository->findeByExamAndUser('', 2);

        self::assertEmpty($userQuestionList);

        $userQuestionList = $userQuestionRepository->findeByExamAndUser('solid', 100);

        self::assertEmpty($userQuestionList);

        $userQuestionList = $userQuestionRepository->findeByExamAndUser('', 100);

        self::assertEmpty($userQuestionList);
    }
}