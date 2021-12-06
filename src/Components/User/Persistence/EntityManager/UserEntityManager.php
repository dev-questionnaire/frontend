<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\EntityManager;

use App\Entity\User;
use App\GeneratedDataTransferObject\UserDataProvider;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserEntityManager implements UserEntityManagerInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private UserRepository $userRepository)
    {
    }

    public function create(UserDataProvider $userDataProvider): void
    {
        $userEntity = new User();
        $currentDate = new \DateTime();

        $userEntity->setEmail($userDataProvider->getEmail())
            ->setPassword(password_hash($userDataProvider->getPassword(), PASSWORD_DEFAULT))
            ->setRoles($userDataProvider->getRoles())
            ->setCreatedAt($currentDate)
            ->setUpdatedAt($currentDate);

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
    }

    public function update(UserDataProvider $userDataProvider): void
    {
        $currentDate = new \DateTime;

        $userEntity = $this->userRepository->find($userDataProvider->getId());
        $userEntity->setEmail($userDataProvider->getEmail())
            ->setPassword(password_hash($userDataProvider->getPassword(), PASSWORD_DEFAULT))
            ->setUpdatedAt($currentDate);

        $this->entityManager->flush();
    }

    public function delete(int $id): void
    {
        $userEntity = $this->userRepository->find($id);

        $this->entityManager->remove($userEntity);
        $this->entityManager->flush();
    }
}