<?php
declare(strict_types=1);

namespace App\Components\Business\Model;

use App\Components\User\Persistence\DataTransferObject\UserDataProvider;

interface validationInterface
{
    public function getErrors(UserDataProvider $userDTO): array;
}