<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\Mapper;

use App\GeneratedDataTransferObject\UserDataProvider;

class UserMapperCSV
{
    public function map(array $userCSV): UserDataProvider
    {
        $userDTO = new UserDataProvider();

        $roles = explode(',', $userCSV['roles']);

        $time = (new \DateTime())->format('d.m.Y');

        if (isset($userCSV['createdAt']) && $userCSV['createdAt'] !== '' && $userCSV['createdAt'] !== '0' && $userCSV['createdAt'] !== 'null') {
            $time = $userCSV['createdAt'];
        }

        $userDTO->setEmail($userCSV['email'])
            ->setPassword($userCSV['password'])
            ->setRoles($roles)
            ->setCreatedAt($time)
            ->setUpdatedAt($time);

        return $userDTO;
    }
}