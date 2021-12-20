<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Business;

use App\DataTransferObject\UserQuestionDataProvider;

interface FacadeUserQuestionInterface
{
    public function create(string $questionSlug, string $userEmail): void;

    public function update(UserQuestionDataProvider $userQuestionDataProvider): void;

    public function delete(int $id): void;

    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider;
}