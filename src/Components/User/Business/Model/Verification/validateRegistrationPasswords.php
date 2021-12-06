<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model\Verification;

use App\GeneratedDataTransferObject\UserDataProvider;

class validateRegistrationPasswords implements validateCollectionInterface
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

        if (!preg_match("#[a-z]+#", $password)) {
            $errors[] = "Password must include at least one lowercase letter!";
        }

        if (!preg_match("#[A-Z]+#", $password)) {
            $errors[] = "Password must include at least one uppercase letter!";
        }

        if (!preg_match("/[!@#$%^&*-]+/", $password)) {
            $errors[] = "Password must include at one special character!";
        }

        if($password !== $verPassword) {
            $errors[] = "Password musst match Verification Password";
        }

        return $errors;
    }
}