<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\UserCommunication;

use App\DataFixtures\AppFixtures;
use App\DataTransferObject\UserDataProvider;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        $appFixtures = $this->container->get(AppFixtures::class);
        $appFixtures->load($this->entityManager);

        $repository = $this->container->get(UserRepository::class);

        $testUser = $repository->findOneBy(['email' => 'user@valantic.com']);

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

    public function testAppUserRegisterSubmit(): void
    {
        $crawler = $this->client->request(
            'POST',
            '/user/register',
            [
                'register' => [
                    'email' => 'test@nexus-united.com',
                    'password' => '!aA12345',
                    'verificationPassword' => '!aA12345',
                ],
            ]
        );

        $userRepository = $this->container->get(\App\Components\User\Persistence\Repository\UserRepository::class);
        $user = $userRepository->getByEmail('test@nexus-united.com');

        self::assertInstanceOf(UserDataProvider::class, $user);
    }

    public function testAppUserProfile(): void
    {
        $crawler = $this->client->request('GET', '/user/profile');

        self::assertResponseIsSuccessful();
    }

    public function testAppUserProfileSubmit(): void
    {
        $crawler = $this->client->request(
            'POST',
            '/user/profile',
            [
                'update' => [
                    'email' => 'user@valantic.com',
                    'password' => '!aA12345',
                    'verificationPassword' => '!aA12345',
                ],
            ]
        );

        $userRepository = $this->container->get(\App\Components\User\Persistence\Repository\UserRepository::class);
        $user = $userRepository->getByEmail('user@valantic.com');

        self::assertInstanceOf(UserDataProvider::class, $user);
    }

    public function testAppUserDelete(): void
    {
        $crawler = $this->client->request('GET', '/user/delete');

        self::assertResponseIsSuccessful();
    }

    public function testAppUserDeleteSubmit(): void
    {
        $crawler = $this->client->request(
            'POST',
            '/user/delete',
            [
                'delete' => [
                ],
            ]
        );

        $userRepository = $this->container->get(\App\Components\User\Persistence\Repository\UserRepository::class);
        $user = $userRepository->getByEmail('user@valantic.com');

        self::assertNull($user);

        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }
}