<?php
declare(strict_types=1);

namespace App\Components\Question\Dependency;

use App\Components\UserQuestion\Business\FacadeUserQuestionInterface;
use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

class BridgeUserQuestion implements BridgeUserQuestionInterface
{
    public function __construct(
        private FacadeUserQuestionInterface $facadeUserQuestion,
    )
    {
    }

    public function create(string $questionSlug, string $examSlug, User $user): void
    {
        $this->facadeUserQuestion->create($questionSlug, $examSlug, $user);
    }

    public function updateAnswer(QuestionDataProvider $questionDataProvider, User $user, array $formData): void
    {
        $this->facadeUserQuestion->updateAnswer($questionDataProvider, $user, $formData);
    }

    public function delete(int $id): void
    {
        $this->facadeUserQuestion->delete($id);
    }

    public function getByUserAndQuestion(User $user, string $questionSlug): ?UserQuestionDataProvider
    {
        return $this->facadeUserQuestion->getByUserAndQuestion($user, $questionSlug);
    }
}