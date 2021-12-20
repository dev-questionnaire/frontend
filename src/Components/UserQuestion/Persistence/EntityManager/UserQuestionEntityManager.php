<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\EntityManager;

use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\UserQuestion;
use App\Repository\UserQuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserQuestionEntityManager implements UserQuestionEntityManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserQuestionRepository $userQuestionRepository,
        private UserRepository         $userRepository,
    )
    {
    }

    public function create(UserQuestionDataProvider $userQuestionDataProvider): void
    {
        $user = $this->userRepository->findOneBy(['email' => $userQuestionDataProvider->getUserEmail()]);

        $userQuestion = new UserQuestion();

        $userQuestion
            ->setUser($user)
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
}