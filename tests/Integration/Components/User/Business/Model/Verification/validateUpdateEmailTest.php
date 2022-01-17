<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Business\Model\Verification;

use App\Components\User\Business\Model\Verification\ValidateUpdateEmail;
use App\Components\User\Persistence\Repository\UserRepository;
use App\Components\User\Persistence\Repository\UserRepositoryInterface;
use App\DataFixtures\AppFixtures;
use App\DataTransferObject\ErrorDataProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class validateUpdateEmailTest extends KernelTestCase
{
    private ValidateUpdateEmail $validateRegistrationEmail;
    private UserRepositoryInterface $userRepository;
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->validateRegistrationEmail = $container->get(ValidateUpdateEmail::class);
        $this->userRepository = $container->get(UserRepository::class);

        $appFixtures = $container->get(AppFixtures::class);
        $appFixtures->load($this->entityManager);
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

    public function testGetErrors(): void
    {
        $userDTO = $this->userRepository->getByEmail('user@valantic.com');
        $errorDataProvider = new ErrorDataProvider();

        $userDTO->setEmail('email@valantic.com');

        $errorDataProvider = $this->validateRegistrationEmail->getErrors($userDTO, $errorDataProvider);

        self::assertEmpty($errorDataProvider->getErrors());

        $errorDataProvider->unsetErrors();
        $userDTO->setEmail('admin@valantic.com');

        $errorDataProvider = $this->validateRegistrationEmail->getErrors($userDTO, $errorDataProvider);

        self::assertCount(1, $errorDataProvider->getErrors());
        self::assertSame('Email is already taken', $errorDataProvider->getErrors()[0]);

        $errorDataProvider->unsetErrors();
        $userDTO->setEmail('test@nexus.com');

        $errorDataProvider = $this->validateRegistrationEmail->getErrors($userDTO, $errorDataProvider);

        self::assertCount(1, $errorDataProvider->getErrors());
        self::assertSame('Email is not valid', $errorDataProvider->getErrors()[0]);
    }

    public function testGetErrorsNoEmailProvided(): void
    {
        $userDTO = $this->userRepository->getByEmail('user@valantic.com');
        $errorDataProvider = new ErrorDataProvider();

        $userDTO->setEmail("");

        $errorDataProvider = $this->validateRegistrationEmail->getErrors($userDTO, $errorDataProvider);

        self::assertCount(1, $errorDataProvider->getErrors());
        self::assertSame("No email provided", $errorDataProvider->getErrors()[0]);
    }
}