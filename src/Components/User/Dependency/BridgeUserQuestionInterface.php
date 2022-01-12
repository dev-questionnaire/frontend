<?php
declare(strict_types=1);

namespace App\Components\User\Dependency;

use App\Entity\User;

interface BridgeUserQuestionInterface
{
    public function deleteByUser(int $userId): void;
}