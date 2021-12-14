<?php

namespace App\Components\Question\Communication;

use App\Components\Answer\Persistence\Repository\AnswerRepositoryInterface;
use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;
use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManager;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\Repository\ExamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
    )
    {
    }

    /**
     * @Route ("/exam/{exam_id}/question/{question_index}", name="app_question")
     */
    public function index(int $exam_id, int $question_index = 0): Response
    {
        $questionList = $this->questionRepository->getByExamId($exam_id);
        $currentQuestion = $questionList[$question_index];

        $nextQuestion = $question_index;

        if(count($questionList) < $question_index) {
            $nextQuestion++;
        }

        return $this->render('question/question.html.twig', [
            'question' => $currentQuestion,
            'answerList' => $currentQuestion->getAnswerDataProviders(),
            'exam_id' => $exam_id,
            'nextQuestion' => $nextQuestion,
        ]);
    }
}
