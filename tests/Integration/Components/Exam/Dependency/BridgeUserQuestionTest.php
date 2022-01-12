<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Exam\Dependency;

use App\Components\Exam\Dependency\BridgeUserQuestion;
use App\Components\Exam\Dependency\BridgeUserQuestionInterface;
use App\Components\UserQuestion\Business\FacadeUserQuestion;
use App\Components\UserQuestion\Persistence\EntityManager\UserQuestionEntityManager;
use App\Components\UserQuestion\Persistence\Mapper\UserQuestionMapper;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepository;
use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepositoryInterface;
use App\DataFixtures\AppFixtures;
use App\DataTransferObject\UserQuestionDataProvider;
use App\Entity\User;
use App\Entity\UserQuestion;
use App\Repository\UserRepository;
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

    public function testGetByUserAndExamIndexedByQuestionSlug(): void
    {
        $userQuestionDataProviderList = $this->bridgeUserQuestion->getByUserAndExamIndexedByQuestionSlug('user@email.com', 'exam');
        self::assertCount(3, $userQuestionDataProviderList);

        self::assertInstanceOf(UserQuestionDataProvider::class, $userQuestionDataProviderList['question_1']);

        self::assertEmpty($this->bridgeUserQuestion->getByUserAndExamIndexedByQuestionSlug('', ''));
    }
}