<?php
declare(strict_types=1);

namespace App\Tests\Acceptance\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
    }

    public function testLoginPage()
    {
        $crawler = $this->client->request(
            'GET',
            '/login'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', 'Login');
    }
}