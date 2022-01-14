<?php

namespace App\Components\Exam\Communication;

use App\Components\Exam\Dependency\BridgeQuestionInterface;
use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\Components\Exam\Dependency\BridgeUserQuestionInterface;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExamController extends AbstractController
{
    public function __construct(
        private ExamRepositoryInterface     $examRepository,
        private BridgeQuestionInterface     $bridgeQuestion,
        private BridgeUserQuestionInterface $bridgeUserQuestion,
    )
    {
    }

    #[Route("/", name: "app_exam")]
    public function index(): Response
    {
        $examDataProviderList = $this->examRepository->getAll();

        return $this->render('exam/exam.html.twig', [
            'examList' => $examDataProviderList,
        ]);
    }

    #[Route("/exam/{examSlug}/result", name: "app_exam_result")]
    public function result(string $examSlug): Response
    {
        $userDataProvider = $this->getUserDataProvider();

        $examDataProvider = $this->examRepository->getBySlug($examSlug);

        $questionDataProviderList = $this->bridgeQuestion->getByExamSlug($examSlug);

        $userQuestionDataProviderList = $this->bridgeUserQuestion->getByUserAndExamIndexedByQuestionSlug($userDataProvider->getId(), $examSlug);

        $questionQuantity = count($questionDataProviderList);
        $countQuestions = 0;

        foreach ($questionDataProviderList as $key => $questionDataProvider) {
            $userQuestionDataProvider = $userQuestionDataProviderList[$questionDataProvider->getSlug()];

            $userQuestionDataProviderList[$key] = $userQuestionDataProvider;

            if ($userQuestionDataProvider->getAnswer() === null) {
                return $this->redirectToRoute('app_question', ['examSlug' => $examSlug]);
            }

            if ($userQuestionDataProvider->getAnswer() === true) {
                $countQuestions++;
            }
        }

        $calculatedPercent = 100;

        if ($questionQuantity !== 0) {
            $calculatedPercent = $countQuestions / $questionQuantity * 100;
        }

        return $this->render('exam/result.html.twig', [
            'exam' => $examDataProvider,
            'examPercent' => $calculatedPercent,
            'questionList' => $questionDataProviderList,
            'userQuestionList' => $userQuestionDataProviderList,
        ]);
    }
}
