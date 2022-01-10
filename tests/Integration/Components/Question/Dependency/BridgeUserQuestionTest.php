<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Question\Dependency;

use App\Components\Question\Dependency\BridgeUserQuestion;
use App\Components\Question\Dependency\BridgeUserQuestionInterface;
use App\Components\Question\Persistence\Mapper\QuestionMapper;
use App\Components\Question\Persistence\Repository\QuestionRepository;
use App\Components\UserQuestion\Business\FacadeUserQuestion;
use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManager;
use App\Components\UserQuestion\Persistence\Mapper\UserQuestionMapper;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepository;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class BridgeUserQuestionTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?BridgeUserQuestionInterface $bridgeUserQuestion;
    private ?UserQuestionRepositoryInterface $userQuestionRepository;
    private ?QuestionRepository $questionRepository;

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

    public function testCreateDeleteGet(): void
    {
        $this->bridgeUserQuestion->create('slug', 'exam', 'test@email.com');

        $userQuestionDataProvider = $this->bridgeUserQuestion->getByUserAndQuestion('test@email.com', 'slug');

        self::assertInstanceOf(UserQuestionDataProvider::class, $userQuestionDataProvider);
        self::assertNull($userQuestionDataProvider->getAnswer());


        $this->bridgeUserQuestion->delete($userQuestionDataProvider->getId());
        self::assertNull($this->bridgeUserQuestion->getByUserAndQuestion('test@email.com', 'slug'));
    }

    public function testUpdate(): void
    {
        $this->bridgeUserQuestion->create('question', 'exam', 'test@email.com');

        $questionDataProvider = new QuestionDataProvider();
        $questionDataProvider
            ->setSlug('question')
            ->setQuestion('question')
            ->setRightQuestions([0 => 'answer 1'])
            ->setAnswers([
                0 => 'answer 1',
                1 => 'answer 2',
                2 => 'answer 3',
                3 => 'answer 4',
            ]);


        $formData =
            [
                'answer_1' => true,
                'answer_2' => false,
                'answer_3' => false,
                'answer_4' => false,
            ];

        $this->bridgeUserQuestion->updateAnswer($questionDataProvider, 'test@email.com', $formData);
        $userQuestionDataProvider = $this->bridgeUserQuestion->getByUserAndQuestion('test@email.com', 'question');
        self::assertTrue($userQuestionDataProvider->getAnswer());


        $formData =
            [
                'answer_1' => false,
                'answer_2' => true,
                'answer_3' => true,
                'answer_4' => false,
            ];

        $this->bridgeUserQuestion->updateAnswer($questionDataProvider, 'test@email.com', $formData);
        $userQuestionDataProvider = $this->bridgeUserQuestion->getByUserAndQuestion('test@email.com', 'question');
        self::assertFalse($userQuestionDataProvider->getAnswer());
    }
}