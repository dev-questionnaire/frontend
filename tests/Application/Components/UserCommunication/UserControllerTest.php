<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\UserCommunication;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends WebTestCase
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

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();

        $this->entityManager = null;
    }

    public function testAppUserRegister(): void
    {
        $crawler = $this->client->request('GET', '/user/register');

        self::assertResponseIsSuccessful();
    }

    public function testAppUserProfile(): void
    {
        $crawler = $this->client->request('GET', '/user/profile');

        self::assertResponseIsSuccessful();
    }

    public function testAppUserDelete(): void
    {
        $crawler = $this->client->request('GET', '/user/delete');

        self::assertResponseIsSuccessful();
    }
}