<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Business;

use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

interface FacadeUserQuestionInterface
{
    public function create(string $questionSlug, string $examSlug, User $user): void;

    public function updateAnswer(QuestionDataProvider $questionDataProvider, User $user, array $formData): void;

    public function delete(int $id): void;

    public function deleteByUser(User $user): void;

    public function getByUserAndQuestion(User $user, string $questionSlug): ?UserQuestionDataProvider;

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(User $user, string $examSlug): array;
}