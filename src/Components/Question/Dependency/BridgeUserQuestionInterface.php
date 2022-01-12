<?php
declare(strict_types=1);

namespace App\Components\Question\Dependency;

use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

interface BridgeUserQuestionInterface
{
    public function create(string $questionSlug, string $examSlug, User $user): void;

    public function updateAnswer(QuestionDataProvider $questionDataProvider, User $user, array $formData): void;

    public function delete(int $id): void;

    public function getByUserAndQuestion(User $user, string $questionSlug): ?UserQuestionDataProvider;
}