<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\EntityManager;

use App\DataTransferObject\UserQuestionDataProvider;

interface UserQuestionEntityManagerInterface
{
    public function create(UserQuestionDataProvider $userQuestionDataProvider): void;

    public function updateAnswer(UserQuestionDataProvider $userQuestionDataProvider): void;

    public function delete(int $id): void;

    public function deleteByUser(int $userId): void;
}