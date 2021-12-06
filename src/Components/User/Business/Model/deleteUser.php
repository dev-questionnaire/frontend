<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\Components\User\Persistence\EntityManager\UserEntityManagerInterface;

class deleteUser implements deleteUserInterface
{
    public function __construct(private UserEntityManagerInterface $userEntityManager)
    {
    }

    public function delete(int $id): void
    {
        $this->userEntityManager->delete($id);
    }
}