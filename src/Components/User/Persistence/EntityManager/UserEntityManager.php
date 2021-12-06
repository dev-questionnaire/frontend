<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\EntityManager;

use App\Entity\User;
use App\GeneratedDataTransferObject\UserDataProvider;
use Doctrine\ORM\EntityManagerInterface;

class UserEntityManager implements UserEntityManagerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(UserDataProvider $userDataProvider): void
    {
        $userEntity = new User();

        $userEntity->setEmail($userDataProvider->getEmail())
            ->setPassword(password_hash($userDataProvider->getPassword(), PASSWORD_DEFAULT))
            ->setRoles($userDataProvider->getRoles())
            ->setCreatedAt(date_create_from_format('d.m.Y', $userDataProvider->getCreatedAt()))
            ->setUpdatedAt(date_create_from_format('d.m.Y', $userDataProvider->getUpdatedAt()));

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
    }

    public function update(): void
    {

    }

    public function delete(): void
    {

    }
}