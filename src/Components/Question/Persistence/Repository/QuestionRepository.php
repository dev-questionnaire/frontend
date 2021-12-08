<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\Repository;

use App\Components\Question\Persistence\Mapper\QuestionMapper;
use App\DataTransferObject\QuestionDataProvider;
use App\Entity\Question;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function __construct(
        private \App\Repository\QuestionRepository $questionRepository,
        private QuestionMapper $questionMapper,
    )
    {
    }

    public function getByName(string $name): ?QuestionDataProvider
    {
        $questionEntity = $this->questionRepository->findOneBy(['name' => $name]);

        if(!$questionEntity instanceof Question) {
            return null;
        }

        return $this->questionMapper->map($questionEntity);
    }

    public function getById(int $id): ?QuestionDataProvider
    {
        $questionEntity = $this->questionRepository->find($id);

        if(!$questionEntity instanceof Question) {
            return null;
        }

        return $this->questionMapper->map($questionEntity);
    }
}