<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model\Verification;

use App\GeneratedDataTransferObject\UserDataProvider;

interface validateRegistrationCollectionInterface
{
    public function getErrors(UserDataProvider $userDTO): array;
}