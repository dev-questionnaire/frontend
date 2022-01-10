<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\ExamCommunication;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    //Need to be logged in
    public function testAppExam(): void
    {
        $repository = $this->container->get(UserRepository::class);

        $testUser = $repository->findOneBy(['email' => 'test@email.com']);

        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Exams');
    }

}