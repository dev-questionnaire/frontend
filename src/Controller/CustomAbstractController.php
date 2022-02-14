<?php
declare(strict_types=1);

namespace App\Controller;

use App\Components\User\Persistence\Repository\UserRepositoryInterface;
use App\Components\User\Service\ApiSecurity;
use App\DataTransferObject\UserDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class CustomAbstractController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private ApiSecurity      $api,
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    protected function authorized(string $role = 'ROLE_USER'): ?Response
    {
        $token = $this->getToken();

        if($token === null) {
            return $this->redirectToRoute('app_login');
        }

        $content = $this->api->authorized($token, $role);

        if($content['authorized'] === false) {
            unset($token);
            return $this->redirectToRoute('app_login');
        }

        return null;
    }

    protected function getUserDataProvider(): ?UserDataProvider
    {
        $token = $this->getToken();

        if($token === null) {
            return null;
        }

        $userDataProvider = $this->userRepository->findByToken($token);

        if (!$userDataProvider instanceof UserDataProvider) {
            return null;
        }

        return $userDataProvider;
    }

    protected function getToken(): ?string
    {
        return $this->requestStack->getSession()->get('authentication_token');
    }
}