<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Business;

use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManagerInterface;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;

class FacadeUserQuestion implements FacadeUserQuestionInterface
{
    public function __construct(
        private UserQuestionRepositoryInterface $userQuestionRepository,
        private UserQuestionEntityManagerInterface $userQuestionEntityManager,
    )
    {
    }

    public function create(string $questionSlug, string $examSlug, string $userEmail): void
    {
        $userQuestionDataProvider = new UserQuestionDataProvider();

        $userQuestionDataProvider
            ->setAnswer(null)
            ->setUserEmail($userEmail)
            ->setQuestionSlug($questionSlug)
            ->setExamSlug($examSlug);

        $this->userQuestionEntityManager->create($userQuestionDataProvider);
    }

    public function updateAnswer(QuestionDataProvider $questionDataProvider, string $userEmail, array $formData): void
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

        $userQuestionDataProvider = $this->getByUserAndQuestion($userEmail, $questionDataProvider->getSlug());
        $userQuestionDataProvider->setAnswer($answerCorrect);

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

    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider
    {
        return $this->userQuestionRepository->getByUserAndQuestion($userEmail, $questionSlug);
    }

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(string $userEmail, string $examSlug): array
    {
        return $this->userQuestionRepository->getByUserAndExamIndexedByQuestionSlug($userEmail, $examSlug);
    }
}