<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\Mapper;

use App\GeneratedDataTransferObject\UserDataProvider;
use App\Entity\User;

class UserMapper
{
    public function map(User $user): UserDataProvider
    {
        $userDataProvider = new UserDataProvider();

        $userDataProvider->setId($user->getId());
        $userDataProvider->setEmail($user->getEmail());
        $userDataProvider->setPassword($user->getPassword());
        $userDataProvider->setRoles($user->getRoles());

        return $userDataProvider;
    }
}