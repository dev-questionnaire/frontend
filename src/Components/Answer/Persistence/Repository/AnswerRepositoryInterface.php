<?php
declare(strict_types=1);

namespace App\Components\Answer\Persistence\Repository;

use App\DataTransferObject\AnswerDataProvider;

interface AnswerRepositoryInterface
{
    /**
     * @return AnswerDataProvider[]
     */
    public function getByQuestion(int $questionId): array;
}