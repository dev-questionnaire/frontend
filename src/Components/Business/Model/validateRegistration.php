<?php
declare(strict_types=1);

namespace App\Components\Business\Model;

use App\Components\Business\Model\Verification\validateRegistrationCollectionInterface;
use App\Components\User\Persistence\DataTransferObject\UserDataProvider;

class validateRegistration implements validationInterface
{
    private array $validationCollection;

    public function __construct(validateRegistrationCollectionInterface...$validationCollection)
    {
        $this->validationCollection = $validationCollection;
    }

    public function getErrors(UserDataProvider $userDTO): array
    {
        $errors = [];

        foreach ($this->validationCollection as $validation) {
            $errors = $this->addErrors($errors, $validation->getErrors($userDTO));
        }

        return $errors;
    }

    private function addErrors(array $array, array $errors): array
    {
        if (!empty($errors)) {
            $array = array_merge($array, $errors);
        }

        return $array;
    }
}