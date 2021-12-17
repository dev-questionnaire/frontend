<?php

namespace App\Components\Question\Communication;

use App\Components\Question\Dependency\BridgeExamInterface;
use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
        private BridgeExamInterface         $bridgeExam,
    )
    {
    }

    /**
     * @Route ("/exam/{exam}/question/{question_id}", name="app_question")
     */
    public function index(string $exam, int $question_id = 0): Response
    {
        $examDataProvider = $this->bridgeExam->getByName($exam);

        if ($examDataProvider === null) {
            return $this->redirectToRoute('app_exam');
        }

        $questionDataProviderList = $this->questionRepository->getByExam($exam);


        while (count($questionDataProviderList) <= $question_id) {
            $question_id--;
        }

        $currentQuestionDataProvider = $questionDataProviderList[$question_id];

        return $this->render('question/question.html.twig', [
            'exam' => $exam,
            'question' => $currentQuestionDataProvider,
            'nextQuestionId' => $question_id + 1,
        ]);
    }
}
