<?php
declare(strict_types=1);

namespace App\Components\ExamQuestion\Persistence\EntityManager;

use App\DataTransferObject\ExamQuestionDataProvider;

interface ExamQuestionEntityManagerInterface
{
    public function create(ExamQuestionDataProvider $examQuestionDataProvider): void;
}