<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

use App\Components\UserQuestion\Business\FacadeUserQuestionInterface;
use App\DataTransferObject\UserQuestionDataProvider;

class BridgeUserQuestion implements BridgeUserQuestionInterface
{
    public function __construct(
        private FacadeUserQuestionInterface $facadeUserQuestion,
    )
    {
    }

    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider
    {
        return $this->facadeUserQuestion->getByUserAndQuestion($userEmail, $questionSlug);
    }
}