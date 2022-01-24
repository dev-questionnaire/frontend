<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Business;

use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

interface FacadeUserQuestionInterface
{
    public function create(string $questionSlug, string $examSlug, int $userId): void;

    /**
     * @param bool[] $formData
     */
    public function updateAnswer(QuestionDataProvider $questionDataProvider, int $userId, array $formData): void;

    public function delete(int $id): void;

    public function deleteByUser(int $userId): void;

    public function getByQuestionAndUser(int $userId, string $questionSlug): ?UserQuestionDataProvider;

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(int $userId, string $examSlug): array;

    /**
     * @param array<array-key, \App\DataTransferObject\QuestionDataProvider> $questionDataProviderList
     * @param array<array-key, \App\DataTransferObject\UserQuestionDataProvider> $userQuestionDataProviderList
     * @param bool $isAdminPage
     * @return array<array-key, int|float|array<array-key, list<string>|string|bool|null>>
     */
    public function getPercentAndAnswerCorrectAndUserAnswerList(array $questionDataProviderList, array $userQuestionDataProviderList, bool $isAdminPage = false): array;
}