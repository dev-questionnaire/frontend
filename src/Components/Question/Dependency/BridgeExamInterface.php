<?php
declare(strict_types=1);

namespace App\Components\Question\Dependency;

use App\DataTransferObject\ExamDataProvider;

interface BridgeExamInterface
{
    public function getBySlug(string $slug): ?ExamDataProvider;
}