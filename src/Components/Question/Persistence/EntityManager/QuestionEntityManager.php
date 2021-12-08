<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\EntityManager;

use App\DataTransferObject\QuestionDataProvider;
use App\Entity\Question;
use App\Repository\ExamRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuestionEntityManager implements QuestionEntityManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ExamRepository $examRepository,
    )
    {
    }

    public function create(QuestionDataProvider $questionDataProvider): void
    {
        $questionEntity = new Question();

        $examEntity = $this->examRepository->find($questionDataProvider->getExamId());

        $questionEntity
            ->setName($questionDataProvider->getName())
            ->setAnswers($questionDataProvider->getAnswers())
            ->setExam($examEntity);

        $this->entityManager->persist($questionEntity);
        $this->entityManager->flush();
    }

    public function update(QuestionDataProvider $questionDataProvider): void
    {
        //TODO create update method
    }

    public function delete(int $id): void
    {
        //TODO create delete method
    }
}