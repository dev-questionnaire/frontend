<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

use App\Components\User\Business\FacadeUserInterface;

class BridgeUser implements BridgeUserInterface
{
    public function __construct(
        private FacadeUserInterface $facadeUser,
    )
    {
    }

    public function doesUserExist(int $id): bool
    {
        return $this->facadeUser->doesUserExist($id);
    }
}