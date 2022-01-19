<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Dependency;

use App\Components\User\Business\FacadeUserInterface;

class BridgeUser implements BridgeUserInterface
{
    public function __construct(
        private FacadeUserInterface $facadeUser,
    )
    {
    }

    /** @return \App\DataTransferObject\UserDataProvider[] */
    public function getAllIndexedByUserId(): array
    {
        return $this->facadeUser->getAllIndexedByUserId();
    }
}