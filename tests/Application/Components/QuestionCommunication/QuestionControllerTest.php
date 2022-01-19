<?php
declare(strict_types=1);

namespace App\Tests\Application\Components\QuestionCommunication;

use App\Components\UserQuestion\Persistence\Repository\UserQuestionRepository;
use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Entity\UserQuestion;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class QuestionControllerTest extends WebTestCase
{
    private ?EntityManager $entityManager;
    private ContainerInterface $container;
    private KernelBrowser $client;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->container = static::getContainer();

        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');

        $appFixtures = $this->container->get(AppFixtures::class);
        $appFixtures->load($this->entityManager);

        $repository = $this->container->get(UserRepository::class);

        $this->user = $repository->findOneBy(['email' => 'user@valantic.com']);

        $this->client->loginUser($this->user);
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

    public function testAppQuestion(): void
    {
        $this->client->request('GET', '/exam/solid/question');

        self::assertResponseIsSuccessful();
    }

    public function testAppQuestionRedirectToResult(): void
    {
        $userQuestion_1 = new UserQuestion();
        $userQuestion_2 = new UserQuestion();

        $userQuestion_1
            ->setExamSlug('solid')
            ->setQuestionSlug('s_in_solid')
            ->setUser($this->user)
            ->setAnswers(["Solid" => false, "Sexy_Programming" => false, "Single_possibility" => true, "Single_like_a_pringle" => false]);

        $userQuestion_2
            ->setExamSlug('solid')
            ->setQuestionSlug('o_in_solid')
            ->setUser($this->user)
            ->setAnswers(['Open_relation' => true, 'Oral__ex' => false, 'Open_close' => false, 'Opfer' => false]);

        $this->entityManager->persist($userQuestion_1);
        $this->entityManager->persist($userQuestion_2);
        $this->entityManager->flush();

        $this->client->request('GET', '/exam/solid/question');

        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());
    }

    public function testAppQuestionSubmit(): void
    {
        //$csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('_token')->getValue();
        $crawler = $this->client->request(
            'POST',
            '/exam/solid/question',
            [
                'form' => [
                    'Single_possibility' => true,
                ],
            ]
        );

        $userQuestionRepo = $this->container->get(UserQuestionRepository::class);
        $userQuestion = $userQuestionRepo->findOneByQuestionAndUser('s_in_solid', 2);

        $expected = [
            'Single_possibility' => true,
            'Single_like_a_pringle' => false,
            'Solid' => false,
            'Sexy_Programming' => false,
        ];

        self::assertSame($expected, $userQuestion->getAnswers());


        self::assertInstanceOf(RedirectResponse::class, $this->client->getResponse());


    }
}