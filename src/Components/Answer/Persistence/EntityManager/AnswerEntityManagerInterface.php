<?php
declare(strict_types=1);

namespace App\Components\Answer\Persistence\EntityManager;

use App\DataTransferObject\AnswerDataProvider;

interface AnswerEntityManagerInterface
{
    public function create(AnswerDataProvider $answerDataProvider): void;
}