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
            ->setAnswer(null)
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

        $userQuestionDataProvider = $this->getByQuestionAndUser($userId, $questionDataProvider->getSlug());

        if(!$userQuestionDataProvider instanceof UserQuestionDataProvider) {
            throw new \RuntimeException("UserQuestion no found");
        }

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

    public function getByQuestionAndUser(int $userId, string $questionSlug): ?UserQuestionDataProvider
    {
        return $this->userQuestionRepository->findeOneByQuestionAndUser($questionSlug, $userId);
    }

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(int $userId, string $examSlug): array
    {
        return $this->userQuestionRepository->getByExamAndUserIndexedByQuestionSlug($examSlug, $userId);
    }
}