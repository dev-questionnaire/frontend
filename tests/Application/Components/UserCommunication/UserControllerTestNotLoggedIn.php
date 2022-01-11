<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\UserCommunication;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserControllerTestNotLoggedIn extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testAppUserProfile(): void
    {
        $this->client->request('GET', '/user/profile');

        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }

    public function testAppUserDelete(): void
    {
        $this->client->request('GET', '/user/delete');

        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }
}