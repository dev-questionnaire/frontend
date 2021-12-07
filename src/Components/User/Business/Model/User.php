<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\Components\User\Persistence\EntityManager\UserEntityManagerInterface;
use App\DataTransferObject\UserDataProvider;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class User implements UserInterface
{
    public function __construct(
        private UserEntityManagerInterface $userEntityManager,
        private ValidateCreate $validateCreate,
        private ValidateUpdate $validateUpdate,
    )
    {
    }

    public function create(UserDataProvider $userDataProvider): array
    {
        $errors = $this->validateCreate->getErrors($userDataProvider)->getErrors();

        if(empty($errors)) {
            $this->userEntityManager->create($userDataProvider);
        }

        return $errors;
    }

    public function update(UserDataProvider $userDataProvider): array
    {
        $errors = $this->validateUpdate->getErrors($userDataProvider)->getErrors();

        if(empty($errors)) {
            $this->userEntityManager->update($userDataProvider);
        }

        return $errors;
    }

    public function delete(int $id): void
    {
        $this->userEntityManager->delete($id);
    }
}