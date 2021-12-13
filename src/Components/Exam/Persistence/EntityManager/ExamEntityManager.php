<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\EntityManager;

use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\Entity\Exam;
use App\DataTransferObject\ExamDataProvider;
use Doctrine\ORM\EntityManagerInterface;

class ExamEntityManager implements ExamEntityManagerInterface
{
    public function __construct(
        private EntityManagerInterface  $entityManager,
    )
    {
    }

    public function create(ExamDataProvider $examDataProvider): void
    {
        $examEntity = new Exam();

        $examEntity->setName($examDataProvider->getName());

        $this->entityManager->persist($examEntity);
        $this->entityManager->flush();
    }
}