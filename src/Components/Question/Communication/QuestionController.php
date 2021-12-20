<?php

namespace App\Components\Question\Communication;

use App\Components\Question\Dependency\BridgeUserQuestionInterface;
use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;
use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\String\b;

class QuestionController extends AbstractController
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
        private BridgeUserQuestionInterface $bridgeUserQuestion,
    )
    {
    }

    /**
     * @Route ("/exam/{exam}/question", name="app_question")
     */
    public function index(Request $request, UserInterface $user, string $exam): Response
    {
        $questionDataProviderList = $this->questionRepository->getByExam($exam);

        $userEmail = $user->getUserIdentifier();

        $currentQuestionDataProvider = $this->getCurrentQuestion($questionDataProviderList, $userEmail);

        if($currentQuestionDataProvider === null)
        {
            return $this->redirectToRoute('app_exam_result', ['exam' => $exam]);
        }

        //Build Form
        $formBuilder = $this->createFormBuilder();

        foreach ($currentQuestionDataProvider->getAnswers() as $answer)
        {
            $formBuilder->add(str_replace(' ', '_', $answer), CheckboxType::class, array('required' => false));
        }

        $formBuilder->add('next_question', SubmitType::class);

        $form = $formBuilder->getForm();

        //Check Question
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();

            $answer = null;

            foreach ($data as $key => $value) {
                if($answer === false)
                {
                    break;
                }

                if($value === false)
                {
                    continue;
                }

                foreach ($currentQuestionDataProvider->getRightQuestions() as $rightQuestion) {
                    if(str_replace(' ', '_', $rightQuestion) === $key) {
                        $answer = true;

                        continue;
                    }
                    $answer = false;
                    break;
                }
            }
            $userQuestionDataProvider = $this->getUserQuestionDataProvider($userEmail, $currentQuestionDataProvider->getSlug());
            $userQuestionDataProvider->setAnswer($answer);

            $this->bridgeUserQuestion->update($userQuestionDataProvider);

            return $this->redirectToRoute('app_question', ['exam' => $exam]);
        }

        //Render
        return $this->renderForm('question/question.html.twig', [
            'form' => $form,
            'exam' => $exam,
            'question' => $currentQuestionDataProvider,
        ]);
    }

    private function getCurrentQuestion(array $questionDataProviderList, string $userEmail): ?QuestionDataProvider
    {
        foreach ($questionDataProviderList as $questionDataProvider) {
            $userQuestionDataProvider = $this->getUserQuestionDataProvider($userEmail, $questionDataProvider->getSlug());

            if ($userQuestionDataProvider === null) {
                $this->bridgeUserQuestion->create($questionDataProvider->getSlug(), $userEmail);

                return $questionDataProvider;
            }

            if ($userQuestionDataProvider->getAnswer() === null) {
                return $questionDataProvider;
            }
        }
        return null;
    }

    private function getUserQuestionDataProvider(string $userEmail, string $slug): ?UserQuestionDataProvider
    {
        return $this->bridgeUserQuestion->getByUserAndQuestion($userEmail, $slug);
    }
}
