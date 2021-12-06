<?php

namespace App\Components\User\Communication;

use App\Components\User\Business\FacadeUser;
use App\Components\User\Persistence\Repository\UserRepositoryInterface;
use App\GeneratedDataTransferObject\UserDataProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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

    /**
     * @Route ("/user/register", name="app_user_register")
     */
    public function register(Request $request): Response
    {
        $errors = [];

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $verPassword = $request->request->get('verPassword');

            $userDTO = new UserDataProvider();
            $userDTO->setEmail($email)
                ->setPassword($password)
                ->setVerificationPassword($verPassword)
                ->setRoles(['ROLE_USER']);

            $errors = $this->facadeUser->create($userDTO);

            if (empty($errors)) {
                return new RedirectResponse('/login');
            }
        }

        return $this->render('user/register.html.twig', [
            'errors' => $errors,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route ("/user/profile", name="app_user_profile")
     */
    public function profile(UserInterface $user, Request $request): Response
    {
        $errors = [];

        if ($request->isMethod('POST')) {
            $userDTO = $this->userRepository->getByEmail($user->getUserIdentifier());

            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $verPassword = $request->request->get('verPassword');

            $changesMade = false;

            if($email !== $user->getUserIdentifier()) {
                $userDTO->setEmail($email);
                $changesMade = true;
            }

            if(!password_verify($password, $user->getPassword())) {
                $userDTO->setPassword($password)
                    ->setVerificationPassword($verPassword);
                $changesMade = true;
            }

            if($changesMade === true) {
                $errors = $this->facadeUser->update($userDTO);
            }
        }

        return $this->render('user/profile.html.twig', [
            'errors' => $errors,
        ]);
    }

    /**
     * @IsGranted ("ROLE_USER")
     * @Route ("/user/delete", name="app_user_delete")
     */
    public function deleteUser(UserInterface $user): Response
    {
        $userDTO = $this->userRepository->getByEmail($user->getUserIdentifier());

        //TODO to logout User bevor deleting it why does this work? https://stackoverflow.com/questions/49401265/symfony-4-good-practice-to-remove-your-own-user-account-while-connected
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();

        $this->facadeUser->delete($userDTO->getId());

        return $this->render('user/delete.html.twig', []);
    }
}
