<?php

namespace App\Components\User\Communication;

use App\Components\User\Business\FacadeUser;
use App\Components\User\Communication\Forms\Delete;
use App\Components\User\Communication\Forms\Register;
use App\Components\User\Communication\Forms\Update;
use App\Components\User\Persistence\Repository\UserRepositoryInterface;
use App\DataTransferObject\UserDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{

    public function __construct(private FacadeUser $facadeUser, private UserRepositoryInterface $userRepository)
    {
    }

    #[Route("/user/register", name: "app_user_register")]
    public function register(Request $request): Response
    {

        $errors = [];

        $userDataProvider = new UserDataProvider();

        $form = $this->createForm(Register::class, $userDataProvider);

        $form->handleRequest($request);
        if ($form->isSubmitted() /**&& $form->isValid()**/) {
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
    public function profile(UserInterface $user, Request $request): Response
    {
        $errors = [];

        $userDataProvider = $this->userRepository->getByEmail($user->getUserIdentifier());

        $form = $this->createForm(Update::class, $userDataProvider);

        $form->handleRequest($request);
        if ($form->isSubmitted() /**&& $form->isValid()**/) {
            $userDataProvider = $form->getData();

            if ($userDataProvider->getEmail() !== $user->getUserIdentifier() || !password_verify($userDataProvider->getPassword(), $user->getPassword())) {
                $errors = $this->facadeUser->update($userDataProvider);
            }
        }

        return $this->renderForm('user/profile.html.twig', [
            'form' => $form,
            'errors' => $errors,
        ]);
    }

    #[Route("/user/delete", name: "app_user_delete")]
    public function deleteUser(UserInterface $user, Request $request): Response
    {
        //TODO delete userQuestion Rows befor deleting user
        $userDTO = $this->userRepository->getByEmail($user->getUserIdentifier());

        $form = $this->createForm(Delete::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() /**&& $form->isValid()**/) {
            $session = new Session();
            $session->invalidate();

            $this->facadeUser->delete($userDTO->getId());

            return new RedirectResponse('/');
        }

        return $this->renderForm('user/delete.html.twig', [
            'form' => $form,
            'user' => $userDTO,
        ]);
    }
}
