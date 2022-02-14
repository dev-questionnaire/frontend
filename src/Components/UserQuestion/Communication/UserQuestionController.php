<?php
declare(strict_types=1);

namespace App\Components\UserQuestion\Communication;

use App\Components\User\Persistence\Repository\UserRepositoryInterface;
use App\Components\UserQuestion\Dependency\BridgeQuestionInterface;
use App\Components\UserQuestion\Dependency\BridgeUserInterface;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\Controller\CustomAbstractController;
use App\Components\User\Service\ApiSecurity;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
class UserQuestionController extends CustomAbstractController
{
    public function __construct(
        private UserQuestionRepositoryInterface $userQuestionRepository,
        private BridgeUserInterface $bridgeUser,
        private BridgeQuestionInterface $bridgeQuestion,
        RequestStack $requestStack,
        ApiSecurity $api,
        UserRepositoryInterface $userRepository,
    )
    {
        parent::__construct($requestStack, $api, $userRepository);
    }

    #[Route("/admin/exam/{examSlug}/question/{questionSlug}", name: "app_admin_userQuestion")]
    public function userQuestion(string $examSlug, string $questionSlug): Response
    {
        $userQuestionList = $this->userQuestionRepository->findByExamAndQuestionSlug($examSlug, $questionSlug);
        $userList = $this->bridgeUser->getAllIndexedByUserId();
        $question = $this->bridgeQuestion->getByExamAndQuestionSlug($examSlug, $questionSlug);

        return $this->render('userQuestion/admin/questionUser.html.twig', [
            'question' => $question,
            'userQuestionList' => $userQuestionList,
            'userList' => $userList,
        ]);
    }
}