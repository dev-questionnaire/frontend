<?php
declare(strict_types=1);

namespace App\Components\ExamQuestion\Persistence\Repository;

use App\DataTransferObject\ExamQuestionDataProvider;

interface ExamQuestionRepositoryInterface
{
    /**
     * @return ExamQuestionDataProvider[]
     */
    public function getByExamId(int $examId): array;
}