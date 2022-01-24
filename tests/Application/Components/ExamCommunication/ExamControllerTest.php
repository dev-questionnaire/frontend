<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\ExamCommunication;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Entity\UserQuestion;
use App\Repository\UserQuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Safe\com_event_sink;

class ExamControllerTest extends WebTestCase
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
        $appFixtures->load($this->entityManager, ['test' => true]);

        $repository = $this->container->get(UserRepository::class);

        $testUser = $repository->findOneBy(['email' => 'user@valantic.com']);

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

    public function testAppExam(): void
    {
        $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
    }

    public function testAppExamResult(): void
    {
        $this->client->request('GET', 'exam/solid/result');

        self::assertResponseIsSuccessful();
    }

    public function testAppExamResultRedirectAnswersNull(): void
    {
        $userQuestion = $this->container->get(UserQuestionRepository::class)->findOneBy(['questionSlug' => 'o_in_solid']);

        $userQuestion->setAnswers(null);
        $this->entityManager->flush();

        $this->client->request('GET', 'exam/solid/result');
        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }

    public function testAppExamResultRedirectNotAllQuestionsAnswered(): void
    {
        $userQuestion = $this->container->get(UserQuestionRepository::class)->findOneBy(['questionSlug' => 'o_in_solid']);

        $this->entityManager->remove($userQuestion);
        $this->entityManager->flush();

        $this->client->request('GET', 'exam/solid/result');
        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }

    public function testExamNotFound(): void
    {
        $this->client->request('GET', 'exam/blablabla/result');

        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }
}