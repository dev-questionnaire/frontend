<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Business\Model\Verification;

use App\Components\User\Business\Model\Verification\ValidatePasswords;
use App\DataFixtures\AppFixtures;
use App\DataTransferObject\ErrorDataProvider;
use App\Entity\User as UserEntity;
use App\DataTransferObject\UserDataProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class validatePasswordsTest extends KernelTestCase
{
    private ?ValidatePasswords $validatePassword;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validatePassword = new ValidatePasswords();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->validatePassword = null;
    }

    public function testGetErrors(): void
    {
        $userDTO = new UserDataProvider();
        $errorDataProvider = new ErrorDataProvider();

        $userDTO->setPassword('!aA45678')
            ->setVerificationPassword('!aA45678');

        $errorDataProvider = $this->validatePassword->getErrors($userDTO, $errorDataProvider);

        self::assertEmpty($errorDataProvider->getErrors());

        $errorDataProvider->unsetErrors();
        $userDTO->setPassword('45678')
            ->setVerificationPassword('!aA45678');

        $errorDataProvider = $this->validatePassword->getErrors($userDTO, $errorDataProvider);

        $errors = $errorDataProvider->getErrors();

        self::assertSame('Password too short!', $errors[0]);
        self::assertSame('Password must include at least one lowercase letter!', $errors[1]);
        self::assertSame('Password must include at least one uppercase letter!', $errors[2]);
        self::assertSame('Password must include at one special character!', $errors[3]);
        self::assertSame('Password musst match Verification Password', $errors[4]);

        $errorDataProvider->unsetErrors();

        $userDTO->setPassword('aA!aaaaa')
            ->setVerificationPassword('aA!aaaaa');

        $errorDataProvider = $this->validatePassword->getErrors($userDTO, $errorDataProvider);

        self::assertSame('Password must include at least one number!', $errorDataProvider->getErrors()[0]);
    }

    public function testNoPasswordGiven(): void
    {
        $userDTO = new UserDataProvider();
        $errorDataProvider = new ErrorDataProvider();

        $errorDataProvider = $this->validatePassword->getErrors($userDTO, $errorDataProvider);

        self::assertCount(1, $errorDataProvider->getErrors());
        self::assertSame("No Password provided!", $errorDataProvider->getErrors()[0]);
    }

    public function testNoVerPasswordGiven(): void
    {
        $userDTO = new UserDataProvider();
        $errorDataProvider = new ErrorDataProvider();

        $userDTO->setPassword('123');
        $errorDataProvider = $this->validatePassword->getErrors($userDTO, $errorDataProvider);

        self::assertSame("No Verification Password provided!", $errorDataProvider->getErrors()[0]);
    }
}