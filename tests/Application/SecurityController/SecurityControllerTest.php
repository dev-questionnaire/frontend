<?php
declare(strict_types=1);

namespace App\Tests\Application\SecurityController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testAppLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Azubi Exams');
    }
}