<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\ExamCommunication;

use App\DataFixtures\AppFixtures;
use App\Entity\UserQuestion;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExamControllerAdminTest extends WebTestCase
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

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeQuery('DELETE FROM user_question');
        $connection->executeQuery('ALTER TABLE user_question AUTO_INCREMENT=0');
        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');

        $connection->close();

        $this->entityManager = null;
    }

    public function testExamAdmin(): void
    {
        $this->client->request('GET', '/admin/exam');

        self::assertResponseIsSuccessful();
    }

    public function testUserExamAdmin(): void
    {
        $this->client->request('GET', '/admin/user/1/exam');

        self::assertResponseIsSuccessful();
    }

    public function testExamResultAdmin(): void
    {
        $this->client->request('GET', '/admin/user/1/exam/oop/result');

        self::assertResponseIsSuccessful();
    }

    public function testExamResultAdminWithQuestions(): void
    {
        $userRepository = $this->container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'user@valantic.com']);

        $userQuestion_1 = new UserQuestion();
        $userQuestion_1
            ->setExamSlug('solid')
            ->setQuestionSlug('s_in_solid')
            ->setUser($testUser)
            ->setAnswers(["Solid" => false, "Sexy_Programming" => false, "Single_possibility" => true, "Single_like_a_pringle" => false]);

        $userQuestion_2 = new UserQuestion();
        $userQuestion_2
            ->setExamSlug('solid')
            ->setQuestionSlug('o_in_solid')
            ->setUser($testUser)
            ->setAnswers(['Open_relation' => true, 'Oral__ex' => false, 'Open_close' => false, 'Opfer' => false]);

        $this->entityManager->persist($userQuestion_1);
        $this->entityManager->persist($userQuestion_2);
        $this->entityManager->flush();

        $this->client->request('GET', '/admin/user/1/exam/oop/result');

        self::assertResponseIsSuccessful();
    }
}