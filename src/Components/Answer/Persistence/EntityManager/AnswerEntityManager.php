<?php
declare(strict_types=1);

namespace App\Components\Answer\Persistence\EntityManager;

use App\DataTransferObject\AnswerDataProvider;
use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;

class AnswerEntityManager implements AnswerEntityManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private QuestionRepository $questionRepository,
    )
    {
    }

    public function create(AnswerDataProvider $answerDataProvider): void
    {
        //$question = $this->questionRepository->find($answerDataProvider->getQuestionId());
        $question = new Question();
        $question->id = $answerDataProvider->getQuestionId();

        $answer = new Answer();

        $answer
            ->setQuestion($question)
            ->setAnswer($answerDataProvider->getAnswer())
            ->setCorrect($answerDataProvider->getCorrect());

        $this->entityManager->persist($answer);
        $this->entityManager->flush();
    }
}