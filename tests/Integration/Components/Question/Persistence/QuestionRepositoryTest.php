<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Question\Persistence;

use App\Components\Question\Persistence\Mapper\QuestionMapper;
use App\Components\Question\Persistence\Repository\QuestionRepository;
use App\Components\Question\Persistence\Repository\QuestionRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class QuestionRepositoryTest extends KernelTestCase
{
    private QuestionRepositoryInterface $questionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $parameterBagStub = new ParameterBag([
            'app_content_folder' => __DIR__ . '/../../../content'
        ]);
        $this->questionRepository = new QuestionRepository(
            new QuestionMapper(),
            $parameterBagStub
        );
    }

    public function testGetByExamAndQuestionPositiv(): void
    {
        $questionDataProviderList = $this->questionRepository->getByExamSlug('solid');

        self::assertCount(2, $questionDataProviderList);

        self::assertSame('s_in_solid', $questionDataProviderList[0]->getSlug());
        self::assertSame('What does S in SOLID mean?', $questionDataProviderList[0]->getQuestion());
        self::assertSame(["Single possibility"], $questionDataProviderList[0]->getRightQuestions());


        $answers = [
            "Single possibility",
            "Single like a pringle",
            "Solid",
            "Sexy Programming"
        ];

        self::assertSame($answers, $questionDataProviderList[0]->getAnswers());

        self::assertSame('o_in_solid', $questionDataProviderList[1]->getSlug());
        self::assertSame('What does O in SOLID mean?', $questionDataProviderList[1]->getQuestion());
        self::assertSame(["Open close"], $questionDataProviderList[1]->getRightQuestions());


        $answers = [
            "Open Relation",
            "Oral _ex",
            "Open close",
            "Opfer"
        ];

        self::assertSame($answers, $questionDataProviderList[1]->getAnswers());
    }

    public function testGetByExamAndQuestionNegativ(): void
    {
        $questionDataProviderList = $this->questionRepository->getByExamSlug('');

        self::assertEmpty($questionDataProviderList);
    }
}