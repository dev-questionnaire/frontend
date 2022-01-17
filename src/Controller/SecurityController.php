<?php

namespace App\Controller;

use App\Controller\Forms\Login;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route("/login", name: "app_login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //TODO having problems with login symfony form
        $form = $this->createForm(Login::class);

        return $this->renderForm('security/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'form' => $form,
        ]);
    }

    #[Route("/logout", name: "app_logout")]
    public function logout(): void
    {
        throw new \Exception('logout() should never be reached!');
    }
}
