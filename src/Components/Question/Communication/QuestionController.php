<?php

namespace App\Components\Question\Communication;

use App\Components\Answer\Persistence\Repository\AnswerRepositoryInterface;
use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
        private AnswerRepositoryInterface $answerRepository,
    )
    {
    }

    /**
     * @Route ("/exam/{exam_id}/question/{question_id}", name="app_question")
     */
    public function index(int $exam_id, int $question_id = 0): Response
    {
        $questionList = $this->questionRepository->getByExamId($exam_id);
        $currentQuestion = $questionList[$question_id];

        $nextQuestion = $question_id;

        if(count($questionList) < $question_id) {
            $nextQuestion++;
        }

        $answerList = $this->answerRepository->getByQuestion($currentQuestion->getId());

        return $this->render('question/question.html.twig', [
            'question' => $currentQuestion,
            'answerList' => $answerList,
            'exam_id' => $exam_id,
            'nextQuestion' => $nextQuestion,
        ]);
    }
}
