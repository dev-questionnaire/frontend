<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

use App\DataTransferObject\UserQuestionDataProvider;

interface BridgeUserQuestionInterface
{
    /**
     * @return UserQuestionDataProvider[]
     */
    public function getByUserAndExamIndexedByQuestionSlug(string $userEmail, string $examSlug): array;
}