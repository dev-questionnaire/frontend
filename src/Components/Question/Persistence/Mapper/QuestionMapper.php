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

        /** @var array<array-key, string|array<array-key, string>> */
        $question = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);

        /** @var string */
        $questionName = $question['question'];
        /** @var array<array-key, string> */
        $rightQuestion = $question['right_question'];
        /** @var array<array-key, string> */
        $answers = $question['answer'];
        /** @var string */
        $slug = $question['slug'];

        $questionDataProvider = new QuestionDataProvider();
        $questionDataProvider
            ->setQuestion($questionName)
            ->setRightQuestions($rightQuestion)
            ->setAnswers($answers)
            ->setSlug($slug);

        return $questionDataProvider;
    }
}