<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Question\Dependency;

use App\Components\Exam\Business\FacadeExam;
use App\Components\Exam\Persistence\Mapper\ExamMapper;
use App\Components\Exam\Persistence\Repository\ExamRepository;
use App\Components\Question\Dependency\BridgeExam;
use App\Components\Question\Dependency\BridgeExamInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class BridgeExamTest extends KernelTestCase
{
    protected BridgeExamInterface $bridgeExam;

    protected function setUp(): void
    {
        parent::setUp();

        $parameterBagStub = new ParameterBag([
            'app_content_folder' => __DIR__ . '/../../../content'
        ]);

        $this->bridgeExam = new BridgeExam(
            new FacadeExam(
                new ExamRepository(
                    new ExamMapper(),
                    $parameterBagStub
                )
            )
        );
    }

    public function testGetBySlug(): void
    {
        $examDataProvider = $this->bridgeExam->getBySlug('oop');
        self::assertSame('OOP', $examDataProvider->getName());

        $examDataProvider = $this->bridgeExam->getBySlug('');
        self::assertEmpty($examDataProvider);
    }
}