<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Persistence;

use App\Components\User\Persistence\EntityManager\UserEntityManager;
use App\Components\User\Persistence\EntityManager\UserEntityManagerInterface;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\Repository\UserRepository;
use App\DataTransferObject\UserDataProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserEntityManagerRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private ?UserEntityManagerInterface $userEntityManager;
    private ?UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userEntityManager = $container->get(UserEntityManager::class);
        $this->userRepository = $container->get(UserRepository::class);
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
        $currentDate = (new \DateTime())->format('d.m.Y');

        $userDTO = new UserDataProvider();
        $userDTO->setEmail('test@email.com')
            ->setPassword('123456789')
            ->setRoles(['ROLE_USER']);

        $this->userEntityManager->create($userDTO);

        $userDTO = $this->userRepository->getByEmail('test@email.com');

        self::assertSame('test@email.com', $userDTO->getEmail());
        self::assertTrue(password_verify('123456789', $userDTO->getPassword()));
        self::assertSame('ROLE_USER', $userDTO->getRoles()[0]);
        self::assertSame($currentDate, $userDTO->getCreatedAt());
        self::assertSame($currentDate, $userDTO->getUpdatedAt());

        $userId = $userDTO->getId();

        self::assertInstanceOf(UserDataProvider::class, $this->userRepository->getById($userId));
    }

    public function testIdEmailNotFound(): void
    {
        self::assertNull($this->userRepository->getById(100));
        self::assertNull($this->userRepository->getByEmail('abc'));
    }

    public function testCheckEmailTaken(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('test1@email.com')
            ->setPassword('123456789')
            ->setRoles(['ROLE_USER']);

        $this->userEntityManager->create($userDTO);

        $userDTO->setEmail('test2@email.com')
            ->setPassword('123456789')
            ->setRoles(['ROLE_USER']);

        $this->userEntityManager->create($userDTO);

        $userDTO = $this->userRepository->getByEmail('test2@email.com');
        $userDTO->setEmail('test1@email.com');

        $result = $this->userRepository->checkEmailTaken($userDTO);

        self::assertTrue($result);
    }

    public function testUpdate(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('test@email.com')
            ->setPassword('123456789')
            ->setRoles(['ROLE_USER']);

        $this->userEntityManager->create($userDTO);

        $userDTO = $this->userRepository->getByEmail('test@email.com');

        $userDTO->setEmail('update@email.com')
            ->setPassword('up123456');

        $this->userEntityManager->update($userDTO);

        $userDTONew = $this->userRepository->getByEmail('update@email.com');

        self::assertSame($userDTO->getId(), $userDTONew->getId());
        self::assertSame('update@email.com', $userDTONew->getEmail());
        self::assertTrue(password_verify('up123456', $userDTONew->getPassword()));
    }

    public function testDelete(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('test@email.com')
            ->setPassword('123456789')
            ->setRoles(['ROLE_USER']);

        $this->userEntityManager->create($userDTO);

        $userDTO = $this->userRepository->getByEmail('test@email.com');
        self::assertNotNull($userDTO);

        $this->userEntityManager->delete($userDTO->getId());

        $userDTO = $this->userRepository->getByEmail('test@email.com');
        self::assertNull($userDTO);
    }
}