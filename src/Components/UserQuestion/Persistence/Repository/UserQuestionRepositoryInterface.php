<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\Repository;

use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

interface UserQuestionRepositoryInterface
{
    public function findeOneByQuestionAndUser(string $questionSlug, int $userId): ?UserQuestionDataProvider;

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByExamAndUserIndexedByQuestionSlug(string $examSlug, int $userId): array;
}