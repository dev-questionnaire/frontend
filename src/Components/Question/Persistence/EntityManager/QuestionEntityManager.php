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
        $examQuestion = new Question();

        $exam = $this->examRepository->find($questionDataProvider->getExamId());

        $examQuestion
            ->setExam($exam)
            ->setQuestion($questionDataProvider->getQuestion());

        $this->entityManager->persist($examQuestion);
        $this->entityManager->flush();
    }
}