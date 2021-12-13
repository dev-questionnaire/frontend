<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\Repository;

use App\Components\UserQuestion\Persistence\Mapper\UserQuestionMapper;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\UserQuestion;
use App\Repository\UserRepository;
use \App\Repository\QuestionRepository;

class UserQuestionRepository implements UserQuestionRepositoryInterface
{
    public function __construct(
        private \App\Repository\UserQuestionRepository $userExamRepository,
        private UserRepository $userRepository,
        private QuestionRepository $questionRepository,
        private UserQuestionMapper $mapper,
    )
    {
    }

    public function getByExamQuestionAndUser(int $userId, int $questionId): ?UserQuestionDataProvider
    {
        $user = $this->userRepository->find($userId);
        $question = $this->questionRepository->find($questionId);

        $userExam = $this->userExamRepository->findOneBy([
            'user' => $user,
            'question' => $question,
        ]);

        if(!$userExam instanceof UserQuestion) {
            return null;
        }

        return $this->mapper->map($userExam);
    }
}