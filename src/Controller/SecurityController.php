<?php

namespace App\Controller;

use App\Controller\Forms\Login;
use App\DataTransferObject\UserDataProvider;
use App\Components\User\Service\ApiSecurity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class SecurityController extends AbstractController
{
    public function __construct(
        private ApiSecurity      $api,
        private RequestStack $requestStack,
    )
    {
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(Request $request): Response
    {
        $error = null;

        //Create Login Form
        $userDataProvider = new UserDataProvider();
        $form = $this->createForm(Login::class, $userDataProvider);

        //Check if Button pressed
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userDataProvider = $form->getData();

            //Send request to login through api
            try {
                $content = $this->api->login($userDataProvider);
            } catch (ClientException $exception) {
                return $this->redirectToRoute('app_login');
            }

            //If successful
            if ($content['success'] === true) {
                $session = $this->requestStack->getSession();

                $tokenId = $content['tokenId'];

                //Path where temp token is saved
                $pfad = sprintf(__DIR__ . '/../../var/cache/temp/%s.txt', $tokenId);

                if(!file_exists($pfad)) {
                    return $this->renderForm('security/login.html.twig', [
                        'form' => $form,
                        'error' => "bad credentials",
                    ]);
                }

                $handle = fopen($pfad, 'rb'); //b = binary safe
                $token = fread($handle, filesize($pfad));
                fclose($handle);

                //Delete Temp token
                unlink($pfad);

                //Writes token into session
                $session->set('authentication_token', $token);

                return $this->redirectToRoute('app_exam');
            }

            $error = $content['message'];
        }

        return $this->renderForm('security/login.html.twig', [
            'form' => $form,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): Response
    {
        $apiToken = $this->requestStack->getSession()->get('authentication_token');

        if($apiToken !== null) {
            $content = $this->api->logout($apiToken);
            if(!$content['logout']) {
                return $this->redirectToRoute('app_login');
            }

            unset($apiToken);
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/token', name: 'app_token', methods: 'POST')]
    public function token(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $info = (array)json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $token = $info['token'] ?? '';
        $tokenId = $info['tokenId'] ?? '';

        if ($token === '' || $tokenId === '') {
            return $this->json([
                'generated' => false,
            ]);
        }

        $pfad = sprintf(__DIR__ . '/../../var/cache/temp/%s.txt', $tokenId);

        $handle = fopen($pfad, 'wb'); //b = binary safe
        fwrite($handle, $token);
        fclose($handle);

        return $this->json([
            'generated' => true,
        ]);
    }
}
