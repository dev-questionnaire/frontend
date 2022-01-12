<?php
declare(strict_types=1);

namespace App\Components\User\Dependency;

use App\Components\UserQuestion\Business\FacadeUserQuestion;
use App\Entity\User;

class BridgeUserQuestion implements BridgeUserQuestionInterface
{
    public function __construct(
        private FacadeUserQuestion $facadeUserQuestion,
    )
    {
    }

    public function deleteByUser(int $userId): void
    {
        $this->facadeUserQuestion->deleteByUser($userId);
    }
}