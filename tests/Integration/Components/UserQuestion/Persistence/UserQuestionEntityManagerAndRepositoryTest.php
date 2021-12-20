<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\UserQuestion\Persistence;

use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManager;
use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManagerInterface;
use App\Components\UserQuestion\Persistence\Mapper\UserQuestionMapper;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepository;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserQuestionEntityManagerAndRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private ?UserQuestionRepositoryInterface $userQuestionRepository;
    private ?UserQuestionEntityManagerInterface $userQuestionEntityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userQuestionRepository = new UserQuestionRepository($container->get(\App\Repository\UserRepository::class,), $container->get(\App\Repository\UserQuestionRepository::class), new UserQuestionMapper());
        $this->userQuestionEntityManager = new UserQuestionEntityManager($this->entityManager, $container->get(\App\Repository\UserQuestionRepository::class), $container->get(\App\Repository\UserRepository::class));
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

        $this->userQuestionRepository = null;
        $this->userQuestionEntityManager = null;
        $this->entityManager = null;
    }

    public function testCreateAndGet(): void
    {
        $user = new User();
        $user
            ->setEmail('test@email.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword('123');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userQuestionDataProvider = new UserQuestionDataProvider();
        $userQuestionDataProvider
            ->setUserEmail('test@email.com')
            ->setQuestionSlug('slug')
            ->setAnswer(null);

        $this->userQuestionEntityManager->create($userQuestionDataProvider);

        $userQuestionDataProvider = $this->userQuestionRepository->getByUserAndQuestion('test@email.com', 'slug');

        $currentDate = (new \DateTime())->format('d.m.Y');

        self::assertSame(1, $userQuestionDataProvider->getId());
        self::assertSame('test@email.com', $userQuestionDataProvider->getUserEmail());
        self::assertSame('slug', $userQuestionDataProvider->getQuestionSlug());
        self::assertNull($userQuestionDataProvider->getAnswer());
        self::assertSame($currentDate, $userQuestionDataProvider->getCreatedAt());
        self::assertSame($currentDate, $userQuestionDataProvider->getUpdatedAt());

        $userQuestionDataProvider->setAnswer(false);
        $this->userQuestionEntityManager->updateAnswer($userQuestionDataProvider);
        $userQuestionDataProvider = $this->userQuestionRepository->getByUserAndQuestion('test@email.com', 'slug');

        self::assertFalse($userQuestionDataProvider->getAnswer());

        $this->userQuestionEntityManager->delete($userQuestionDataProvider->getId());

        $userQuestionDataProvider = $this->userQuestionRepository->getByUserAndQuestion('test@email.com', 'slug');
        self::assertNull($userQuestionDataProvider);
    }
}