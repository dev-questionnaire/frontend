<?php

namespace App\Components\Exam\Communication;

use App\Components\Exam\Dependency\BridgeQuestionInterface;
use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\Components\Exam\Dependency\BridgeUserQuestionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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

    #[Route("/{exam}/result", name: "app_exam_result")]
    public function result(UserInterface $user, string $exam): Response
    {
        //TODO add Slug to exam to get Data with slug not with exam name!
        $userEmail = $user->getUserIdentifier();

        $examDataProvider = $this->examRepository->getByName($exam);

        $questionDataProviderList = $this->bridgeQuestion->getByExam($examDataProvider->getName());

        $questionQuantity = count($questionDataProviderList);
        $countQuestions = 0;

        foreach ($questionDataProviderList as $questionDataProvider) {
            $userQuestionDataProvider = $this->bridgeUserQuestion->getByUserAndQuestion($userEmail, $questionDataProvider->getSlug());

            if ($userQuestionDataProvider === null) {
                return $this->redirectToRoute('app_question', ['exam' => $exam]);
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
        ]);
    }
}
