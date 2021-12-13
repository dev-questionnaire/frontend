<?php
declare(strict_types=1);

namespace App\Components\UserExam\Persistence\EntityManager;

use App\DataTransferObject\UserExamDataProvider;
use App\Entity\UserQuestion;
use App\Repository\ExamAnswerRepository;
use App\Repository\UserQuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserExamEntityManager implements UserExamEntityManagerInterface
{
    public function __construct(
        private EntityManagerInterface   $entityManager,
        private UserQuestionRepository   $userExamRepository,
        private UserRepository           $userRepository,
        private QuestionAnswerRepository $examQuestionRepository,
    )
    {
    }

    public function create(UserExamDataProvider $userExamDataProvider): void
    {
        $user = $this->userRepository->find($userExamDataProvider->getUserId());
        $examQuestion = $this->examQuestionRepository->find($userExamDataProvider->getExamQuestionId());

        $userExam = new UserQuestion();

        $userExam
            ->setUser($user)
            ->setExamQuestion($examQuestion)
            ->setAnswer($userExamDataProvider->getAnswer());

        $this->entityManager->persist($userExam);
        $this->entityManager->flush();
    }

    public function updateAnswer(UserExamDataProvider $userExamDataProvider): void
    {
        $userExam = $this->userExamRepository->find($userExamDataProvider->getId());

        $userExam->setAnswer($userExamDataProvider->getAnswer());

        $this->entityManager->flush();
    }
}