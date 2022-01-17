<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\Mapper;

use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;
use App\Entity\UserQuestion;

class UserQuestionMapper
{
    public function map(UserQuestion $userQuestion): UserQuestionDataProvider
    {
        $userQuestionDataProvider = new UserQuestionDataProvider();

        $userQuestionDataProvider
            ->setId($userQuestion->getId())
            ->setQuestionSlug($userQuestion->getQuestionSlug())
            ->setExamSlug($userQuestion->getExamSlug())
            ->setAnswer($userQuestion->getAnswer())
            ->setCreatedAt($userQuestion->getCreatedAt()->format('d.m.Y'))
            ->setUpdatedAt($userQuestion->getUpdatedAt()->format('d.m.Y'));

        $user = $userQuestion->getUser();

        if(!$user instanceof  User){
            throw new \PDOException("User not provided");
        }

        $userQuestionDataProvider->setUserId($user->getId());

        return $userQuestionDataProvider;
    }
}