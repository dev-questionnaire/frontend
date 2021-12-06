<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\Components\User\Persistence\EntityManager\UserEntityManagerInterface;
use App\GeneratedDataTransferObject\UserDataProvider;

class updateUser implements updateUserInterface
{
    public function __construct(private UserEntityManagerInterface $userEntityManager, private validationInterface $validation)
    {
    }

    public function update(UserDataProvider $userDataProvider): array
    {
        $errors = $this->validation->getErrors($userDataProvider);

        if(empty($errors)) {
            $this->userEntityManager->update($userDataProvider);
        }

        return $errors;
    }
}