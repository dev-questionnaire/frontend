<?php
declare(strict_types=1);

namespace App\Components\ExamQuestion\Persistence\EntityManager;

use App\DataTransferObject\ExamQuestionDataProvider;
use App\Entity\ExamQuestion;
use App\Repository\ExamRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExamQuestionEntityManager implements ExamQuestionEntityManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ExamRepository $examRepository,
    )
    {
    }

    public function create(ExamQuestionDataProvider $examQuestionDataProvider): void
    {
        $examQuestion = new ExamQuestion();

        $exam = $this->examRepository->find($examQuestionDataProvider->getExamId());

        $examQuestion
            ->setExam($exam)
            ->setQuestion($examQuestionDataProvider->getQuestion())
            ->setCorrect($examQuestionDataProvider->getCorrect());

        $this->entityManager->persist($examQuestion);
        $this->entityManager->flush();
    }
}