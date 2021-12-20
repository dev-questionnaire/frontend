<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

interface BridgeQuestionInterface
{
    /**
     * @return \App\DataTransferObject\QuestionDataProvider[]
     */
    public function getByExamSlug(string $exam): array;
}