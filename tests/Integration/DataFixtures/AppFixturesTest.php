<?php
declare(strict_types=1);

namespace App\Tests\Integration\DataFixtures;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixturesTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?UserRepository $userRepository;
    private ?AppFixtures $appFixtures;
    private ?UserPasswordHasherInterface $userPasswordHasher;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userRepository = $container->get(UserRepository::class);
        $this->appFixtures = $container->get(AppFixtures::class);
        $this->userPasswordHasher = $container->get(UserPasswordHasherInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');

        $connection->close();

        $this->userPasswordHasher = null;
        $this->appFixtures = null;
        $this->userRepository = null;
        $this->entityManager = null;
    }

    public function testLoad(): void
    {
        $this->appFixtures->load($this->entityManager);

        $userList = $this->userRepository->findAll();

        self::assertSame('admin@email.com', $userList[0]->getEmail());
        self::assertSame('ROLE_ADMIN', $userList[0]->getRoles()[0]);
        self::assertSame('ROLE_USER', $userList[0]->getRoles()[1]);
        self::assertTrue($this->userPasswordHasher->isPasswordValid($userList[0], 'admin'));

        self::assertSame('user@email.com', $userList[1]->getEmail());
        self::assertSame('ROLE_USER', $userList[1]->getRoles()[0]);
        self::assertTrue($this->userPasswordHasher->isPasswordValid($userList[1], 'user'));
    }
}