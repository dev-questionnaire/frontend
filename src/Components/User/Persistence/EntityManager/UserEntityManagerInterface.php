<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\EntityManager;

use App\GeneratedDataTransferObject\UserDataProvider;

interface UserEntityManagerInterface
{
    public function create(UserDataProvider $userDataProvider): void;

    public function update(): void;

    public function delete(): void;
}