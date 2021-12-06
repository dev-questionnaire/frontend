<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\Components\User\Persistence\EntityManager\UserEntityManagerInterface;
use App\GeneratedDataTransferObject\UserDataProvider;

class createUser implements createUserInterface
{
    public function __construct(private UserEntityManagerInterface $userEntityManager, private validationInterface $validation)
    {
    }

    public function create(UserDataProvider $userDataProvider): array
    {
        $errors = $this->validation->getErrors($userDataProvider);

        if(empty($errors)) {
            $this->userEntityManager->create($userDataProvider);
        }

        return $errors;
    }
}