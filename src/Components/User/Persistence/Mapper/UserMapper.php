<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\Mapper;

use App\DataTransferObject\UserDataProvider;

class UserMapper
{
    public function map(array $user): ?UserDataProvider
    {
        $userDataProvider = new UserDataProvider();

        /** @var int $id */
        $id = $user['id'] ?? 0;
        /** @var string $email */
        $email = $user['email'] ?? '';

        if($id === 0 || $email === '') {
            return null;
        }

        $userDataProvider
            ->setId($id)
            ->setEmail($email);

        return $userDataProvider;
    }
}