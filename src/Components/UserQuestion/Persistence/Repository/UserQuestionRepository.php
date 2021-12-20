<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\Repository;

use App\Components\UserQuestion\Persistence\Mapper\UserQuestionMapper;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\UserQuestion;
use App\Repository\UserRepository;

class UserQuestionRepository implements UserQuestionRepositoryInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private \App\Repository\UserQuestionRepository $userQuestionRepository,
        private UserQuestionMapper $mapper,
    )
    {
    }

    public function getByUserAndExam(string $userEmail, string $questionSlug): ?UserQuestionDataProvider
    {
        $user = $this->userRepository->findOneBy(['email' => $userEmail]);

        $userQuestion = $this->userQuestionRepository->findOneBy(['questionSlug' => $questionSlug, 'user' => $user]);

        if(!$userQuestion instanceof UserQuestion) {
            return null;
        }

        return $this->mapper->map($userQuestion);
    }
}