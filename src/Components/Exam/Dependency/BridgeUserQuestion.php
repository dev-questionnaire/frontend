<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

use App\Components\UserQuestion\Business\FacadeUserQuestionInterface;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

class BridgeUserQuestion implements BridgeUserQuestionInterface
{
    public function __construct(
        private FacadeUserQuestionInterface $facadeUserQuestion,
    )
    {
    }

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(int $userId, string $examSlug): array
    {
        return $this->facadeUserQuestion->getByUserAndExamIndexedByQuestionSlug($userId, $examSlug);
    }

    /**
     * @param array<array-key, \App\DataTransferObject\QuestionDataProvider> $questionDataProviderList
     * @param array<array-key, \App\DataTransferObject\UserQuestionDataProvider> $userQuestionDataProviderList
     * @param bool $isAdminPage
     * @return array<array-key, int|float|array<array-key, list<string>|string|bool|null>>
     */
    public function getPercentAndAnswerCorrectAndUserAnswerList(array $questionDataProviderList, array $userQuestionDataProviderList, bool $isAdminPage = false): array
    {
        return $this->facadeUserQuestion->getPercentAndAnswerCorrectAndUserAnswerList($questionDataProviderList, $userQuestionDataProviderList, $isAdminPage);
    }
}