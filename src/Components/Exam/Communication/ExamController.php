<?php

namespace App\Components\Exam\Communication;

use App\Components\Exam\Dependency\BridgeQuestionInterface;
use App\Components\Exam\Dependency\BridgeUserInterface;
use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\Components\Exam\Dependency\BridgeUserQuestionInterface;
use App\Components\User\Persistence\Repository\UserRepositoryInterface;
use App\Controller\CustomAbstractController;
use App\Components\User\Service\ApiSecurity;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
class ExamController extends CustomAbstractController
{
    public function __construct(
        private ExamRepositoryInterface     $examRepository,
        private BridgeQuestionInterface     $bridgeQuestion,
        private BridgeUserQuestionInterface $bridgeUserQuestion,
        private BridgeUserInterface         $bridgeUser,
        RequestStack $requestStack,
        ApiSecurity $api,
        UserRepositoryInterface $userRepository,
    )
    {
        parent::__construct($requestStack, $api, $userRepository);
    }

    #[Route("/", name: "app_exam")]
    public function index(): Response
    {
        //Check if logged in
        $authorized = $this->authorized();
        if ($authorized !== null) {
            return $authorized;
        }

        $examDataProviderList = $this->examRepository->getAll();

        return $this->render('exam/exam.html.twig', [
            'examList' => $examDataProviderList,
            'loggedInUser' => $this->getUserDataProvider(),
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

        if (!$examDataProvider) {
            return $this->redirectToRoute('app_exam');
        }

        $questionDataProviderList = $this->bridgeQuestion->getByExamSlug($examSlug);

        $userQuestionDataProviderList = $this->bridgeUserQuestion->getByUserAndExamIndexedByQuestionSlug($userDataProvider->getId(), $examSlug);

        $percentAnswerCorrectAndUserAnswerLists = $this->bridgeUserQuestion->getPercentAndAnswerCorrectAndUserAnswerList($questionDataProviderList, $userQuestionDataProviderList);

        if (!array_key_exists('percent', $percentAnswerCorrectAndUserAnswerLists)
            || !array_key_exists('answeredCorrect', $percentAnswerCorrectAndUserAnswerLists)) {
            return $this->redirectToRoute("app_question", ['examSlug' => $examSlug]);
        }

        /** @var float $calculatedPercent */
        $calculatedPercent = $percentAnswerCorrectAndUserAnswerLists['percent'];

        /** @var array<array-key, boolean|null> $answeredCorrect */
        $answeredCorrect = $percentAnswerCorrectAndUserAnswerLists['answeredCorrect'];

        foreach ($answeredCorrect as $answer) {
            if ($answer === null) {
                return $this->redirectToRoute("app_question", ['examSlug' => $examSlug]);
            }
        }

        return $this->render('exam/result.html.twig', [
            'exam' => $examDataProvider,
            'examPercent' => $calculatedPercent,
            'questionList' => $questionDataProviderList,
            'answeredCorrect' => $answeredCorrect,
            'loggedInUser' => $this->getUserDataProvider(),
        ]);
    }

    #[Route("/admin/exam", name: "app_admin_exam")]
    public function examAdmin(): Response
    {
        $examDataProviderList = $this->examRepository->getAll();

        return $this->render('exam/admin/exam.html.twig', [
            'examList' => $examDataProviderList,
            'loggedInUser' => $this->getUserDataProvider(),
        ]);
    }

    #[Route("/admin/user/{id}/exam", name: "app_admin_examUser")]
    public function examUserAdmin(int $id): Response
    {
        $examDataProviderList = $this->examRepository->getAll();

        return $this->render('exam/admin/examUser.html.twig', [
            'id' => $id,
            'examList' => $examDataProviderList,
            'loggedInUser' => $this->getUserDataProvider(),
        ]);
    }

    #[Route("/admin/user/{id}/exam/{examSlug}/result", name: "app_admin_exam_result")]
    public function resultAdmin(int $id, string $examSlug): Response
    {
        $examDataProvider = $this->examRepository->getBySlug($examSlug);

        $questionDataProviderList = $this->bridgeQuestion->getByExamSlug($examSlug);

        if ($this->bridgeUser->doesUserExist($id) === false) {
            return $this->redirectToRoute('app_admin_users');
        }

        if (!$examDataProvider) {
            return $this->redirectToRoute('app_admin_examUser', ['id' => $id]);
        }

        $userQuestionDataProviderList = $this->bridgeUserQuestion->getByUserAndExamIndexedByQuestionSlug($id, $examSlug);

        $percentAnswerCorrectAndUserAnswerLists = $this->bridgeUserQuestion->getPercentAndAnswerCorrectAndUserAnswerList($questionDataProviderList, $userQuestionDataProviderList, true);

        $calculatedPercent = $percentAnswerCorrectAndUserAnswerLists['percent'];
        $userAnswerList = $percentAnswerCorrectAndUserAnswerLists['userAnswerList'];
        $answeredCorrect = $percentAnswerCorrectAndUserAnswerLists['answeredCorrect'];

        return $this->render('exam/admin/result.html.twig', [
            'exam' => $examDataProvider,
            'examPercent' => $calculatedPercent,
            'userAnswers' => $userAnswerList,
            'questionList' => $questionDataProviderList,
            'answeredCorrect' => $answeredCorrect,
            'loggedInUser' => $this->getUserDataProvider(),
        ]);
    }
}
