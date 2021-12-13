<?php
declare(strict_types=1);

namespace App\Components\UserExam\Persistence\Repository;

use App\DataTransferObject\UserExamDataProvider;

interface UserExamRepositoryInterface
{
    public function getByExamQuestionAndUser(int $userId, int $examQuestionId): ?UserExamDataProvider;
}