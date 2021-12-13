<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\DataTransferObject\ErrorDataProvider;
use App\DataTransferObject\UserDataProvider;

interface ValidateInterface
{
    public function getErrors(UserDataProvider $userDTO): ErrorDataProvider;
}