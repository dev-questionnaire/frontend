<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\GeneratedDataTransferObject\UserDataProvider;

interface createUserInterface
{
    public function create(UserDataProvider $userDataProvider): array;
}