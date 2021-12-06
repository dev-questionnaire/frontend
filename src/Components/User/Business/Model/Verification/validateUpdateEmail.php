<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model\Verification;

use App\GeneratedDataTransferObject\UserDataProvider;
use App\Components\User\Persistence\Repository\UserRepository;

class validateUpdateEmail implements validateCollectionInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getErrors(UserDataProvider $userDTO): array
    {
        $errors = [];
        $email = $userDTO->getEmail();

        if($this->userRepository->checkEmailTaken($userDTO) === true) {
            $errors[] = "Email is already taken";
        }

        if(!str_contains($email, 'nexus-united.com') && !str_contains($email, 'valantic.com')) {
            $errors[] = "Email is not valid";
        }

        return $errors;
    }
}