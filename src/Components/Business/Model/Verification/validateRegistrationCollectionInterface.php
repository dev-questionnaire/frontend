<?php
declare(strict_types=1);

namespace App\Components\Business\Model\Verification;

use App\Components\User\Persistence\DataTransferObject\UserDataProvider;

interface validateRegistrationCollectionInterface
{
    public function getErrors(UserDataProvider $userDTO): array;
}