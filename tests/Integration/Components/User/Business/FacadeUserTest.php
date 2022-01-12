<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Business;

use App\Components\User\Business\FacadeUser;
use App\Components\User\Persistence\Repository\UserRepositoryInterface;
use App\DataTransferObject\UserDataProvider;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FacadeUserTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?FacadeUser $facadeUser;
    private ?UserRepositoryInterface $userRepository;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $this->container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->facadeUser = $this->container->get(FacadeUser::class);
        $this->userRepository = $this->container->get(UserRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();

        $this->facadeUser = null;
        $this->entityManager = null;
    }

    public function testCreatePositiv(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('email@nexus-united.com')
            ->setPassword('!aA45678')
            ->setVerificationPassword('!aA45678')
            ->setRoles(['ROLE_USER']);

        $errors = $this->facadeUser->create($userDTO);
        self::assertEmpty($errors);
    }

    public function testCreateNegativ(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('email@nex.com')
            ->setPassword('45678')
            ->setVerificationPassword('!aA45678')
            ->setRoles(['ROLE_USER']);

        $errors = $this->facadeUser->create($userDTO);
        self::assertNotEmpty($errors);
    }

    public function testUpdatePositiv(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('email@nexus-united.com')
            ->setPassword('!aA45678')
            ->setVerificationPassword('!aA45678')
            ->setRoles(['ROLE_USER']);

        $this->facadeUser->create($userDTO);

        $userDTO = $this->userRepository->getByEmail('email@nexus-united.com');

        $userDTO->setEmail('emailUpdate@nexus-united.com')
            ->setPassword('!Update45678')
            ->setVerificationPassword('!Update45678');

        $errors = $this->facadeUser->update($userDTO);
        self::assertEmpty($errors);
    }

    public function testUpdateNegativ(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('email@nexus-united.com')
            ->setPassword('!aA45678')
            ->setVerificationPassword('!aA45678')
            ->setRoles(['ROLE_USER']);

        $this->facadeUser->create($userDTO);

        $userDTO = $this->userRepository->getByEmail('email@nexus-united.com');

        $userDTO->setEmail('emailUpdate@nexus-uni.com')
            ->setPassword('678')
            ->setVerificationPassword('te45678');

        $errors = $this->facadeUser->update($userDTO);
        self::assertNotEmpty($errors);
    }

    public function testDelete(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('email@nexus-united.com')
            ->setPassword('!aA45678')
            ->setVerificationPassword('!aA45678')
            ->setRoles(['ROLE_USER']);

        $this->facadeUser->create($userDTO);

        $this->facadeUser->delete($this->container->get(UserRepository::class)->findOneBy(['email' => 'email@nexus-united.com']));

        self::assertNull($this->userRepository->getByEmail('email@nexus-united.com'));
    }
}