<?php
declare(strict_types=1);

namespace App\Components\Question\Dependency;

use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;

interface BridgeUserQuestionInterface
{
    public function create(string $questionSlug, string $examSlug, string $userEmail): void;

    public function updateAnswer(QuestionDataProvider $questionDataProvider, string $userEmail, array $formData): void;

    public function delete(int $id): void;

    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider;
}