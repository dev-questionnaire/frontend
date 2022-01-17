<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\UserQuestion\Persistence;

use App\Components\UserQuestion\Persistence\Mapper\UserQuestionMapper;
use App\Entity\UserQuestion;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserQuestionMapperTest extends KernelTestCase
{
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->container = static::getContainer();
    }

    public function testMapperNegativ(): void
    {
        $mapper = $this->container->get(UserQuestionMapper::class);

        $currentDate = new \DateTime();

        $userQuestion = new UserQuestion();
        $userQuestion
            ->setQuestionSlug('question')
            ->setExamSlug('exam')
            ->setUser(null)
            ->setAnswer(false)
            ->setCreatedAt($currentDate)
            ->setUpdatedAt($currentDate);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("User not provided");

        $mapper->map($userQuestion);
    }
}