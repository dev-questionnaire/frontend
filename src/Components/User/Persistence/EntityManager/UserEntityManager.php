<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\EntityManager;

use App\Entity\User;
use App\DataTransferObject\UserDataProvider;
use App\Repository\UserRepository;
use Doctrine\DBAL\Driver\PDO\Exception;
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
        $user = new User();

        $user
            ->setEmail($userDataProvider->getEmail())
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                $userDataProvider->getPassword()
            ));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(UserDataProvider $userDataProvider): void
    {
        $user = $this->userRepository->find($userDataProvider->getId());

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

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}