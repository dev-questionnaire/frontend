<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\Mapper;

use App\DataTransferObject\ExamDataProvider;

/**
 * @psalm-suppress MixedAssignment
 * @psalm-suppress MixedArrayAccess
 * @psalm-suppress MixedArgument
 */
class ExamMapper
{
    /**
     * @throws \JsonException
     */
    public function map(string $path): ExamDataProvider
    {
        $fileContent = file_get_contents($path);

        if ($fileContent === false) {
            throw new \RuntimeException("File not found");
        }

        /** @var array<array-key, string> */
        $exam = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);

        $name = $exam['exam'];

        $slug = $exam['slug'];

        $examDataProvider = new ExamDataProvider();
        $examDataProvider
            ->setName($name)
            ->setSlug($slug);

        return $examDataProvider;
    }
}