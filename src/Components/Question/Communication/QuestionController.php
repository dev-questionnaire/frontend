<?php

namespace App\Components\Question\Communication;

use App\Components\Question\Dependency\BridgeExamInterface;
use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionController extends AbstractController
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
        private BridgeExamInterface         $bridgeExam,
        private UserQuestionRepositoryInterface $userQuestionRepository,
    )
    {
    }

    /**
     * @Route ("/exam/{exam}/question/{questionArrayIndex}", name="app_question")
     */
    public function index(UserInterface $user, string $exam, int $questionArrayIndex = 0): Response
    {
        $examDataProvider = $this->bridgeExam->getByName($exam);

        if ($examDataProvider === null) {
            return $this->redirectToRoute('app_exam');
        }

        $userEmail = $user->getUserIdentifier();
        //TODO get USerQuestion and Check if answerd or not and if correct Question (n)

        $questionDataProviderList = $this->questionRepository->getByExam($exam);


        while (count($questionDataProviderList) <= $questionArrayIndex) {
            $questionArrayIndex--;
        }

        $currentQuestionDataProvider = $questionDataProviderList[$questionArrayIndex];

        return $this->render('question/question.html.twig', [
            'exam' => $exam,
            'question' => $currentQuestionDataProvider,
            'nextQuestionId' => $questionArrayIndex + 1,
        ]);
    }
}
