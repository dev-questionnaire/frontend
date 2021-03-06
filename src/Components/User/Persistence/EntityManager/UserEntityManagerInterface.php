<?php
declare(strict_types=1);

namespace App\Components\User\Persistence\EntityManager;

use App\DataTransferObject\UserDataProvider;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface UserEntityManagerInterface
{
    public function create(UserDataProvider $userDataProvider): void;

    public function update(UserDataProvider $userDataProvider): void;

    public function delete(int $id): void;
}