<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

use App\Components\UserQuestion\Business\FacadeUserQuestionInterface;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

class BridgeUserQuestion implements BridgeUserQuestionInterface
{
    public function __construct(
        private FacadeUserQuestionInterface $facadeUserQuestion,
    )
    {
    }

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(int $userId, string $examSlug): array
    {
        return $this->facadeUserQuestion->getByUserAndExamIndexedByQuestionSlug($userId, $examSlug);
    }
}