<?php
declare(strict_types=1);

namespace App\Components\Exam\Business;

use App\DataTransferObject\ExamDataProvider;

interface FacadeExamInterface
{
    public function getBySlug(string $slug): ?ExamDataProvider;
}