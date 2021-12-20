<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Exam\Dependency;

use App\Components\Exam\Dependency\BridgeQuestion;
use App\Components\Exam\Dependency\BridgeQuestionInterface;
use App\Components\Question\Business\FacadeQuestion;
use App\Components\Question\Persistence\Mapper\QuestionMapper;
use App\Components\Question\Persistence\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class BridgeQuestionTest extends KernelTestCase
{
    private BridgeQuestionInterface $bridgeQuestion;

    protected function setUp(): void
    {
        parent::setUp();

        $parameterBagStub = new ParameterBag([
            'app_content_folder' => __DIR__ . '/../../../content'
        ]);

        $this->bridgeQuestion = new BridgeQuestion(
            new FacadeQuestion(
                new QuestionRepository(
                    new QuestionMapper(),
                    $parameterBagStub
                )
            )
        );
    }

    public function testGetByExam(): void
    {
        $questionList = $this->bridgeQuestion->getByExamSlug('solid');
        self::assertCount(2, $questionList);

        $questionList = $this->bridgeQuestion->getByExamSlug('');
        self::assertEmpty($questionList);
    }
}