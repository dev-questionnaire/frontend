<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Exam\Business;

use App\Components\Exam\Business\FacadeExam;
use App\Components\Exam\Business\FacadeExamInterface;
use App\Components\Exam\Persistence\Mapper\ExamMapper;
use App\Components\Exam\Persistence\Repository\ExamRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ExamFacadeTest extends KernelTestCase
{
    private FacadeExamInterface $facadeExam;

    protected function setUp(): void
    {
        parent::setUp();

        $parameterBagStub = new ParameterBag([
            'app_content_folder' => __DIR__ . '/content'
        ]);

        $this->facadeExam = new FacadeExam(
            new ExamRepository(
                new ExamMapper(),
                $parameterBagStub
            )
        );
    }

    public function testGetByName(): void
    {
        $examDataProvider = $this->facadeExam->getByName('SOLID');

        self::assertSame('SOLID', $examDataProvider->getName());
    }
}