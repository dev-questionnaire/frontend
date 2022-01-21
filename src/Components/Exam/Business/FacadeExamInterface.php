<?php
declare(strict_types=1);

namespace App\Components\Exam\Business;

use App\DataTransferObject\ExamDataProvider;

interface FacadeExamInterface
{
    public function getBySlug(string $slug): ?ExamDataProvider;

    /**
     * @param array<array-key, \App\DataTransferObject\QuestionDataProvider> $questionDataProviderList
     * @param array<array-key, \App\DataTransferObject\UserQuestionDataProvider> $userQuestionDataProviderList
     * @param bool $isAdminPage
     * @return array<array-key, int|float|array<array-key, list<string>|string|bool|null>>
     */
    public function getPercentAndAnswerCorrectAndUserAnswerList(array $questionDataProviderList, array $userQuestionDataProviderList, bool $isAdminPage = false): array;
}