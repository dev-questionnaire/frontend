<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\Repository;

use App\DataTransferObject\QuestionDataProvider;

interface QuestionRepositoryInterface
{
    /**
     * @return QuestionDataProvider[]
     */
    public function getByExam(string $exam): array;
}