<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\Repository;

use App\DataTransferObject\ExamDataProvider;

interface ExamRepositoryInterface
{
    public function getById(int $id): ?ExamDataProvider;

    public function getByName(string $name): ?ExamDataProvider;

    public function getAll(): array;
}