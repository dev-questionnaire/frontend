<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\Repository;

use App\DataTransferObject\UserDataProvider;

interface UserRepositoryInterface
{
    public function findById(int $id): ?UserDataProvider;

    public function findByToken(string $token): ?UserDataProvider;

    /**
     * @return UserDataProvider[]
     */
    public function findAll(): array;

    /**
     * @return UserDataProvider[]
     */
    public function findAllIndexedByUserId(): array;
}