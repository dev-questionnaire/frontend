<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\Repository;

use App\DataTransferObject\UserDataProvider;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Entity\User;
use \App\Repository\UserRepository as UserEntityRepository;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private UserEntityRepository $userEntityRepository,
        private UserMapper $userMapper,
    )
    {
    }

    public function getById(int $id): ?UserDataProvider
    {
        $userEntity = $this->userEntityRepository->find($id);

        if(!$userEntity instanceof User) {
            return null;
        }

        return $this->userMapper->map($userEntity);
    }

    public function getByEmail(string $email): ?UserDataProvider
    {
        $userEntity = $this->userEntityRepository->findOneBy(['email' => $email]);

        if(!$userEntity instanceof User) {
            return null;
        }

        return $this->userMapper->map($userEntity);
    }

    public function checkEmailTaken(UserDataProvider $userDataProvider): bool
    {
        /** @var string $email */
        $email = $userDataProvider->getEmail();

        /** @var int $id */
        $id = $userDataProvider->getId();

        $userEntityList = $this->userEntityRepository->findeByEmailExcludeId($email, $id);

        return !empty($userEntityList);
    }

    /**
     * @return UserDataProvider[]
     */
    public function getAll(): array
    {
        /** @var UserDataProvider[] $userList */
        $userList = [];

        $users = $this->userEntityRepository->findAll();

        foreach ($users as $user) {
            $userList[] = $this->userMapper->map($user);
        }

        return $userList;
    }

    /**
     * @psalm-suppress PossiblyNullArrayOffset //id can't be null
     * @return UserDataProvider[]
     */
    public function getAllIndexedByUserId(): array
    {
        /** @var UserDataProvider[] $userList */
        $userList = [];

        $users = $this->userEntityRepository->findAll();

        foreach ($users as $user) {
            $userId = $user->getId();

            $userList[$userId] = $this->userMapper->map($user);
        }

        return $userList;
    }
}