<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Business;

use App\DataTransferObject\UserQuestionDataProvider;

interface FacadeUserQuestionInterface
{
    public function create(string $questionSlug, string $examSlug, string $userEmail): void;

    public function updateAnswer(UserQuestionDataProvider $userQuestionDataProvider): void;

    public function delete(int $id): void;

    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider;

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(string $userEmail, string $examSlug): array;
}