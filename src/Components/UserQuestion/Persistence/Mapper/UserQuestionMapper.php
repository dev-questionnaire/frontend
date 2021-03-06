<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\Mapper;

use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\UserQuestion;

class UserQuestionMapper
{
    public function map(UserQuestion $userQuestion): UserQuestionDataProvider
    {
        $userQuestionDataProvider = new UserQuestionDataProvider();

        $userQuestionDataProvider
            ->setId($userQuestion->getId())
            ->setUserId($userQuestion->getUser()->getId())
            ->setQuestionId($userQuestion->getQuestion()->getId())
            ->setAnswer($userQuestion->getAnswer())
            ->setCreatedAt($userQuestion->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($userQuestion->getUpdatedAt()->format('d.m.Y'));

        return $userQuestionDataProvider;
    }
}