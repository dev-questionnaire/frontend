<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\Repository;

use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

interface UserQuestionRepositoryInterface
{
    public function findOneByQuestionAndUser(string $questionSlug, int $userId): ?UserQuestionDataProvider;

    /**
     * @return UserQuestionDataProvider[]
     */
    public function findByExamAndUserIndexedByQuestionSlug(string $examSlug, int $userId): array;

    /**
     * @return UserQuestionDataProvider[]
     */
    public function findByExamAndQuestionSlug(string $examSlug, string $questionSlug): array;
}