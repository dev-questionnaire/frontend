<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;

interface BridgeUserQuestionInterface
{
    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(User $user, string $examSlug): array;
}