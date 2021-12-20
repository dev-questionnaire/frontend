<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model\Verification;

use App\DataTransferObject\ErrorDataProvider;
use App\DataTransferObject\UserDataProvider;

class ValidatePasswords implements validateCollectionInterface
{
    public function getErrors(UserDataProvider $userDTO, ErrorDataProvider $errorDataProvider): ErrorDataProvider
    {
        $password = $userDTO->getPassword();
        $verPassword = $userDTO->getVerificationPassword();

        if (strlen($password) < 8) {
            $errorDataProvider->addError("Password too short!");
        }

        if (!preg_match("#[\d]+#", $password)) {
            $errorDataProvider->addError("Password must include at least one number!");
        }

        if (!preg_match("#[a-z]+#", $password)) {
            $errorDataProvider->addError("Password must include at least one lowercase letter!");
        }

        if (!preg_match("#[A-Z]+#", $password)) {
            $errorDataProvider->addError("Password must include at least one uppercase letter!");
        }

        if (!preg_match("/[!@#$%^&*-]+/", $password)) {
            $errorDataProvider->addError("Password must include at one special character!");
        }

        if($password !== $verPassword) {
            $errorDataProvider->addError("Password musst match Verification Password");
        }

        return $errorDataProvider;
    }
}