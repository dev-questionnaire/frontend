<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Exam\Dependency;

use App\Components\Exam\Dependency\BridgeUserQuestion;
use App\Components\Exam\Dependency\BridgeUserQuestionInterface;
use App\Components\Question\Persistence\Repository\QuestionRepository;
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
use Symfony\Component\DependencyInjection\ContainerInterface;

class BridgeUserQuestionTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?BridgeUserQuestionInterface $bridgeUserQuestion;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $this->container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->bridgeUserQuestion = $this->container->get(BridgeUserQuestion::class);

        $appFixtures = $this->container->get(AppFixtures::class);
        $appFixtures->load($this->entityManager, ['test' => true]);
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
        $userQuestionDataProviderList = $this->bridgeUserQuestion->getByUserAndExamIndexedByQuestionSlug(2, 'solid');
        self::assertCount(2, $userQuestionDataProviderList);

        self::assertInstanceOf(UserQuestionDataProvider::class, $userQuestionDataProviderList['s_in_solid']);

        self::assertEmpty($this->bridgeUserQuestion->getByUserAndExamIndexedByQuestionSlug(2, ''));
    }

    public function testGetPercentAndAnswerCorrectAndUserAnswerListAdmin(): void
    {
        $userQuestion = $this->container->get(\App\Repository\UserQuestionRepository::class)->findOneBy(['questionSlug' => 'o_in_solid', 'user' => 2]);
        $userQuestion->setAnswers(null);
        $this->entityManager->flush();

        $questionDTOList = $this->container->get(QuestionRepository::class)->getByExamSlug('solid');
        $userQuestionDTOList = $this->container->get(UserQuestionRepository::class)->findByExamAndUserIndexedByQuestionSlug('solid', 2);


        $results = $this->container->get(FacadeUserQuestion::class)->getPercentAndAnswerCorrectAndUserAnswerList($questionDTOList, $userQuestionDTOList, true);

        self::assertNotEmpty($results);
    }

    public function testGetPercentAndAnswerCorrectAndUserAnswerList(): void
    {
        $questionDTOList = $this->container->get(QuestionRepository::class)->getByExamSlug('solid');
        $userQuestionDTOList = $this->container->get(UserQuestionRepository::class)->findByExamAndUserIndexedByQuestionSlug('solid', 2);

        $results = $this->container->get(FacadeUserQuestion::class)->getPercentAndAnswerCorrectAndUserAnswerList($questionDTOList, $userQuestionDTOList)['answeredCorrect'];

        self::assertTrue($results['s_in_solid']);
        self::assertFalse($results['o_in_solid']);
    }
}