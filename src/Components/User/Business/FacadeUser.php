<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\GeneratedDataTransferObject\UserDataProvider;

class FacadeUser
{
    public function __construct(private FactoryUser $factoryUser)
    {
    }

    public function create(UserDataProvider $userDataProvider): array
    {
        return $this->factoryUser->createCreateUser()->create($userDataProvider);
    }

    public function update(UserDataProvider $userDataProvider): array
    {
        return $this->factoryUser->createUpdateUser()->update($userDataProvider);
    }

    public function delete(int $id): void
    {
        $this->factoryUser->createDeleteUser()->delete($id);
    }
}