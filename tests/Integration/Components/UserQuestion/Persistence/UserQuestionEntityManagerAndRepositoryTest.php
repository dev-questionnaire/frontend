<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\UserQuestion\Persistence;

use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManager;
use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManagerInterface;
use App\Components\UserQuestion\Persistence\Mapper\UserQuestionMapper;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepository;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\DataFixtures\AppFixtures;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserQuestionEntityManagerAndRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private ?UserQuestionRepositoryInterface $userQuestionRepository;
    private ?UserQuestionEntityManagerInterface $userQuestionEntityManager;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $this->container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $appFixtures = $this->container->get(AppFixtures::class);
        $appFixtures->load($this->entityManager);

        $this->userQuestionRepository = $this->container->get(UserQuestionRepository::class);
        $this->userQuestionEntityManager = $this->container->get(UserQuestionEntityManager::class);
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
        $user = $this->container->get(UserRepository::class)->findOneBy(['email' => 'user@valantic.com']);

        $userQuestionDataProvider = new UserQuestionDataProvider();
        $userQuestionDataProvider
            ->setUser($user)
            ->setQuestionSlug('question')
            ->setExamSlug('exam')
            ->setAnswer(null);

        $this->userQuestionEntityManager->create($userQuestionDataProvider);

        $userQuestionDataProvider
            ->setUser($user)
            ->setQuestionSlug('question2')
            ->setExamSlug('exam')
            ->setAnswer(null);

        $this->userQuestionEntityManager->create($userQuestionDataProvider);

        $userQuestionDataProvider = $this->userQuestionRepository->getByUserAndQuestion($user, 'question');

        $currentDate = (new \DateTime())->format('d.m.Y');

        self::assertSame(1, $userQuestionDataProvider->getId());
        self::assertSame($user, $userQuestionDataProvider->getUser());
        self::assertSame('question', $userQuestionDataProvider->getQuestionSlug());
        self::assertSame('exam', $userQuestionDataProvider->getExamSlug());
        self::assertNull($userQuestionDataProvider->getAnswer());
        self::assertSame($currentDate, $userQuestionDataProvider->getCreatedAt());
        self::assertSame($currentDate, $userQuestionDataProvider->getUpdatedAt());

        $userQuestionDataProvider->setAnswer(false);
        $this->userQuestionEntityManager->updateAnswer($userQuestionDataProvider);
        $userQuestionDataProvider = $this->userQuestionRepository->getByUserAndQuestion($user, 'question');

        self::assertFalse($userQuestionDataProvider->getAnswer());

        $userQuestionDataProviderList = $this->userQuestionRepository->getByUserAndExamIndexedByQuestionSlug($user, 'exam');
        self::assertCount(2, $userQuestionDataProviderList);

        $this->userQuestionEntityManager->delete($userQuestionDataProvider->getId());

        $userQuestionDataProvider = $this->userQuestionRepository->getByUserAndQuestion($user, 'question');
        self::assertNull($userQuestionDataProvider);

        $userQuestionDataProviderList = $this->userQuestionRepository->getByUserAndExamIndexedByQuestionSlug($user, 'exam');
        self::assertCount(1, $userQuestionDataProviderList);

        $this->userQuestionEntityManager->deleteByUser($user);
        $userQuestionDataProvider = $this->userQuestionRepository->getByUserAndQuestion($user, 'question2');
        self::assertNull($userQuestionDataProvider);
    }
}