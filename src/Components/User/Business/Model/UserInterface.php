<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\DataTransferObject\UserDataProvider;

interface UserInterface
{
    public function create(UserDataProvider $userDataProvider): array;

    public function update(UserDataProvider $userDataProvider): array;

    public function delete(int $id): void;
}