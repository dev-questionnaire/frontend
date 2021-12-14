<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model\Verification;

use App\DataTransferObject\ErrorDataProvider;
use App\DataTransferObject\UserDataProvider;
use App\Components\User\Persistence\Repository\UserRepository;

class ValidateRegistrationEmail implements validateCollectionInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getErrors(UserDataProvider $userDTO, ErrorDataProvider $errorDataProvider): ErrorDataProvider
    {
        $email = $userDTO->getEmail();

        if($this->userRepository->getByEmail($email) instanceof UserDataProvider) {
            $errorDataProvider->addError("Email is already taken");
        }

        if(!str_contains($email, 'nexus-united.com') && !str_contains($email, 'valantic.com')) {
            $errorDataProvider->addError("Email is not valid");
        }

        return $errorDataProvider;
    }
}