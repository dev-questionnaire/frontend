<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\Components\User\Business\Model\UserInterface;
use App\DataTransferObject\UserDataProvider;

class FacadeUser
{
    public function __construct(
        private UserInterface $user,
    )
    {
    }

    public function create(UserDataProvider $userDataProvider): array
    {
        return $this->user->create($userDataProvider);
    }

    public function update(UserDataProvider $userDataProvider): array
    {
        return $this->user->update($userDataProvider);
    }

    public function delete(int $id): void
    {
        $this->user->delete($id);
    }
}