<?php
declare(strict_types=1);

namespace App\Tests\Unit\GeneratedDataProvider;

use App\GeneratedDataTransferObject\UserDataProvider;
use PHPUnit\Framework\TestCase;

class UserDataProviderTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $userDataProvider = new UserDataProvider();

        $userDataProvider
            ->setId(1)
            ->setEmail('testEmail')
            ->setPassword('testPassword')
            ->setVerificationPassword('testVerPassword')
            ->setRoles(['ROLE_TEST'])
            ->setCreatedAt('01.01.2001')
            ->setUpdatedAt('01.01.2001');

        self::assertSame(1, $userDataProvider->getId());
        self::assertSame('testEmail', $userDataProvider->getEmail());
        self::assertSame('testPassword', $userDataProvider->getPassword());
        self::assertSame('testVerPassword', $userDataProvider->getVerificationPassword());
        self::assertSame('ROLE_TEST', $userDataProvider->getRoles()[0]);
        self::assertSame('01.01.2001', $userDataProvider->getCreatedAt());
        self::assertSame('01.01.2001', $userDataProvider->getUpdatedAt());

        self::assertTrue($userDataProvider->hasId());
        self::assertTrue($userDataProvider->hasEmail());
        self::assertTrue($userDataProvider->hasPassword());
        self::assertTrue($userDataProvider->hasVerificationPassword());
        self::assertTrue($userDataProvider->hasRoles());
        self::assertTrue($userDataProvider->hasCreatedAt());
        self::assertTrue($userDataProvider->hasUpdatedAt());

        $userDataProvider
            ->unsetId()
            ->unsetEmail()
            ->unsetPassword()
            ->unsetVerificationPassword()
            ->unsetRoles()
            ->unsetCreatedAt()
            ->unsetUpdatedAt();

        self::assertFalse($userDataProvider->hasId());
        self::assertFalse($userDataProvider->hasEmail());
        self::assertFalse($userDataProvider->hasPassword());
        self::assertFalse($userDataProvider->hasVerificationPassword());
        self::assertFalse($userDataProvider->hasRoles());
        self::assertFalse($userDataProvider->hasCreatedAt());
        self::assertFalse($userDataProvider->hasUpdatedAt());

    }
}