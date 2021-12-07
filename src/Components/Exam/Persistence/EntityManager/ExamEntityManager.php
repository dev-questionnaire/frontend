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
        private EntityManagerInterface  $entityManager,
        private ExamRepositoryInterface $examRepository,
    )
    {
    }

    public function create(ExamDataProvider $examDataProvider): void
    {
        $examEntity = new Exam();
        $currentDate = new \DateTime();

        $examEntity->setName($examDataProvider->getName());

        if ($examDataProvider->getCreatedAt() === '' || $examDataProvider->getUpdatedAt() === '') {
            $examEntity
                ->setCreatedAt($currentDate)
                ->setUpdatedAt($currentDate);
        } else {
            $examEntity
                ->setCreatedAt(date_create_from_format('d.m.Y', $examDataProvider->getCreatedAt()))
                ->setUpdatedAt(date_create_from_format('d.m.Y', $examDataProvider->getUpdatedAt()));
        }

        $this->entityManager->persist($examEntity);
        $this->entityManager->flush();
    }

    public function update(ExamDataProvider $examDataProvider): void
    {
        //TODO make update method
    }

    public function delete(int $id): void
    {
        //TODO make delete method
    }
}