<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\Repository;

use App\DataTransferObject\UserQuestionDataProvider;

interface UserQuestionRepositoryInterface
{
    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider;
}