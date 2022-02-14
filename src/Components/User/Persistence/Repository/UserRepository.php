<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\Repository;

use App\Components\User\Service\ApiUser;
use App\DataTransferObject\UserDataProvider;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Entity\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private UserMapper $userMapper,
        private ApiUser    $api,
    )
    {
    }

    public function findById(int $id): ?UserDataProvider
    {
        $userEntity = $this->userEntityRepository->find($id);

        if(!$userEntity instanceof User) {
            return null;
        }

        return $this->userMapper->map($userEntity);
    }

    public function findByToken(string $token): ?UserDataProvider
    {
        $user = $this->api->findByToken($token);

        if(empty($user)) {
            return null;
        }

        return $this->userMapper->map($user);
    }

    public function checkEmailTaken(UserDataProvider $userDataProvider): bool
    {
        $user = $this->api->checkEmailTaken($userDataProvider);

        if(empty($user)) {
            return false;
        }

        return (bool)$user['found'];
    }

    /**
     * @return UserDataProvider[]
     */
    public function findAll(): array
    {
        /** @var UserDataProvider[] $userList */
        $userList = [];

        $users = $this->api->findAll();

        foreach ($users as $user) {
            $userList[] = $this->userMapper->map($user);
        }

        return $userList;
    }

    /**
     * @return UserDataProvider[]
     */
    public function findAllIndexedByUserId(): array
    {
        /** @var UserDataProvider[] $userList */
        $userList = [];

        $users = $this->api->findAll();

        foreach ($users as $id => $user) {
            $userList[(int)$id] = $this->userMapper->map($user);
        }

        return $userList;
    }
}