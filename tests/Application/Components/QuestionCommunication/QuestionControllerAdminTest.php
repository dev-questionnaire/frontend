<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\QuestionCommunication;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class QuestionControllerAdminTest extends WebTestCase
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

        $repository = $this->container->get(UserRepository::class);

        $testUser = $repository->findOneBy(['email' => 'admin@valantic.com']);

        $this->client->loginUser($testUser);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();

        $this->entityManager = null;
    }

    public function testShowAdminQuestions(): void
    {
        $this->client->request('GET', '/admin/exam/solid/question');

        self::assertResponseIsSuccessful();
    }
}