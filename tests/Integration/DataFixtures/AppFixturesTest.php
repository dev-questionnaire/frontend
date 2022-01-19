<?php
declare(strict_types=1);

namespace App\Tests\Integration\DataFixtures;

use App\DataFixtures\AppFixtures;
use App\Repository\UserQuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixturesTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?UserRepository $userRepository;
    private ?AppFixtures $appFixtures;
    private ?UserPasswordHasherInterface $userPasswordHasher;
    private ?UserQuestionRepository $userQuestionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userRepository = $container->get(UserRepository::class);
        $this->userQuestionRepository = $container->get(UserQuestionRepository::class);
        $this->appFixtures = $container->get(AppFixtures::class);
        $this->userPasswordHasher = $container->get(UserPasswordHasherInterface::class);
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

        $this->userPasswordHasher = null;
        $this->appFixtures = null;
        $this->userRepository = null;
        $this->entityManager = null;
    }

    public function testLoad(): void
    {
        $this->appFixtures->load($this->entityManager);

        $userList = $this->userRepository->findAll();

        self::assertSame('admin@valantic.com', $userList[0]->getEmail());
        self::assertSame('ROLE_ADMIN', $userList[0]->getRoles()[0]);
        self::assertSame('ROLE_USER', $userList[0]->getRoles()[1]);
        self::assertTrue($this->userPasswordHasher->isPasswordValid($userList[0], 'admin'));

        self::assertSame('user@valantic.com', $userList[1]->getEmail());
        self::assertSame('ROLE_USER', $userList[1]->getRoles()[0]);
        self::assertTrue($this->userPasswordHasher->isPasswordValid($userList[1], 'user'));
    }

    public function testLoadTest(): void
    {
        $this->appFixtures->load($this->entityManager, ['test' => true]);

        $userList = $this->userRepository->findAll();

        self::assertSame('admin@valantic.com', $userList[0]->getEmail());

        self::assertSame('user@valantic.com', $userList[1]->getEmail());

        $userQuestionList = $this->userQuestionRepository->findAll();

        $expected = [
            'Solid' => false,
            'Sexy_Programming' => false,
            'Single_possibility' => true,
            'Single_like_a_pringle' => false,
        ];

        self::assertSame($expected, $userQuestionList[0]->getAnswers());

        $expected = [
            'Open_relation' => true,
            'Oral__ex' => false,
            'Open_close' => false,
            'Opfer' => false
        ];

        self::assertSame($expected, $userQuestionList[1]->getAnswers());
        self::assertNull($userQuestionList[2]->getAnswers());

        self::assertSame('s_in_solid', $userQuestionList[0]->getQuestionSlug());
        self::assertSame('o_in_solid', $userQuestionList[1]->getQuestionSlug());
        self::assertSame('harun_alter', $userQuestionList[2]->getQuestionSlug());

        self::assertSame('solid', $userQuestionList[0]->getExamSlug());
        self::assertSame('solid', $userQuestionList[1]->getExamSlug());
        self::assertSame('harun', $userQuestionList[2]->getExamSlug());

        self::assertSame($userList[1], $userQuestionList[0]->getUser());
        self::assertSame($userList[1], $userQuestionList[1]->getUser());
        self::assertSame($userList[1], $userQuestionList[2]->getUser());
    }
}