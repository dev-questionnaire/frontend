<?php
declare(strict_types=1);

namespace App\Components\UserExam\Persistence\EntityManager;

use App\DataTransferObject\UserExamDataProvider;

interface UserExamEntityManagerInterface
{
    public function create(UserExamDataProvider $userExamDataProvider): void;

    public function updateAnswer(UserExamDataProvider $userExamDataProvider): void;
}