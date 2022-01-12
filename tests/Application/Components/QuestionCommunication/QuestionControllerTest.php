<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\QuestionCommunication;

use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepository;
use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class QuestionControllerTest extends WebTestCase
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
        $appFixtures->load($this->entityManager, ['user' => true]);

        $repository = $this->container->get(UserRepository::class);

        $testUser = $repository->findOneBy(['email' => 'user@email.com']);

        $this->client->loginUser($testUser);
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

    public function testAppQuestion(): void
    {
        $this->client->request('GET', '/exam/solid/question');

        self::assertResponseIsSuccessful();
    }

    public function testAppQuestionSubmit(): void
    {
        $crawler = $this->client->request(
            'POST',
            '/exam/solid/question',
            [
                'form' => [
                    'Single_possibility' => '1',
                ],
            ]
        );

        $userQuestionRepo = $this->container->get(UserQuestionRepository::class);
        $userQuestion = $userQuestionRepo->getByUserAndQuestion('user@email.com', 's_in_solid');

        self::assertTrue($userQuestion->getAnswer());
        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());


    }
}