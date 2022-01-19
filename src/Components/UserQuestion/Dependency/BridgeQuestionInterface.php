<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Dependency;

use App\DataTransferObject\QuestionDataProvider;

interface BridgeQuestionInterface
{
    public function getByExamAndQuestionSlug(string $examSlug, string $questionSlug): ?QuestionDataProvider;
}