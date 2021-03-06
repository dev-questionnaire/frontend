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
        $userEntityList = $this->userEntityRepository->createQueryBuilder('u')
            ->andWhere('u.id != :id')
            ->andWhere('u.email = :email')
            ->setParameter('id', $userDataProvider->getId())
            ->setParameter('email', $userDataProvider->getEmail())
            ->getQuery()
            ->getResult();

        return !empty($userEntityList);
    }
}