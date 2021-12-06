<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\Components\User\Business\Model\Verification\validateCollectionInterface;
use App\GeneratedDataTransferObject\UserDataProvider;

class validate implements validationInterface
{
    private array $validationCollection;

    public function __construct(validateCollectionInterface...$validationCollection)
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