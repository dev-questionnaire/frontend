<?php
declare(strict_types=1);

namespace App\Components\Question\Business;

use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;
use App\DataTransferObject\QuestionDataProvider;

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
    public function getByExamSlug(string $slug): array
    {
        return $this->questionRepository->getByExamSlug($slug);
    }

    public function getByExamAndQuestionSlug(string $examSlug, string $questionSlug): ?QuestionDataProvider
    {
        return $this->questionRepository->getByExamAndQuestionSlug($examSlug, $questionSlug);
    }
}