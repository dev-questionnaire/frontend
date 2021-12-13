<?php
declare(strict_types=1);

namespace App\Components\UserExam\Persistence\Mapper;

use App\DataTransferObject\UserExamDataProvider;
use App\Entity\UserExam;

class UserExamMapper
{
    public function map(UserExam $userExam): UserExamDataProvider
    {
        $userExamDataProvider = new UserExamDataProvider();

        $userExamDataProvider
            ->setId($userExam->getId())
            ->setUserId($userExam->getUser()->getId())
            ->setExamQuestionId($userExam->getExamQuestion()->getId())
            ->setAnswer($userExam->getAnswer())
            ->setCreatedAt($userExam->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($userExam->getUpdatedAt()->format('d.m.Y'));

        return $userExamDataProvider;
    }
}