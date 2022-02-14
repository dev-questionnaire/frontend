<?php

namespace App\Components\Question\Communication;

use App\Components\Question\Dependency\BridgeExamInterface;
use App\Components\Question\Dependency\BridgeUserQuestionInterface;
use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;
use App\Components\User\Persistence\Repository\UserRepositoryInterface;
use App\Controller\CustomAbstractController;
use App\DataTransferObject\QuestionDataProvider;
use App\Components\User\Service\ApiSecurity;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
class QuestionController extends CustomAbstractController
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
        private BridgeUserQuestionInterface $bridgeUserQuestion,
        private BridgeExamInterface $bridgeExam,
        RequestStack $requestStack,
        ApiSecurity $api,
        UserRepositoryInterface $userRepository,
    )
    {
        parent::__construct($requestStack, $api, $userRepository);
    }

    #[Route("/exam/{examSlug}/question", name: "app_question")]
    public function index(Request $request, string $examSlug): Response
    {
        $questionDataProviderList = $this->questionRepository->getByExamSlug($examSlug);

        $userDataProvider = $this->getUserDataProvider();

        if($userDataProvider->getId() === null) {
            throw new \RuntimeException("User is not logged in");
        }

        $currentQuestionDataProvider = $this->getCurrentQuestion($questionDataProviderList, $examSlug, $userDataProvider->getId());

        if($currentQuestionDataProvider === null)
        {
            return $this->redirectToRoute('app_exam_result', ['examSlug' => $examSlug]);
        }

        //Build Form
        $formBuilder = $this->createFormBuilder();

        /** @var string $answer */
        foreach ($currentQuestionDataProvider->getAnswers() as $answer)
        {
            $formBuilder->add(str_replace(' ', '_', $answer), CheckboxType::class, ['required' => false]);
        }

        $formBuilder->add('next_question', SubmitType::class);

        $form = $formBuilder->getForm();

        //Save Answers
        $form->handleRequest($request);

        if ($form->isSubmitted() /**&& $form->isValid()**/) {
            /** @var array<array-key, bool> $data */
            $data = $form->getData();

            $this->bridgeUserQuestion->updateAnswer($currentQuestionDataProvider, $userDataProvider->getId(), $data);

            return $this->redirectToRoute('app_question', ['examSlug' => $examSlug]);
        }

        $exam = $this->bridgeExam->getBySlug($examSlug);

        //Render
        return $this->renderForm('question/question.html.twig', [
            'form' => $form,
            'exam' => $exam,
            'question' => $currentQuestionDataProvider,
        ]);
    }

    #[Route("/admin/exam/{examSlug}/question", name: "app_admin_question")]
    public function adminQuestion(string $examSlug): Response
    {
        $exam = $this->bridgeExam->getBySlug($examSlug);

        $questionList = $this->questionRepository->getByExamSlug($examSlug);

        return $this->render('question/admin/question.html.twig', [
            'questionList' => $questionList,
            'exam' => $exam,
        ]);
    }

    /**
     * @param QuestionDataProvider[] $questionDataProviderList
     */
    private function getCurrentQuestion(array $questionDataProviderList, string $examSlug, int $userId): ?QuestionDataProvider
    {
        foreach ($questionDataProviderList as $questionDataProvider) {
            $userQuestionDataProvider = $this->bridgeUserQuestion->getByUserAndQuestion($userId, $questionDataProvider->getSlug());

            if ($userQuestionDataProvider === null) {
                $this->bridgeUserQuestion->create($questionDataProvider->getSlug(), $examSlug, $userId);

                return $questionDataProvider;
            }

            if ($userQuestionDataProvider->getAnswers() === null) {
                return $questionDataProvider;
            }
        }
        return null;
    }
}
