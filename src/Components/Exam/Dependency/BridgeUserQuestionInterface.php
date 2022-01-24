<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

interface BridgeUserQuestionInterface
{
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