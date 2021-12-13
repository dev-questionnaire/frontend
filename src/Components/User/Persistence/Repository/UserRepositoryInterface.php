<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\Repository;

use App\DataTransferObject\UserDataProvider;

interface UserRepositoryInterface
{
    public function getById(int $id): ?UserDataProvider;

    public function getByEmail(string $email): ?UserDataProvider;
}