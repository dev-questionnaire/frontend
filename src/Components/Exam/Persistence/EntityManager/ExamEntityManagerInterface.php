<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\EntityManager;

use App\GeneratedDataTransferObject\ExamDataProvider;

interface ExamEntityManagerInterface
{
    public function create(ExamDataProvider $examDataProvider): void;

    public function update(ExamDataProvider $examDataProvider): void;

    public function delete(int $id): void;
}