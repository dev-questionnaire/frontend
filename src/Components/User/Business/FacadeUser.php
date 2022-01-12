<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\Components\User\Business\Model\UserInterface;
use App\DataTransferObject\UserDataProvider;
use App\Entity\User;

class FacadeUser implements FacadeUserInterface
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

    public function delete(User $user): void
    {
        $this->user->delete($user->getId());
    }
}