<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\ExamCommunication;

use App\Entity\User;
use App\Entity\UserQuestion;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

        $passwordHasher = $this->container->get(UserPasswordHasherInterface::class);

        $user = new User();
        $user
            ->setEmail('test@email.com')
            ->setPassword($passwordHasher->hashPassword($user, '123'))
            ->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $repository = $this->container->get(UserRepository::class);

        $testUser = $repository->findOneBy(['email' => 'test@email.com']);

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
        $userRepository = $this->container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'test@email.com']);

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

        $this->client->request('GET', 'exam/solid/result');

        self::assertResponseIsSuccessful();
    }

    public function testAppExamResultRedirect(): void
    {
        $userRepository = $this->container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'test@email.com']);

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
            ->setAnswers(null);

        $this->entityManager->persist($userQuestion_1);
        $this->entityManager->persist($userQuestion_2);
        $this->entityManager->flush();

        $this->client->request('GET', 'exam/solid/result');

        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }
}