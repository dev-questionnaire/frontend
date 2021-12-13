<?php
declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $userEntity = new User();
        $currentDate = new \DateTime();

        $userEntity
            ->setId(1)
            ->setEmail('test@email.com')
            ->setPassword('123')
            ->setRoles(['ROLE_TEST'])
            ->setUpdatedAt($currentDate)
            ->setCreatedAt($currentDate);

        self::assertSame(1, $userEntity->getId());
        self::assertSame('test@email.com', $userEntity->getEmail());
        self::assertSame('test@email.com', $userEntity->getUserIdentifier());
        self::assertSame('test@email.com', $userEntity->getUsername());
        self::assertSame('123', $userEntity->getPassword());
        self::assertSame('ROLE_TEST', $userEntity->getRoles()[0]);
        self::assertSame($currentDate, $userEntity->getCreatedAt());
        self::assertSame($currentDate, $userEntity->getUpdatedAt());

        self::assertNull($userEntity->getSalt());
    }
}