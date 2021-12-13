<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\EntityManager;

use App\DataTransferObject\ExamDataProvider;

interface ExamEntityManagerInterface
{
    public function create(ExamDataProvider $examDataProvider): void;
}