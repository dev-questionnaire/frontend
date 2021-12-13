<?php
declare(strict_types=1);

namespace App\Components\ExamQuestion\Persistence\Mapper;

use App\DataTransferObject\ExamQuestionDataProvider;
use App\Entity\Answer;

class ExamQuestionMapper
{
    public function map(Answer $examQuestion): ExamQuestionDataProvider
    {
        $examQuestionDataProvider = new ExamQuestionDataProvider();

        $examQuestionDataProvider
            ->setId($examQuestion->getId())
            ->setExamId($examQuestion->getExam()->getId())
            ->setQuestion($examQuestion->getAnswer())
            ->setCorrect($examQuestion->getCorrect())
            ->setCreatedAt($examQuestion->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($examQuestion->getUpdatedAt()->format('d.m.Y'));

        return $examQuestionDataProvider;
    }
}