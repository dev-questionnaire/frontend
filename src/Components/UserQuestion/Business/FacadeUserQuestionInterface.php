<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Business;

use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;

interface FacadeUserQuestionInterface
{
    public function create(string $questionSlug, string $examSlug, string $userEmail): void;

    public function updateAnswer(QuestionDataProvider $questionDataProvider, string $userEmail, array $formData): void;

    public function delete(int $id): void;

    public function deleteByUser(int $userId): void;

    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider;

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(string $userEmail, string $examSlug): array;
}