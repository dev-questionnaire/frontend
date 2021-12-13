<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\EntityManager;

use App\DataTransferObject\QuestionDataProvider;

interface QuestionEntityManagerInterface
{
    public function create(QuestionDataProvider $questionDataProvider): void;
}