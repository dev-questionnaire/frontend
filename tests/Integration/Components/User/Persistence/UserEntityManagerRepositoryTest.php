<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Persistence;

use App\Components\User\Persistence\EntityManager\UserEntityManager;
use App\Components\User\Persistence\EntityManager\UserEntityManagerInterface;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\Repository\UserRepository;
use App\GeneratedDataTransferObject\UserDataProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserEntityManagerRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private ?UserEntityManagerInterface $userEntityManager;
    private ?UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userEntityManager = new UserEntityManager($this->entityManager);
        $this->userRepository = new UserRepository(self::$container->get(\App\Repository\UserRepository::class), new UserMapper());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();

        $this->userEntityManager = null;
        $this->userRepository = null;
        $this->entityManager = null;
    }

    public function testCreateAndGetByEmailAndId(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('test@email.com')
            ->setPassword('123456789')
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt('06.12.2021')
            ->setUpdatedAt('07.12.2021');

        $this->userEntityManager->create($userDTO);

        $userDTO = $this->userRepository->getByEmail('test@email.com');

        self::assertSame('test@email.com', $userDTO->getEmail());
        self::assertTrue(password_verify('123456789', $userDTO->getPassword()));
        self::assertSame('ROLE_USER', $userDTO->getRoles()[0]);
        self::assertSame('06.12.2021', $userDTO->getCreatedAt());
        self::assertSame('07.12.2021', $userDTO->getUpdatedAt());

        $userId = $userDTO->getId();

        self::assertInstanceOf(UserDataProvider::class, $this->userRepository->getById($userId));
    }

    public function testIdEmailNotFound(): void
    {
        self::assertNull($this->userRepository->getById(100));
        self::assertNull($this->userRepository->getByEmail('abc'));
    }
}