<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\Mapper;

use App\Components\Question\Persistence\Mapper\QuestionMapper;
use App\Entity\Exam;
use App\DataTransferObject\ExamDataProvider;

class ExamMapperEntity
{
    public function __construct(
        private QuestionMapper $questionMapper,
    )
    {
    }

    public function map(Exam $exam): ExamDataProvider
    {
        $examDataProvider = new ExamDataProvider();

        $examDataProvider->setId($exam->getId())
            ->setName($exam->getName())
            ->setCreatedAt($exam->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($exam->getUpdatedAt()->format('d.m.Y'));

        foreach ($exam->getQuestions() as $question) {
            $examDataProvider->addQuestionDataProvider($this->questionMapper->map($question));
        }

        return $examDataProvider;
    }
}