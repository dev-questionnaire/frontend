<?php
declare(strict_types=1);

namespace App\Components\UserExam\Persistence\Repository;

use App\Components\UserExam\Persistence\Mapper\UserExamMapper;
use App\DataTransferObject\UserExamDataProvider;
use App\Entity\UserExam;
use App\Repository\ExamQuestionRepository;
use App\Repository\UserRepository;

class UserExamRepository implements UserExamRepositoryInterface
{
    public function __construct(
        private \App\Repository\UserExamRepository $userExamRepository,
        private UserRepository $userRepository,
        private ExamQuestionRepository $examQuestionRepository,
        private UserExamMapper $mapper,
    )
    {
    }

    public function getByExamQuestionAndUser(int $userId, int $examQuestionId): ?UserExamDataProvider
    {
        $user = $this->userRepository->find($userId);
        $examQuestion = $this->examQuestionRepository->find($examQuestionId);

        $userExam = $this->userExamRepository->findOneBy([
            'user' => $user,
            'examQuestion' => $examQuestion,
        ]);

        if(!$userExam instanceof UserExam) {
            return null;
        }

        return $this->mapper->map($userExam);
    }
}