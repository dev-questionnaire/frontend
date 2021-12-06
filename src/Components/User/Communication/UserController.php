<?php

namespace App\Components\User\Communication;

use App\Components\User\Business\FacadeUser;
use App\GeneratedDataTransferObject\UserDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function __construct(private FacadeUser $facadeUser)
    {
    }

    /**
     * @Route ("/user/register", name="app_user_register")
     */
    public function register(Request $request): Response
    {
        $errors = [];

        if($request->isMethod('POST')) {
            $currentDate = (new \DateTime())->format('d.m.Y');

            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $verPassword = $request->request->get('verPassword');

            $userDTO = new UserDataProvider();
            $userDTO->setEmail($email)
                ->setPassword($password)
                ->setVerificationPassword($verPassword)
                ->setRoles(['ROLE_USER'])
                ->setCreatedAt($currentDate)
                ->setUpdatedAt($currentDate);

            $errors = $this->facadeUser->create($userDTO);
        }

        return $this->render('user/register.html.twig', [
            'errors' => $errors,
        ]);
    }
}
