<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\DataTransferObject\UserDataProvider;
use App\Entity\User;

interface FacadeUserInterface
{
    public function create(UserDataProvider $userDataProvider): array;

    public function update(UserDataProvider $userDataProvider): array;

    public function delete(int $userId): void;
}