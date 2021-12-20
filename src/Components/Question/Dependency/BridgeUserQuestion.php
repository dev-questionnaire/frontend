<?php
declare(strict_types=1);

namespace App\Components\Question\Dependency;

use App\Components\UserQuestion\Business\FacadeUserQuestionInterface;
use App\DataTransferObject\UserQuestionDataProvider;

class BridgeUserQuestion implements BridgeUserQuestionInterface
{
    public function __construct(
        private FacadeUserQuestionInterface $facadeUserQuestion,
    )
    {
    }

    public function create(string $questionSlug, string $examSlug, string $userEmail): void
    {
        $this->facadeUserQuestion->create($questionSlug, $examSlug, $userEmail);
    }

    public function update(UserQuestionDataProvider $userQuestionDataProvider): void
    {
        $this->facadeUserQuestion->update($userQuestionDataProvider);
    }

    public function delete(int $id): void
    {
        $this->facadeUserQuestion->delete($id);
    }

    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider
    {
        return $this->facadeUserQuestion->getByUserAndQuestion($userEmail, $questionSlug);
    }
}