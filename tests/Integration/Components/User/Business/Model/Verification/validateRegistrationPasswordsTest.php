<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Business\Model\Verification;

use App\Components\User\Business\Model\Verification\validateRegistrationPasswords;
use App\Entity\User as UserEntity;
use App\GeneratedDataTransferObject\UserDataProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class validateRegistrationPasswordsTest extends KernelTestCase
{
    private ?validateRegistrationPasswords $validatePassword;
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->validatePassword = new validateRegistrationPasswords();

        $currentTime = new \DateTime();

        $userEntity = new UserEntity();

        $userEntity->setEmail('test@nexus-united.com');
        $userEntity->setPassword('test');
        $userEntity->setRoles(['ROLE_USER']);
        $userEntity->setCreatedAt($currentTime);
        $userEntity->setUpdatedAt($currentTime);

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();

        $this->validatePassword = null;
        $this->entityManager = null;
    }

    public function testGetErrors(): void
    {
        $userDTO = new UserDataProvider();

        $userDTO->setPassword('!aA45678')
            ->setVerificationPassword('!aA45678');

        $errors = $this->validatePassword->getErrors($userDTO);

        self::assertEmpty($errors);

        $userDTO->setPassword('45678')
            ->setVerificationPassword('!aA45678');

        $errors = $this->validatePassword->getErrors($userDTO);

        self::assertSame('Password too short!', $errors[0]);
        self::assertSame('Password must include at least one lowercase letter!', $errors[1]);
        self::assertSame('Password must include at least one uppercase letter!', $errors[2]);
        self::assertSame('Password must include at one special character!', $errors[3]);
        self::assertSame('Password musst match Verification Password', $errors[4]);

        $userDTO->setPassword('aA!aaaaa')
            ->setVerificationPassword('aA!aaaaa');

        $errors = $this->validatePassword->getErrors($userDTO);

        self::assertSame('Password must include at least one number!', $errors[0]);
    }
}