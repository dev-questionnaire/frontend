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

    public function create(string $questionSlug, string $examSlug, User $user): void
    {
        $userQuestionDataProvider = new UserQuestionDataProvider();

        $userQuestionDataProvider
            ->setAnswer(null)
            ->setUser($user)
            ->setQuestionSlug($questionSlug)
            ->setExamSlug($examSlug);

        $this->userQuestionEntityManager->create($userQuestionDataProvider);
    }

    public function updateAnswer(QuestionDataProvider $questionDataProvider, User $user, array $formData): void
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

        $this->userQuestionEntityManager->updateAnswer($userQuestionDataProvider);
    }

    public function delete(int $id): void
    {
        $this->userQuestionEntityManager->delete($id);
    }

    public function deleteByUser(User $user): void
    {
        $this->userQuestionEntityManager->deleteByUser($user);
    }

    public function getByUserAndQuestion(User $user, string $questionSlug): ?UserQuestionDataProvider
    {
        return $this->userQuestionRepository->getByUserAndQuestion($user, $questionSlug);
    }

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(User $user, string $examSlug): array
    {
        return $this->userQuestionRepository->getByUserAndExamIndexedByQuestionSlug($user, $examSlug);
    }
}