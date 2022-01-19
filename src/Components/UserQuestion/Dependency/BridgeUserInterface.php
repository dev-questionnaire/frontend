<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Dependency;

interface BridgeUserInterface
{
    /** @return \App\DataTransferObject\UserDataProvider[] */
    public function getAllIndexedByUserId(): array;
}