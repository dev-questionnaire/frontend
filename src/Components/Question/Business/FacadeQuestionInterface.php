<?php
declare(strict_types=1);

namespace App\Components\Question\Business;

interface FacadeQuestionInterface
{
    /**
     * @return \App\DataTransferObject\QuestionDataProvider[]
     */
    public function getByExamSlug(string $slug): array;
}