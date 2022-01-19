<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Dependency;

use App\Components\Question\Business\FacadeQuestionInterface;
use App\DataTransferObject\QuestionDataProvider;

class BridgeQuestion implements BridgeQuestionInterface
{
    public function __construct(
        private FacadeQuestionInterface $facadeQuestion,
    )
    {
    }

    public function getByExamAndQuestionSlug(string $examSlug, string $questionSlug): ?QuestionDataProvider
    {
        return $this->facadeQuestion->getByExamAndQuestionSlug($examSlug, $questionSlug);
    }
}