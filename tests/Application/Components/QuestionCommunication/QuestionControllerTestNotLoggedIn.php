<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\QuestionCommunication;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class QuestionControllerTestNotLoggedIn extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testAppQuestion(): void
    {
        $this->client->request('GET', '/exam/solid/question');

        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }
}