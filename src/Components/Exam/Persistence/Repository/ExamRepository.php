<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\Repository;

use App\Components\Exam\Persistence\Mapper\ExamMapperEntity;
use App\Entity\Exam;
use App\DataTransferObject\ExamDataProvider;

class ExamRepository implements ExamRepositoryInterface
{
    public function __construct(
        private \App\Repository\ExamRepository $examEntityRepository,
        private ExamMapperEntity $examMapper,
    )
    {
    }

    public function getById(int $id): ?ExamDataProvider
    {
        $examEntity = $this->examEntityRepository->find($id);

        if(!$examEntity instanceof Exam) {
            return null;
        }

        return $this->examMapper->map($examEntity);
    }

    public function getByName(string $name): ?ExamDataProvider
    {
        $examEntity = $this->examEntityRepository->findOneBy(['name' => $name]);

        if(!$examEntity instanceof Exam) {
            return null;
        }

        return $this->examMapper->map($examEntity);
    }

    /**
     * @return ExamDataProvider[]
     */
    public function getAll(): array
    {
        $examDTOList = [];

        $examEntities = $this->examEntityRepository->findAll();

        foreach ($examEntities as $examEntity) {
            $examDTOList[] = $this->examMapper->map($examEntity);
        }

        return $examDTOList;
    }
}