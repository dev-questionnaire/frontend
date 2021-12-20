<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Business;

use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManagerInterface;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\DataTransferObject\UserQuestionDataProvider;

class FacadeUserQuestion implements FacadeUserQuestionInterface
{
    public function __construct(
        private UserQuestionRepositoryInterface $userQuestionRepository,
        private UserQuestionEntityManagerInterface $userQuestionEntityManager,
    )
    {
    }

    public function create(string $questionSlug, string $userEmail): void
    {
        $userQuestionDataProvider = new UserQuestionDataProvider();

        $userQuestionDataProvider
            ->setAnswer(null)
            ->setUserEmail($userEmail)
            ->setQuestionSlug($questionSlug);

        $this->userQuestionEntityManager->create($userQuestionDataProvider);
    }

    public function update(UserQuestionDataProvider $userQuestionDataProvider): void
    {
        $this->userQuestionEntityManager->updateAnswer($userQuestionDataProvider);
    }

    public function delete(int $id): void
    {
        $this->userQuestionEntityManager->delete($id);
    }

    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider
    {
        return $this->userQuestionRepository->getByUserAndQuestion($userEmail, $questionSlug);
    }
}