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
        private UserQuestionMapper                     $mapper,
    )
    {
    }

    public function findOneByQuestionAndUser(string $questionSlug, int $userId): ?UserQuestionDataProvider
    {
        $userQuestion = $this->userQuestionRepository->findOneByQuestionAndUser($questionSlug, $userId);

        if (!$userQuestion instanceof UserQuestion) {
            return null;
        }

        return $this->mapper->map($userQuestion);
    }

    /**
     * @return UserQuestionDataProvider[]
     */
    public function findByExamAndUserIndexedByQuestionSlug(string $examSlug, int $userId): array
    {
        $userQuestionDataProviderList = [];

        $userQuestionList = $this->userQuestionRepository->findByExamAndUser($examSlug, $userId);

        foreach ($userQuestionList as $userQuestion) {
            $slug = $userQuestion->getQuestionSlug();

            if ($slug === null) {
                throw new \RuntimeException("no slug provided");
            }

            $userQuestionDataProviderList[$slug] = $this->mapper->map($userQuestion);
        }

        return $userQuestionDataProviderList;
    }

    /**
     * @return UserQuestionDataProvider[]
     */
    public function findByExamAndQuestionSlug(string $examSlug, string $questionSlug): array
    {
        $userQuestionDataProviderList = [];

        $userQuestionList = $this->userQuestionRepository->findBy(['examSlug' => $examSlug, 'questionSlug' => $questionSlug]);

        foreach ($userQuestionList as $userQuestion) {
            $userQuestionDataProviderList[] = $this->mapper->map($userQuestion);
        }

        return $userQuestionDataProviderList;
    }
}