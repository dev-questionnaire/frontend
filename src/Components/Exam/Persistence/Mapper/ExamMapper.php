<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\Mapper;

use App\DataTransferObject\ExamDataProvider;

class ExamMapper
{
    /**
     * @throws \JsonException
     */
    public function map(string $path): ExamDataProvider
    {
        $fileContent = file_get_contents($path);

        if($fileContent === false) {
            throw new \RuntimeException("File not found");
        }

        $exam = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);

        $examDataProvider = new ExamDataProvider();
        $examDataProvider
            ->setName($exam['exam'])
            ->setSlug($exam['slug']);

        return $examDataProvider;
    }
}