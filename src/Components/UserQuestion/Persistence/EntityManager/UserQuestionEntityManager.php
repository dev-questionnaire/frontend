<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\EntityManager;

use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\UserQuestion;
use App\Repository\QuestionRepository;
use App\Repository\UserQuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserQuestionEntityManager implements UserQuestionEntityManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserQuestionRepository $userQuestionRepository,
        private UserRepository         $userRepository,
        private QuestionRepository     $questionRepository,
    )
    {
    }

    public function create(UserQuestionDataProvider $userQuestionDataProvider): void
    {
        $user = $this->userRepository->find($userQuestionDataProvider->getUserId());
        $question = $this->questionRepository->find($userQuestionDataProvider->getQuestionId());

        $userQuestion = new UserQuestion();

        $userQuestion
            ->setUser($user)
            ->setQuestion($question)
            ->setAnswer($userQuestionDataProvider->getAnswer());

        $this->entityManager->persist($userQuestion);
        $this->entityManager->flush();
    }

    public function updateAnswer(UserQuestionDataProvider $userQuestionDataProvider): void
    {
        $userQuestion = $this->userQuestionRepository->find($userQuestionDataProvider->getId());

        $userQuestion->setAnswer($userQuestionDataProvider->getAnswer());

        $this->entityManager->flush();
    }
}