<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Persistence\Repository;

use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private User $user;
    private ?EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->user = new User();
        $this->userRepository = new UserRepository(self::$container->get(\App\Repository\UserRepository::class), new UserMapper());

        $currentTime = new \DateTime();

        $this->user->setEmail('admin@email.com');
        $this->user->setPassword('admin');
        $this->user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $this->user->setCreatedAt($currentTime);
        $this->user->setUpdatedAt($currentTime);

        $this->entityManager->persist($this->user);

        $this->user = new User();

        $this->user->setEmail('user@email.com');
        $this->user->setPassword('user');
        $this->user->setRoles(['ROLE_USER']);
        $this->user->setCreatedAt($currentTime);
        $this->user->setUpdatedAt($currentTime);

        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
        $this->entityManager = null;
    }

    public function testGetById(): void
    {
        $userDTO = $this->userRepository->getById(1);

        self::assertSame(1, $userDTO->getId());
        self::assertSame('admin@email.com', $userDTO->getEmail());
        self::assertSame('admin', $userDTO->getPassword());
        self::assertSame('ROLE_USER', $userDTO->getRoles()[0]);
        self::assertSame('ROLE_ADMIN', $userDTO->getRoles()[1]);

        $userDTO = $this->userRepository->getById(100);

        self::assertNull($userDTO);
    }

    public function testGetByEmail(): void
    {
        $userDTO = $this->userRepository->getByEmail('admin@email.com');

        self::assertSame(1, $userDTO->getId());
        self::assertSame('admin@email.com', $userDTO->getEmail());
        self::assertSame('admin', $userDTO->getPassword());
        self::assertSame('ROLE_USER', $userDTO->getRoles()[0]);
        self::assertSame('ROLE_ADMIN', $userDTO->getRoles()[1]);

        $userDTO = $this->userRepository->getByEmail('email');

        self::assertNull($userDTO);
    }
}