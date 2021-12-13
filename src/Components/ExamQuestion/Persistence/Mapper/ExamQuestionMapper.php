<?php
declare(strict_types=1);

namespace App\Components\ExamQuestion\Persistence\Mapper;

use App\DataTransferObject\ExamQuestionDataProvider;
use App\Entity\ExamQuestion;

class ExamQuestionMapper
{
    public function map(ExamQuestion $examQuestion): ExamQuestionDataProvider
    {
        $examQuestionDataProvider = new ExamQuestionDataProvider();

        $examQuestionDataProvider
            ->setId($examQuestion->getId())
            ->setExamId($examQuestion->getExam()->getId())
            ->setQuestion($examQuestion->getQuestion())
            ->setCorrect($examQuestion->getCorrect())
            ->setCreatedAt($examQuestion->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($examQuestion->getUpdatedAt()->format('d.m.Y'));

        return $examQuestionDataProvider;
    }
}