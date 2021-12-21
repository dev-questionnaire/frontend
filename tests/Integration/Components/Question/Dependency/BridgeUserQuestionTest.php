<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Question\Dependency;

use App\Components\Question\Dependency\BridgeUserQuestion;
use App\Components\Question\Dependency\BridgeUserQuestionInterface;
use App\Components\UserQuestion\Business\FacadeUserQuestion;
use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManager;
use App\Components\UserQuestion\Persistence\Mapper\UserQuestionMapper;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepository;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BridgeUserQuestionTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?BridgeUserQuestionInterface $bridgeUserQuestion;
    private ?UserQuestionRepositoryInterface $userQuestionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userQuestionRepository = new UserQuestionRepository(
            $container->get(UserRepository::class),
            $container->get(\App\Repository\UserQuestionRepository::class),
            new UserQuestionMapper());

        $this->bridgeUserQuestion = new BridgeUserQuestion(
            new FacadeUserQuestion($this->userQuestionRepository,
                new UserQuestionEntityManager($this->entityManager,
                    $container->get(\App\Repository\UserQuestionRepository::class),
                    $container->get(UserRepository::class))));

        $user = new User();
        $user
            ->setEmail('test@email.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword('123');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
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

        $this->userQuestionRepository = null;
        $this->bridgeUserQuestion = null;
        $this->entityManager = null;
    }

    public function testCreateUpdateDeleteGet(): void
    {
        $this->bridgeUserQuestion->create('slug', 'exam', 'test@email.com');

        $userQuestionDataProvider = $this->bridgeUserQuestion->getByUserAndQuestion('test@email.com', 'slug');

        self::assertInstanceOf(UserQuestionDataProvider::class, $userQuestionDataProvider);
        self::assertNull($userQuestionDataProvider->getAnswer());

        $userQuestionDataProvider->setAnswer(false);

        $this->bridgeUserQuestion->updateAnswer($userQuestionDataProvider);

        $userQuestionDataProvider = $this->bridgeUserQuestion->getByUserAndQuestion('test@email.com', 'slug');
        self::assertFalse($userQuestionDataProvider->getAnswer());

        $this->bridgeUserQuestion->delete($userQuestionDataProvider->getId());
        self::assertNull($this->bridgeUserQuestion->getByUserAndQuestion('test@email.com', 'slug'));
    }
}