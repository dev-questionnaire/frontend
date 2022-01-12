<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\EntityManager;

use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;
use App\Entity\UserQuestion;
use App\Repository\UserQuestionRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserQuestionEntityManager implements UserQuestionEntityManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserQuestionRepository $userQuestionRepository,
    )
    {
    }

    public function create(UserQuestionDataProvider $userQuestionDataProvider): void
    {
        $userQuestion = new UserQuestion();

        $userQuestion
            ->setUser($userQuestionDataProvider->getUser())
            ->setQuestionSlug($userQuestionDataProvider->getQuestionSlug())
            ->setExamSlug($userQuestionDataProvider->getExamSlug())
            ->setAnswer($userQuestionDataProvider->getAnswer());

        $this->entityManager->persist($userQuestion);
        $this->entityManager->flush();
    }

    public function updateAnswer(UserQuestionDataProvider $userQuestionDataProvider): void
    {
        $userQuestion = $this->userQuestionRepository->find($userQuestionDataProvider->getId());

        if(!$userQuestion instanceof UserQuestion) {
            return;
        }

        $userQuestion->setAnswer($userQuestionDataProvider->getAnswer());

        $this->entityManager->flush();
    }

    public function delete(int $id): void
    {
        $userQuestion = $this->userQuestionRepository->find($id);

        $this->entityManager->remove($userQuestion);
        $this->entityManager->flush();
    }

    public function deleteByUser(User $user): void
    {
        $userQuestionList = $this->userQuestionRepository->findBy(['user' => $user]);

        foreach ($userQuestionList as $userQuestion) {
            $this->entityManager->remove($userQuestion);
        }

        $this->entityManager->flush();
    }
}