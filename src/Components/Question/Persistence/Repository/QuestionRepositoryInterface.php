<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\Repository;

use App\DataTransferObject\QuestionDataProvider;

interface QuestionRepositoryInterface
{
    public function getByName(string $name): ?QuestionDataProvider;

    public function getById(int $id): ?QuestionDataProvider;
}