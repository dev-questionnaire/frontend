<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\Mapper;

use App\DataTransferObject\QuestionDataProvider;

class QuestionMapper
{
    /**
     * @throws \JsonException
     */
    public function map(string $path): QuestionDataProvider
    {
        $fileContent = file_get_contents($path);

        if($fileContent === false) {
            throw new \RuntimeException("File not found");
        }

        $question = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);

        $questionDataProvider = new QuestionDataProvider();
        $questionDataProvider
            ->setQuestion($question['question'])
            ->setRightQuestions($question['right_question'])
            ->setAnswers($question['answer'])
            ->setSlug($question['slug']);

        return $questionDataProvider;
    }
}