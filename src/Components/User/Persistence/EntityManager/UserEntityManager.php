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
    )
    {
    }

    public function create(UserDataProvider $userDataProvider): void
    {
        if ($userDataProvider->getEmail() === null
            || $userDataProvider->getPassword() === null
        ) {
            throw new \RuntimeException("No data Provided");
        }

        $user = new User();

        $user
            ->setEmail($userDataProvider->getEmail())
            ->setRoles(['ROLE_USER'])
            ->setPassword($userDataProvider->getPassword()/*Hash*/);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(UserDataProvider $userDataProvider): void
    {
        if ($userDataProvider->getId() === null
            || $userDataProvider->getEmail() === null
            || $userDataProvider->getPassword() === null
        ) {
            throw new \RuntimeException("No data Provided");
        }

        $user = $this->userRepository->find($userDataProvider->getId());

        if(!$user instanceof User) {
            throw new \RuntimeException("User not found");
        }

        $plaintextPassword = $userDataProvider->getPassword();

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        $user
            ->setEmail($userDataProvider->getEmail())
            ->setPassword($hashedPassword);

        $this->entityManager->flush();
    }

    public function delete(int $id): void
    {
        $user = $this->userRepository->find($id);

        if(!$user instanceof User) {
            throw new \RuntimeException("User not found");
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}