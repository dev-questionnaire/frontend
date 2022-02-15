<?php
declare(strict_types=1);

namespace App\Components\User\Service;

use App\DataTransferObject\UserDataProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiSecurity
{
    private string $apiUrl;

    public function __construct(
        private HttpClientInterface $httpClient,
        ParameterBagInterface       $parameterBag,
    )
    {
        //rtrim entfernt vom ende "/" falls vorhanden
        $this->apiUrl = rtrim($parameterBag->get('api.url'), '/');
    }

    public function login(UserDataProvider $userDataProvider): array
    {
        $response = $this->httpClient->request(
            'POST',
            $this->apiUrl . '/api/login', [
                'json' => [
                    'username' => $userDataProvider->getEmail(),
                    'password' => $userDataProvider->getPassword(),
                ],
            ]
        );

        $content = $response->getContent();

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    public function authorized(string $token, string $role): array
    {
        $response = $this->httpClient->request(
            'POST',
            $this->apiUrl . '/api/authorized', [
                'json' => [
                    'token' => $token,
                    'role' => $role,
                ],
            ]
        );

        $content = $response->getContent();
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    public function logout(string $token): array
    {
        $response = $this->httpClient->request(
            'POST',
            $this->apiUrl . '/api/logout', [
                'json' => [
                    'token' => $token,
                ],
            ]
        );

        $content = $response->getContent();
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}