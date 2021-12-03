<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Business\Model;

use App\Components\User\Business\Model\UserMapperCSV;
use PHPUnit\Framework\TestCase;

class UserMapperCSVTest extends TestCase
{
    public function testMap(): void
    {
        $userMapper = new UserMapperCSV();
        $currentDate = (new \DateTime())->format('d.m.Y');

        $userCSV = [
            'email' => 'test@email.com',
            'password' => 'password',
            'roles' => 'ROLE_USER,ROLE_ADMIN',
            'createdAt' => '01.01.2001',
        ];

        $userDTO = $userMapper->map($userCSV);

        self::assertSame('test@email.com', $userDTO->getEmail());
        self::assertSame('password', $userDTO->getPassword());
        self::assertSame('ROLE_USER', $userDTO->getRoles()[0]);
        self::assertSame('ROLE_ADMIN', $userDTO->getRoles()[1]);
        self::assertSame('01.01.2001', $userDTO->getCreatedAt());
        self::assertSame('01.01.2001', $userDTO->getUpdatedAt());

        $userCSV = [
            'email' => 'test@email.com',
            'password' => 'password',
            'roles' => 'ROLE_USER,ROLE_ADMIN',
            'createdAt' => '0',
        ];

        $userDTO = $userMapper->map($userCSV);

        self::assertSame($currentDate, $userDTO->getCreatedAt());
        self::assertSame($currentDate, $userDTO->getUpdatedAt());

        $userCSV = [
            'email' => 'test@email.com',
            'password' => 'password',
            'roles' => 'ROLE_USER,ROLE_ADMIN',
            'createdAt' => '',
        ];

        $userDTO = $userMapper->map($userCSV);

        self::assertSame($currentDate, $userDTO->getCreatedAt());
        self::assertSame($currentDate, $userDTO->getUpdatedAt());

        $userCSV = [
            'email' => 'test@email.com',
            'password' => 'password',
            'roles' => 'ROLE_USER,ROLE_ADMIN',
            'createdAt' => 'null',
        ];

        $userDTO = $userMapper->map($userCSV);

        self::assertSame($currentDate, $userDTO->getCreatedAt());
        self::assertSame($currentDate, $userDTO->getUpdatedAt());
    }
}