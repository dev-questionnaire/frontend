<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Question\Dependency;

use App\Components\Question\Dependency\BridgeUserQuestion;
use App\Components\Question\Dependency\BridgeUserQuestionInterface;
use App\DataFixtures\AppFixtures;
use App\DataTransferObject\QuestionDataProvider;
use App\DataTransferObject\UserQuestionDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BridgeUserQuestionTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?BridgeUserQuestionInterface $bridgeUserQuestion;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->bridgeUserQuestion = $container->get(BridgeUserQuestion::class);

        $appFixtures = $container->get(AppFixtures::class);
        $appFixtures->load($this->entityManager);
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

        $this->bridgeUserQuestion = null;
        $this->entityManager = null;
    }

    public function testCreateDeleteGet(): void
    {
        $this->bridgeUserQuestion->create('slug', 'exam', 'user@valantic.com');

        $userQuestionDataProvider = $this->bridgeUserQuestion->getByUserAndQuestion('user@valantic.com', 'slug');

        self::assertInstanceOf(UserQuestionDataProvider::class, $userQuestionDataProvider);
        self::assertNull($userQuestionDataProvider->getAnswer());


        $this->bridgeUserQuestion->delete($userQuestionDataProvider->getId());
        self::assertNull($this->bridgeUserQuestion->getByUserAndQuestion('user@valantic.com', 'slug'));
    }

    public function testUpdate(): void
    {
        $this->bridgeUserQuestion->create('question', 'exam', 'user@valantic.com');

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

        $this->bridgeUserQuestion->updateAnswer($questionDataProvider, 'user@valantic.com', $formData);
        $userQuestionDataProvider = $this->bridgeUserQuestion->getByUserAndQuestion('user@valantic.com', 'question');
        self::assertTrue($userQuestionDataProvider->getAnswer());


        $formData =
            [
                'answer_1' => false,
                'answer_2' => true,
                'answer_3' => true,
                'answer_4' => false,
            ];

        $this->bridgeUserQuestion->updateAnswer($questionDataProvider, 'user@valantic.com', $formData);
        $userQuestionDataProvider = $this->bridgeUserQuestion->getByUserAndQuestion('user@valantic.com', 'question');
        self::assertFalse($userQuestionDataProvider->getAnswer());
    }
}