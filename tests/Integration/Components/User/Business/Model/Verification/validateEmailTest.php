<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Business\Model\Verification;

use App\Components\User\Business\Model\Verification\ValidateRegistrationEmail;
use App\DataFixtures\AppFixtures;
use App\DataTransferObject\ErrorDataProvider;
use App\DataTransferObject\UserDataProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class validateEmailTest extends KernelTestCase
{
    private ValidateRegistrationEmail $validateEmail;
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->validateEmail = $container->get(ValidateRegistrationEmail::class);

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
        $userDTO = new UserDataProvider();
        $errorDataProvider = new ErrorDataProvider();

        $userDTO->setEmail('email@valantic.com');

        $errorDataProvider = $this->validateEmail->getErrors($userDTO, $errorDataProvider);

        self::assertEmpty($errorDataProvider->getErrors());

        $errorDataProvider->unsetErrors();
        $userDTO->setEmail('user@valantic.com');

        $errorDataProvider = $this->validateEmail->getErrors($userDTO, $errorDataProvider);

        self::assertCount(1, $errorDataProvider->getErrors());
        self::assertSame('Email is already taken', $errorDataProvider->getErrors()[0]);

        $errorDataProvider->unsetErrors();
        $userDTO->setEmail('test@nexus.com');

        $errorDataProvider = $this->validateEmail->getErrors($userDTO, $errorDataProvider);

        self::assertCount(1, $errorDataProvider->getErrors());
        self::assertSame('Email is not valid', $errorDataProvider->getErrors()[0]);
    }
}