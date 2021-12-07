<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model\Verification;

use App\DataTransferObject\ErrorDataProvider;
use App\DataTransferObject\UserDataProvider;

interface ValidateCollectionInterface
{
    public function getErrors(UserDataProvider $userDTO, ErrorDataProvider $errorDataProvider): ErrorDataProvider;
}