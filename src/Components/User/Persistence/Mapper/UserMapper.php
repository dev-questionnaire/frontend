<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\Mapper;

use App\DataTransferObject\UserDataProvider;
use App\Entity\User;

class UserMapper
{
    public function map(User $user): UserDataProvider
    {
        $userDataProvider = new UserDataProvider();

        $userDataProvider->setId($user->getId())
            ->setEmail($user->getEmail())
            ->setPassword($user->getPassword())
            ->setRoles($user->getRoles())
            ->setCreatedAt($user->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($user->getUpdatedAt()->format('d.m.Y'));

        return $userDataProvider;
    }
}