<?php
declare(strict_types=1);

namespace App\Components\Question\Dependency;

use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

interface BridgeUserQuestionInterface
{
    public function create(string $questionSlug, string $examSlug, int $userId): void;

    /**
     * @param bool[] $formData
     */
    public function updateAnswer(QuestionDataProvider $questionDataProvider, int $userId, array $formData): void;

    public function delete(int $id): void;

    public function getByUserAndQuestion(int $userId, string $questionSlug): ?UserQuestionDataProvider;
}