<?php
declare(strict_types=1);

namespace App\Components\Business\Model\Verification;

use App\Components\User\Persistence\DataTransferObject\UserDataProvider;

class validateRegistrationPasswords implements validateRegistrationCollectionInterface
{
    public function getErrors(UserDataProvider $userDTO): array
    {
        $errors = [];

        $password = $userDTO->getPassword();
        $verPassword = $userDTO->getVerificationPassword();

        if (strlen($password) < 8) {
            $errors[] = "Password too short!";
        }

        if (!preg_match("#[\d]+#", $password)) {
            $errors[] = "Password must include at least one number!";
        }

        if (!preg_match("#[a-zA-Z]+#", $password)) {
            $errors[] = "Password must include at least one letter!";
        }

        if($password !== $verPassword) {
            $errors[] = "Password musst match Verification Password";
        }

        return $errors;
    }
}