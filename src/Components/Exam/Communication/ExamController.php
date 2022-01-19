<?php

namespace App\Components\Exam\Communication;

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

        //CorrectAnswer?
        $countQuestions = 0;
        $answeredCorrect = [];

        foreach ($questionDataProviderList as $questionDataProvider) {
            $slug = $questionDataProvider->getSlug();

            $answerList = $userQuestionDataProviderList[$slug]->getAnswers();

            if ($answerList === null) {
                return $this->redirectToRoute('app_question', ['examSlug' => $examSlug]);
            }

            $answeredCorrect[$slug] = null;

            /**
             * @var string $answer
             * @var bool $result
             */
            foreach ($answerList as $answer => $result) {
                $answeredCorrect[$slug] = $this->getCurrentAnsweredCorrect(
                    $questionDataProvider,
                    $answeredCorrect[$slug],
                    $answer, $result
                );
            }

            if ($answeredCorrect[$slug] === true) {
                $countQuestions++;
            }
        }

        $calculatedPercent = 100;

        $questionQuantity = count($questionDataProviderList);

        if ($questionQuantity !== 0) {
            $calculatedPercent = $countQuestions / $questionQuantity * 100;
        }

        return $this->render('exam/result.html.twig', [
            'exam' => $examDataProvider,
            'examPercent' => $calculatedPercent,
            'questionList' => $questionDataProviderList,
            'answeredCorrect' => $answeredCorrect,
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

        $countQuestions = 0;

        /** @var array<array-key, bool|null> $answeredCorrect */
        $answeredCorrect = [];

        /** @var array<array-key, string> $userAnswerList */
        $userAnswerList = [];

        foreach ($questionDataProviderList as $questionDataProvider) {

            $slug = $questionDataProvider->getSlug();
            $userAnswerList[$slug] = null;
            $answeredCorrect[$slug] = null;

            if(!array_key_exists($slug, $userQuestionDataProviderList)) {
                continue;
            }

            $userQuestionDataProvider = $userQuestionDataProviderList[$slug];

            $answerList = $userQuestionDataProvider->getAnswers();

            if ($answerList === null) {
                continue;
            }

            $answeredCorrect[$slug] = null;

            /**
             * @var string $answer
             * @var bool $result
             */
            foreach ($answerList as $answer => $result) {
                if ($result === true) {
                    $userAnswerList[$slug][] = str_replace('_', ' ', $answer);
                }

                $answeredCorrect[$slug] = $this->getCurrentAnsweredCorrect(
                    $questionDataProvider,
                    $answeredCorrect[$slug],
                    $answer, $result
                );
            }

            if ($answeredCorrect[$slug] === true) {
                $countQuestions++;
            }
        }

        $calculatedPercent = 100;
        $questionQuantity = count($questionDataProviderList);

        if ($questionQuantity !== 0) {
            $calculatedPercent = $countQuestions / $questionQuantity * 100;
        }

        return $this->render('exam/admin/result.html.twig', [
            'exam' => $examDataProvider,
            'examPercent' => $calculatedPercent,
            'userAnswers' => $userAnswerList,
            'questionList' => $questionDataProviderList,
            'answeredCorrect' => $answeredCorrect,
        ]);
    }

    private function getCurrentAnsweredCorrect(
        QuestionDataProvider $questionDataProvider,
        bool|null            $currentAnsweredCorrect,
        string               $answer,
        bool                 $result,
    ): bool|null
    {
        /** @var string $rightAnswer */
        foreach ($questionDataProvider->getRightQuestions() as $rightAnswer) {
            $rightAnswer = str_replace(' ', '_', $rightAnswer);

            if ($currentAnsweredCorrect === false) {
                break;
            }

            $rightAnswer = str_replace(' ', '_', $rightAnswer);

            if ($rightAnswer === $answer && $result === true) {
                $currentAnsweredCorrect = true;
                continue;
            }

            if ($rightAnswer === $answer && $result === false) {
                $currentAnsweredCorrect = false;
                continue;
            }

            if ($result === true) {
                $currentAnsweredCorrect = false;
            }
        }

        return $currentAnsweredCorrect;
    }
}
