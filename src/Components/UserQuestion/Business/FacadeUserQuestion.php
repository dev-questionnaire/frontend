<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Business;

use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManagerInterface;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

class FacadeUserQuestion implements FacadeUserQuestionInterface
{
    public function __construct(
        private UserQuestionRepositoryInterface $userQuestionRepository,
        private UserQuestionEntityManagerInterface $userQuestionEntityManager,
    )
    {
    }

    public function create(string $questionSlug, string $examSlug, int $userId): void
    {
        $userQuestionDataProvider = new UserQuestionDataProvider();

        $userQuestionDataProvider
            ->setAnswers(null)
            ->setUserId($userId)
            ->setQuestionSlug($questionSlug)
            ->setExamSlug($examSlug);

        $this->userQuestionEntityManager->create($userQuestionDataProvider);
    }

    /**
     * @param bool[] $formData
     */
    public function updateAnswer(QuestionDataProvider $questionDataProvider, int $userId, array $formData): void
    {
        $userQuestionDataProvider = $this->getByQuestionAndUser($userId, $questionDataProvider->getSlug());

        if(!$userQuestionDataProvider instanceof UserQuestionDataProvider) {
            throw new \RuntimeException("UserQuestion no found");
        }

        $userQuestionDataProvider->setAnswers($formData);

        $this->userQuestionEntityManager->updateAnswer($userQuestionDataProvider);
    }

    public function delete(int $id): void
    {
        $this->userQuestionEntityManager->delete($id);
    }

    public function deleteByUser(int $userId): void
    {
        $this->userQuestionEntityManager->deleteByUser($userId);
    }

    public function getByQuestionAndUser(int $userId, string $questionSlug): ?UserQuestionDataProvider
    {
        return $this->userQuestionRepository->findOneByQuestionAndUser($questionSlug, $userId);
    }

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(int $userId, string $examSlug): array
    {
        return $this->userQuestionRepository->findByExamAndUserIndexedByQuestionSlug($examSlug, $userId);
    }

    /**
     * @param array<array-key, QuestionDataProvider> $questionDataProviderList
     * @param array<array-key, \App\DataTransferObject\UserQuestionDataProvider> $userQuestionDataProviderList
     * @param bool $isAdminPage
     * @return array<array-key, int|float|array<array-key, list<string>|string|bool|null>>
     */
    public function getPercentAndAnswerCorrectAndUserAnswerList(array $questionDataProviderList, array $userQuestionDataProviderList, bool $isAdminPage = false): array
    {
        $countQuestions = 0;

        /** @var array<array-key, bool|null> $answeredCorrect */
        $answeredCorrect = [];

        /** @var array<array-key, string> $userAnswerList */
        $userAnswerList = [];

        foreach ($questionDataProviderList as $questionDataProvider) {
            $slug = $questionDataProvider->getSlug();
            $userAnswerList[$slug] = null;
            $answeredCorrect[$slug] = null;

            if(!array_key_exists($slug, $userQuestionDataProviderList)) {
                continue;
            }

            /** @var array<array-key, bool>|null $answerList */
            $answerList = $userQuestionDataProviderList[$slug]->getAnswers();

            if ($answerList === null && $isAdminPage === true) {
                continue;
            }

            if ($answerList === null) {
                return [];
            }

            $answeredCorrect[$slug] = null;


            foreach ($answerList as $answer => $result) {

                if(!is_string($answer)) {
                    $answer = (string)$answer;
                }

                if ($result === true) {
                    $userAnswerList[$slug][] = str_replace('_', ' ', $answer);
                }

                $answeredCorrect[$slug] = $this->getCurrentAnsweredCorrect(
                    $questionDataProvider,
                    $answeredCorrect[$slug],
                    $answer,
                    $result
                );
            }

            if ($answeredCorrect[$slug] === true) {
                $countQuestions++;
            }
        }

        $calculatedPercent = 100;

        $questionQuantity = count($questionDataProviderList);

        if ($questionQuantity !== 0) {
            $calculatedPercent = $countQuestions / $questionQuantity * 100;
        }

        return [
            'answeredCorrect' => $answeredCorrect,
            'percent' => $calculatedPercent,
            'userAnswerList' => $userAnswerList,
        ];
    }

    private function getCurrentAnsweredCorrect(
        QuestionDataProvider $questionDataProvider,
        bool|null            $currentAnsweredCorrect,
        string               $answer,
        bool                 $result,
    ): bool|null
    {
        /** @var string $rightAnswer */
        foreach ($questionDataProvider->getRightQuestions() as $rightAnswer) {
            if ($currentAnsweredCorrect === false) {
                break;
            }

            $rightAnswer = str_replace(' ', '_', $rightAnswer);

            if ($rightAnswer === $answer && $result === true) {
                $currentAnsweredCorrect = true;
                continue;
            }

            if ($rightAnswer === $answer && $result === false) {
                $currentAnsweredCorrect = false;
                continue;
            }

            if ($result === true) {
                $currentAnsweredCorrect = false;
            }
        }

        return $currentAnsweredCorrect;
    }
}