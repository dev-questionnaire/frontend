<?php

namespace App\Components\User\Communication;

use App\Components\User\Business\FacadeUserInterface;
use App\Components\User\Communication\Forms\Delete;
use App\Components\User\Communication\Forms\Register;
use App\Components\User\Communication\Forms\Update;
use App\Components\User\Dependency\BridgeUserQuestion;
use App\Components\User\Persistence\Repository\UserRepositoryInterface;
use App\DataTransferObject\UserDataProvider;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function __construct(
        private FacadeUserInterface     $facadeUser,
        private UserRepositoryInterface $userRepository,
        private BridgeUserQuestion      $bridgeUserQuestion,
    )
    {
    }

    #[Route("/user/register", name: "app_user_register")]
    public function register(Request $request): Response
    {

        $errors = [];

        $userDataProvider = new UserDataProvider();

        $form = $this->createForm(Register::class, $userDataProvider);

        $form->handleRequest($request);
        if ($form->isSubmitted()/**&& $form->isValid()**/) {
            $userDataProvider = $form->getData();

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
        $errors = [];

        $userDataProvider = $this->getUserDataProvider();

        if ($userDataProvider->getId() === null
            || $userDataProvider->getEmail() === null
            || $userDataProvider->getPassword() === null
        ) {
            throw new \RuntimeException("User is not logged in");
        }

        $form = $this->createForm(Update::class, $userDataProvider);

        $form->handleRequest($request);
        if ($form->isSubmitted()/**&& $form->isValid()**/) {
            $userDataProviderForm = $form->getData();

            if ($userDataProviderForm->getEmail() !== $userDataProvider->getEmail() || !password_verify($userDataProviderForm->getPassword(), $userDataProvider->getPassword())) {
                $errors = $this->facadeUser->update($userDataProviderForm);
            }
        }

        return $this->renderForm('user/profile.html.twig', [
            'form' => $form,
            'errors' => $errors,
        ]);
    }

    #[Route("/user/delete", name: "app_user_delete")]
    public function deleteUser(Request $request): Response
    {
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
}
