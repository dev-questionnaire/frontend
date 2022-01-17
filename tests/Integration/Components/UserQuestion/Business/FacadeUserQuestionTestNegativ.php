<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\UserQuestion\Business;

use App\Components\Question\Persistence\Repository\QuestionRepository;
use App\Components\UserQuestion\Business\FacadeUserQuestion;
use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FacadeUserQuestionTestNegativ extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $this->container = static::getContainer();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
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
    }

    public function testUpdateException(): void
    {
        $questionDTO = $this->container->get(QuestionRepository::class)->getByExamSlug('solid')[0];

        $formAnswer = [
            "Single possibility" => false,
            "Single like a pringle" => false,
            "Solid" => false,
            "Sexy Programming" => false
        ];

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("UserQuestion no found");

        $this->container->get(FacadeUserQuestion::class)->updateAnswer($questionDTO, 0, $formAnswer);
    }
}