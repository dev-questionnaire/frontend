<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\EntityManager;

use App\Entity\User;
use App\DataTransferObject\UserDataProvider;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserEntityManager implements UserEntityManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    public function create(UserDataProvider $userDataProvider): void
    {
        $userEntity = new User();

        $plaintextPassword = $userDataProvider->getPassword();

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $userEntity,
            $plaintextPassword
        );

        $userEntity->setEmail($userDataProvider->getEmail())
            ->setRoles(['ROLE_USER'])
            ->setPassword($hashedPassword);

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
    }

    public function update(UserDataProvider $userDataProvider): void
    {
        $userEntity = $this->userRepository->find($userDataProvider->getId());

        $plaintextPassword = $userDataProvider->getPassword();

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $userEntity,
            $plaintextPassword
        );

        $userEntity
            ->setEmail($userDataProvider->getEmail())
            ->setPassword($hashedPassword);

        $this->entityManager->flush();
    }

    public function delete(int $id): void
    {
        $userEntity = $this->userRepository->find($id);

        $this->entityManager->remove($userEntity);
        $this->entityManager->flush();
    }
}