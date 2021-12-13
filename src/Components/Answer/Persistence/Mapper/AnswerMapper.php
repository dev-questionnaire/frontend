<?php
declare(strict_types=1);

namespace App\Components\Answer\Persistence\Mapper;

use App\DataTransferObject\AnswerDataProvider;
use App\Entity\Answer;

class AnswerMapper
{
    public function map(Answer $answer): AnswerDataProvider
    {
        $answerDataProvider = new AnswerDataProvider();

        $answerDataProvider
            ->setId($answer->getId())
            ->setQuestionId($answer->getQuestion()->getId())
            ->setAnswer($answer->getAnswer())
            ->setCorrect($answer->getCorrect())
            ->setCreatedAt($answer->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($answer->getUpdatedAt()->format('d.m.Y'));

        return $answerDataProvider;
    }
}