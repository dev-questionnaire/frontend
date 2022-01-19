<?php
declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminControllerTest extends WebTestCase
{
    private ?EntityManager $entityManager;
    private ContainerInterface $container;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->container = static::getContainer();

        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');

        $appFixtures = $this->container->get(AppFixtures::class);
        $appFixtures->load($this->entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeQuery('DELETE FROM user_question');
        $connection->executeQuery('ALTER TABLE user_question AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');

        $connection->close();

        $this->entityManager = null;
    }
    /**
    public function testAppIndex(): void
    {

        $this->client->request('GET', '/admin');

        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }

    public function testAppIndexLoggedInUser(): void
    {
        $user = $this->container->get(UserRepository::class)->findOneBy(['email' => 'user@valantic.com']);
        $this->client->loginUser($user);

        $this->client->request('GET', '/admin');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }
    */

    public function testAppIndexLoggedInAdmin(): void
    {
        $user = $this->container->get(UserRepository::class)->findOneBy(['email' => 'admin@valantic.com']);
        $this->client->loginUser($user);

        $this->client->request('GET', '/admin');

        $this->assertResponseIsSuccessful();
    }
}