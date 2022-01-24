<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

interface BridgeUserInterface
{
    public function doesUserExist(int $id): bool;
}