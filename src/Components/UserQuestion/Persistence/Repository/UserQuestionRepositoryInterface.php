<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\Repository;

use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

interface UserQuestionRepositoryInterface
{
    public function getByUserAndQuestion(User $user, string $questionSlug): ?UserQuestionDataProvider;

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(User $user, string $examSlug): array;
}