<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\Mapper;

use App\DataTransferObject\ExamDataProvider;

class ExamMapper
{
    public function map(string $path): ExamDataProvider
    {
        $exam = json_decode(file_get_contents($path), true);

        $examDataProvider = new ExamDataProvider();
        $examDataProvider
            ->setName($exam['exam'])
            ->setSlug($exam['slug']);

        return $examDataProvider;
    }
}