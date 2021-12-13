<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\Mapper;

use App\DataTransferObject\QuestionDataProvider;
use App\Entity\Question;

class QuestionMapper
{
    public function map(Question $question): QuestionDataProvider
    {
        $questionDataProvider = new QuestionDataProvider();

        $questionDataProvider
            ->setId($question->getId())
            ->setExamId($question->getExam()->getId())
            ->setQuestion($question->getQuestion())
            ->setCreatedAt($question->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($question->getUpdatedAt()->format('d.m.Y'));

        return $questionDataProvider;
    }
}