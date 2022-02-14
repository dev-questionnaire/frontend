<?php

namespace App\Components\User\Communication;

use App\Components\User\Business\FacadeUserInterface;
use App\Components\User\Communication\Forms\Delete;
use App\Components\User\Communication\Forms\Register;
use App\Components\User\Communication\Forms\Update;
use App\Components\User\Dependency\BridgeUserQuestion;
use App\Components\User\Persistence\Repository\UserRepositoryInterface;
use App\Controller\CustomAbstractController;
use App\DataTransferObject\ErrorDataProvider;
use App\DataTransferObject\UserDataProvider;
use App\Components\User\Service\ApiSecurity;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress PropertyNotSetInConstructor */
class UserController extends CustomAbstractController
{

    public function __construct(
        private FacadeUserInterface     $facadeUser,
        private BridgeUserQuestion      $bridgeUserQuestion,
        private UserRepositoryInterface $userRepository,
        RequestStack $requestStack,
        ApiSecurity $api,
    )
    {
        parent::__construct($requestStack, $api, $userRepository);
    }

    #[Route("/user/register", name: "app_user_register")]
    public function register(Request $request): Response
    {
        $errors = [];

        $userDataProvider = new UserDataProvider();

        $form = $this->createForm(Register::class, $userDataProvider);

        $form->handleRequest($request);
        if ($form->isSubmitted()/**&& $form->isValid()**/) {
            /** @var UserDataProvider $userDataProvider */
            $userDataProvider = $form->getData();

            /** @var array<array-key, string> $errors */
            $errors = $this->facadeUser->create($userDataProvider);

            if (empty($errors)) {
                return new RedirectResponse('/login');
            }
        }

        return $this->renderForm('user/register.html.twig', [
            'form' => $form,
            'errors' => $errors,
        ]);
    }

    #[Route("/user/profile", name: "app_user_profile")]
    public function profile(Request $request): Response
    {
        //Check if logged in
        $loggedIn = $this->isLoggedIn();
        if ($loggedIn !== null) {
            return $loggedIn;
        }

        $errors = [];

        $userDataProvider = $loggedInUser = $this->getUserDataProvider();

        $form = $this->createForm(Update::class, $userDataProvider);

        $form->handleRequest($request);
        if ($form->isSubmitted()/**&& $form->isValid()**/) {
            /** @var UserDataProvider $userDataProviderForm */
            $userDataProviderForm = $form->getData();

            /** @var string $formPassword */
            $formPassword = $userDataProviderForm->getPassword();

            if ($userDataProviderForm->getEmail() !== $userDataProvider->getEmail() || !password_verify($formPassword, $userDataProvider->getPassword())) {
                $errors = $this->facadeUser->update($userDataProviderForm);
            }
        }

        return $this->renderForm('user/profile.html.twig', [
            'form' => $form,
            'errors' => $errors,
            'loggedInUser' => $loggedInUser,
        ]);
    }

    #[Route("/user/delete", name: "app_user_delete")]
    public function deleteUser(Request $request): Response
    {
        //Check if logged in
        $loggedIn = $this->isLoggedIn();
        if ($loggedIn !== null) {
            return $loggedIn;
        }

        $userDataProvider = $this->getUserDataProvider();

        if ($userDataProvider->getId() === null) {
            throw new \RuntimeException("User is not logged in");
        }

        $form = $this->createForm(Delete::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()/**&& $form->isValid()**/) {
            $session = new Session();
            $session->invalidate();

            $this->bridgeUserQuestion->deleteByUser($userDataProvider->getId());
            $this->facadeUser->delete($userDataProvider->getId());

            return new RedirectResponse('/');
        }

        return $this->renderForm('user/delete.html.twig', [
            'form' => $form,
            'userEmail' => $userDataProvider->getEmail(),
        ]);
    }

    #[Route("/admin/users", name: "app_admin_users")]
    public function showUsers(): Response
    {
        //Check if logged in
        $loggedIn = $this->isLoggedIn('ROLE_ADMIN');
        if ($loggedIn !== null) {
            return $loggedIn;
        }

        $userList = $this->userRepository->findAll();

        return $this->render('user/admin/users.html.twig', [
            'userList' => $userList,
            'loggedInUser' => $this->getUserDataProvider(),
        ]);
    }

    #[Route("/admin/user/{id}", name: "app_admin_user")]
    public function showUser(int $id): Response
    {
        //Check if logged in
        $loggedIn = $this->isLoggedIn();
        if ($loggedIn !== null) {
            return $loggedIn;
        }

        $user = $this->userRepository->getById($id);

        if(!$user instanceof UserDataProvider) {
            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('user/admin/user.html.twig', [
            'user' => $user,
            'loggedInUser' => $this->getUserDataProvider(),
        ]);
    }
}
