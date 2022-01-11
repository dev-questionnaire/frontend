<?php
declare(strict_types=1);

namespace App\Components\Question\Dependency;

use App\Components\UserQuestion\Business\FacadeUserQuestionInterface;
use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;

class BridgeUserQuestion implements BridgeUserQuestionInterface
{
    public function __construct(
        private FacadeUserQuestionInterface $facadeUserQuestion,
    )
    {
    }

    public function create(string $questionSlug, string $examSlug, string $userEmail): void
    {
        $this->facadeUserQuestion->create($questionSlug, $examSlug, $userEmail);
    }

    public function updateAnswer(QuestionDataProvider $questionDataProvider, $user, $formData): void
    {
        $answerCorrect = null;

        foreach ($formData as $question => $answer) {
            if($answerCorrect === false)
            {
                break;
            }

            if($answer === false)
            {
                continue;
            }

            foreach ($questionDataProvider->getRightQuestions() as $rightQuestion) {
                if(str_replace(' ', '_', (string)$rightQuestion) === (string)$question) {
                    $answerCorrect = true;

                    break;
                }
                $answerCorrect = false;
            }
        }

        $userQuestionDataProvider = $this->getByUserAndQuestion($user, $questionDataProvider->getSlug());
        $userQuestionDataProvider->setAnswer($answerCorrect);

        $this->facadeUserQuestion->updateAnswer($userQuestionDataProvider);
    }

    public function delete(int $id): void
    {
        $this->facadeUserQuestion->delete($id);
    }

    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider
    {
        return $this->facadeUserQuestion->getByUserAndQuestion($userEmail, $questionSlug);
    }
}