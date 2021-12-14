<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\Mapper;

use App\Components\Answer\Persistence\Mapper\AnswerMapper;
use App\DataTransferObject\QuestionDataProvider;
use App\Entity\Question;

class QuestionMapper
{
    public function __construct(
        private AnswerMapper $answerMapper,
    )
    {
    }

    public function map(Question $question): QuestionDataProvider
    {
        $questionDataProvider = new QuestionDataProvider();

        $questionDataProvider
            ->setId($question->getId())
            ->setExamId($question->getExam()->getId())
            ->setQuestion($question->getQuestion())
            ->setCreatedAt($question->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($question->getUpdatedAt()->format('d.m.Y'));

        foreach ($question->getAnswers() as $answer) {
            $questionDataProvider->addAnswerDataProvider($this->answerMapper->map($answer));
        }

        return $questionDataProvider;
    }
}