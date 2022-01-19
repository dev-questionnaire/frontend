<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\DataTransferObject\ErrorDataProvider;
use App\DataTransferObject\UserDataProvider;

interface FacadeUserInterface
{
    /**
     * @return ErrorDataProvider[]
     */
    public function create(UserDataProvider $userDataProvider): array;

    /**
     * @return ErrorDataProvider[]
     */
    public function update(UserDataProvider $userDataProvider): array;

    public function delete(int $userId): void;

    /** @return UserDataProvider[] */
    public function getAllIndexedByUserId(): array;
}