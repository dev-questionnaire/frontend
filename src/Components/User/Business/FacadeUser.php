<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\Components\User\Business\Model\ValidateCreate;
use App\Components\User\Business\Model\ValidateUpdate;
use App\Components\User\Persistence\EntityManager\UserEntityManagerInterface;
use App\DataTransferObject\UserDataProvider;
use App\DataTransferObject\ErrorDataProvider;

class FacadeUser implements FacadeUserInterface
{
    public function __construct(
        private UserEntityManagerInterface $userEntityManager,
        private ValidateCreate $validateCreate,
        private ValidateUpdate $validateUpdate,
    )
    {
    }

    /**
     * @return ErrorDataProvider[]
     */
    public function create(UserDataProvider $userDataProvider): array
    {
        $errors = $this->validateCreate->getErrors($userDataProvider)->getErrors();

        if(empty($errors)) {
            $this->userEntityManager->create($userDataProvider);
        }

        return $errors;
    }

    /**
     * @return ErrorDataProvider[]
     */
    public function update(UserDataProvider $userDataProvider): array
    {
        $errors = $this->validateUpdate->getErrors($userDataProvider)->getErrors();

        if(empty($errors)) {
            $this->userEntityManager->update($userDataProvider);
        }

        return $errors;
    }

    public function delete(int $userId): void
    {
        $this->userEntityManager->delete($userId);
    }
}