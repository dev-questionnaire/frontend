<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Dependency;

use App\Components\User\Dependency\BridgeUserQuestion;
use App\Components\User\Dependency\BridgeUserQuestionInterface;
use App\DataFixtures\AppFixtures;
use App\Repository\UserQuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BridgeUserQuestionTest extends KernelTestCase
{
    private EntityManager $entityManager;
    private ContainerInterface $container;
    private BridgeUserQuestionInterface $bridgeUserQuestion;

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

        $this->bridgeUserQuestion = $this->container->get(BridgeUserQuestion::class);
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

    public function testDeleteByUser(): void
    {
        $userRepository = $this->container->get(UserRepository::class);
        $userQuestionRepository = $this->container->get(UserQuestionRepository::class);

        self::assertCount(3, $userQuestionRepository->findAll());

        $user = $userRepository->findOneBy(['email' => 'user@email.com']);

        $this->bridgeUserQuestion->deleteByUser($user->getId());

        self::assertCount(0, $userQuestionRepository->findAll());
    }
}