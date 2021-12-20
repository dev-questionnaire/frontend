<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

use App\Components\Question\Business\FacadeQuestionInterface;

class BridgeQuestion implements BridgeQuestionInterface
{
    public function __construct(
        private FacadeQuestionInterface $facadeQuestion,
    )
    {
    }

    /**
     * @return \App\DataTransferObject\QuestionDataProvider[]
     */
    public function getByExamSlug(string $exam): array
    {
        return $this->facadeQuestion->getByExamSlug($exam);
    }
}