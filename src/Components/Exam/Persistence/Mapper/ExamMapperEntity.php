<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\Mapper;

use App\Entity\Exam;
use App\DataTransferObject\ExamDataProvider;

class ExamMapperEntity
{
    public function map(Exam $exam): ExamDataProvider
    {
        $examDataProvider = new ExamDataProvider();

        $examDataProvider->setId($exam->getId())
            ->setName($exam->getName())
            ->setCreatedAt($exam->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($exam->getUpdatedAt()->format('d.m.Y'));

        return $examDataProvider;
    }
}