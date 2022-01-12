<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Business;

use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

interface FacadeUserQuestionInterface
{
    public function create(string $questionSlug, string $examSlug, int $userId): void;

    public function updateAnswer(QuestionDataProvider $questionDataProvider, int $userId, array $formData): void;

    public function delete(int $id): void;

    public function deleteByUser(int $userId): void;

    public function getByQuestionAndUser(int $userId, string $questionSlug): ?UserQuestionDataProvider;

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(int $userId, string $examSlug): array;
}