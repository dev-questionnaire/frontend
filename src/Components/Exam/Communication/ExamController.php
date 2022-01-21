<?php

namespace App\Components\Exam\Communication;

use App\Components\Exam\Business\FacadeExamInterface;
use App\Components\Exam\Dependency\BridgeQuestionInterface;
use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\Components\Exam\Dependency\BridgeUserQuestionInterface;
use App\Controller\AbstractController;
use App\DataTransferObject\QuestionDataProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

/** @psalm-suppress PropertyNotSetInConstructor */
class ExamController extends AbstractController
{
    public function __construct(
        private ExamRepositoryInterface     $examRepository,
        private BridgeQuestionInterface     $bridgeQuestion,
        private BridgeUserQuestionInterface $bridgeUserQuestion,
        private FacadeExamInterface         $facadeExam,
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

        if ($userDataProvider->getId() === null) {
            throw new \RuntimeException("User is not logged in");
        }

        $examDataProvider = $this->examRepository->getBySlug($examSlug);

        $questionDataProviderList = $this->bridgeQuestion->getByExamSlug($examSlug);

        $userQuestionDataProviderList = $this->bridgeUserQuestion->getByUserAndExamIndexedByQuestionSlug($userDataProvider->getId(), $examSlug);

        $percentAnswerCorrectAndUserAnswerLists = $this->facadeExam->getPercentAndAnswerCorrectAndUserAnswerList($questionDataProviderList, $userQuestionDataProviderList);

        if (!array_key_exists('percent', $percentAnswerCorrectAndUserAnswerLists)
            || !array_key_exists('answeredCorrect', $percentAnswerCorrectAndUserAnswerLists)) {
            return $this->redirectToRoute('app_question', ['examSlug' => $examSlug]);
        }

        $calculatedPercent = $percentAnswerCorrectAndUserAnswerLists['percent'];
        $answeredCorrect = $percentAnswerCorrectAndUserAnswerLists['answeredCorrect'];

        return $this->render('exam/result.html.twig', [
            'exam' => $examDataProvider,
            'examPercent' => $calculatedPercent,
            'questionList' => $questionDataProviderList,
            'answeredCorrect' => $answeredCorrect,
        ]);
    }

    #[Route("/admin/exam", name: "app_admin_exam")]
    public function examAdmin(): Response
    {
        $examDataProviderList = $this->examRepository->getAll();

        return $this->render('exam/admin/exam.html.twig', [
            'examList' => $examDataProviderList,
        ]);
    }

    #[Route("/admin/user/{id}/exam", name: "app_admin_examUser")]
    public function examUserAdmin(int $id): Response
    {
        $examDataProviderList = $this->examRepository->getAll();

        return $this->render('exam/admin/examUser.html.twig', [
            'id' => $id,
            'examList' => $examDataProviderList,
        ]);
    }

    #[Route("/admin/user/{id}/exam/{examSlug}/result", name: "app_admin_exam_result")]
    public function resultAdmin(int $id, string $examSlug): Response
    {
        $examDataProvider = $this->examRepository->getBySlug($examSlug);

        $questionDataProviderList = $this->bridgeQuestion->getByExamSlug($examSlug);

        $userQuestionDataProviderList = $this->bridgeUserQuestion->getByUserAndExamIndexedByQuestionSlug($id, $examSlug);

        $percentAnswerCorrectAndUserAnswerLists = $this->facadeExam->getPercentAndAnswerCorrectAndUserAnswerList($questionDataProviderList, $userQuestionDataProviderList, true);

        if (!array_key_exists('percent', $percentAnswerCorrectAndUserAnswerLists)
            || !array_key_exists('answeredCorrect', $percentAnswerCorrectAndUserAnswerLists)
            || !array_key_exists('userAnswerList', $percentAnswerCorrectAndUserAnswerLists)) {
            $this->redirectToRoute('app_admin_examUser', ['id' => $id]);
        }

        $calculatedPercent = $percentAnswerCorrectAndUserAnswerLists['percent'];
        $userAnswerList = $percentAnswerCorrectAndUserAnswerLists['userAnswerList'];
        $answeredCorrect = $percentAnswerCorrectAndUserAnswerLists['answeredCorrect'];

        return $this->render('exam/admin/result.html.twig', [
            'exam' => $examDataProvider,
            'examPercent' => $calculatedPercent,
            'userAnswers' => $userAnswerList,
            'questionList' => $questionDataProviderList,
            'answeredCorrect' => $answeredCorrect,
        ]);
    }
}
