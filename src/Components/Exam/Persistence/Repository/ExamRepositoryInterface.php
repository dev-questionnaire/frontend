<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\Repository;

use App\DataTransferObject\ExamDataProvider;

interface ExamRepositoryInterface
{
    public function getByName(string $exam): ?ExamDataProvider;

    public function getAll(): array;
}