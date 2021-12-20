<?php
declare(strict_types=1);

namespace App\Components\Exam\Dependency;

use App\DataTransferObject\UserQuestionDataProvider;

interface BridgeUserQuestionInterface
{
    public function getByUserAndQuestion(string $userEmail, string $questionSlug): ?UserQuestionDataProvider;
}