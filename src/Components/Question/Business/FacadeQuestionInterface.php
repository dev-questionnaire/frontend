<?php
declare(strict_types=1);

namespace App\Components\Question\Business;

use App\DataTransferObject\QuestionDataProvider;

interface FacadeQuestionInterface
{
    /**
     * @return \App\DataTransferObject\QuestionDataProvider[]
     */
    public function getByExamSlug(string $slug): array;

    public function getByExamAndQuestionSlug(string $examSlug, string $questionSlug): ?QuestionDataProvider;
}