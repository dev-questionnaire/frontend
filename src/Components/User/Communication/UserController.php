<?php

namespace App\Components\User\Communication;

use App\Components\User\Persistence\DataTransferObject\UserDataProvider;
use App\Components\User\Persistence\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @Route ("/user/register", name="app_user_register")
     */
    public function register(Request $request): Response
    {
        $errors = [];

        if($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $verPassword = $request->request->get('verPassword');
        }

        return $this->render('user/register.html.twig', [
            'errors' => $errors,
        ]);
    }
}
