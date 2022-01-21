<?php
declare(strict_types=1);

namespace App\Components\Exam\Business;

use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\DataTransferObject\ExamDataProvider;
use App\DataTransferObject\QuestionDataProvider;

class FacadeExam implements FacadeExamInterface
{
    public function __construct(
        private ExamRepositoryInterface $examRepository,
    )
    {
    }

    public function getBySlug(string $slug): ?ExamDataProvider
    {
        return $this->examRepository->getBySlug($slug);
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