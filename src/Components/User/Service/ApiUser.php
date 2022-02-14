<?php
declare(strict_types=1);

namespace App\Components\User\Service;

use App\DataTransferObject\UserDataProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiUser
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

    public function findByToken(string $token): array
    {
        $response = $this->httpClient->request(
            'GET',
            $this->apiUrl . '/api/user/findByToken', [
                'json' => [
                    'token' => $token,
                ],
            ]
        );

        $content = $response->getContent();
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    public function findById(int $id): array
    {
        $response = $this->httpClient->request(
            'GET',
            $this->apiUrl . '/api/user/findById', [
                'json' => [
                    'id' => $id,
                ],
            ]
        );

        $content = $response->getContent();
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    public function checkEmailTaken(UserDataProvider $userDataProvider): array
    {
        $response = $this->httpClient->request(
            'GET',
            $this->apiUrl . '/api/user/checkEmailTaken', [
                'json' => [
                    'id' => $userDataProvider->getId(),
                    'email' => $userDataProvider->getEmail(),
                ],
            ]
        );

        $content = $response->getContent();
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    public function findAll(): array
    {
        $response = $this->httpClient->request(
            'GET',
            $this->apiUrl . '/api/user/findAll', [
            ]
        );

        $content = $response->getContent();
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}