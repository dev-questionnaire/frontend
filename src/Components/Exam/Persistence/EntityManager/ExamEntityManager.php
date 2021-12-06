<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\EntityManager;

use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\Entity\Exam;
use App\GeneratedDataTransferObject\ExamDataProvider;
use Doctrine\ORM\EntityManagerInterface;

class ExamEntityManager implements ExamEntityManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ExamRepositoryInterface $examRepository,
    )
    {
    }

    public function create(ExamDataProvider $examDataProvider): void
    {
        $examEntity = new Exam();
        $currentDate = new \DateTime();

        $examEntity->setName($examDataProvider->getName())
            ->setCreatedAt($currentDate)
            ->setUpdatedAt($currentDate);

        $this->entityManager->persist($examEntity);
        $this->entityManager->flush();
    }

    public function update(ExamDataProvider $examDataProvider): void
    {
        //TODO
    }

    public function delete(int $id): void
    {
        //TODO
    }
}