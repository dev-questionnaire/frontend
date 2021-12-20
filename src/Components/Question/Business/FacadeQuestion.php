<?php
declare(strict_types=1);

namespace App\Components\Question\Business;

use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;

class FacadeQuestion implements FacadeQuestionInterface
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
    )
    {
    }

    /**
     * @return \App\DataTransferObject\QuestionDataProvider[]
     */
    public function getByExam(string $exam): array
    {
        return $this->questionRepository->getByExam($exam);
    }
}