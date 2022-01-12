<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Persistence\Repository;

use App\Components\UserQuestion\Persistence\Mapper\UserQuestionMapper;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;
use App\Entity\UserQuestion;
use App\Repository\UserRepository;

class UserQuestionRepository implements UserQuestionRepositoryInterface
{
    public function __construct(
        private \App\Repository\UserQuestionRepository $userQuestionRepository,
        private UserQuestionMapper $mapper,
    )
    {
    }

    public function findeOneByQuestionAndUser(string $questionSlug, int $userId): ?UserQuestionDataProvider
    {
        $userQuestion = $this->userQuestionRepository->findeOneByQuestionAndUser($questionSlug, $userId);

        if(!$userQuestion instanceof UserQuestion) {
            return null;
        }

        return $this->mapper->map($userQuestion);
    }

    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByExamAndUserIndexedByQuestionSlug(string $examSlug, int $userId): array
    {
        $userQuestionDataProviderList = [];

        $userQuestionList = $this->userQuestionRepository->findeByExamAndUser($examSlug, $userId);

        foreach ($userQuestionList as $userQuestion) {
            $userQuestionDataProviderList[$userQuestion->getQuestionSlug()] = $this->mapper->map($userQuestion);
        }

        return $userQuestionDataProviderList;
    }
}