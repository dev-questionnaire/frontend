<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\Mapper;

use App\DataTransferObject\QuestionDataProvider;

class QuestionMapper
{
    public function map(string $path): QuestionDataProvider
    {
        $question = json_decode(file_get_contents($path), true);

        $questionDataProvider = new QuestionDataProvider();
        $questionDataProvider
            ->setQuestion($question['question'])
            ->setRightQuestions($question['right_question'])
            ->setAnswers($question['answer'])
            ->setSlug($question['slug']);

        return $questionDataProvider;
    }
}