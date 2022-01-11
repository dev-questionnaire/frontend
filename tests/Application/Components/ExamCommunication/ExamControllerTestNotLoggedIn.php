<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\ExamCommunication;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ExamControllerTestNotLoggedIn extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testAppExam(): void
    {
        $this->client->request('GET', '/');

        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }

    public function testAppExamResult(): void
    {
        $this->client->request('GET', '/exam/solid/result');

        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }
}