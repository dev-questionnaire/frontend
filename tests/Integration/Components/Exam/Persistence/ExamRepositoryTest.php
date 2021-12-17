<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Exam\Persistence;

use App\Components\Exam\Persistence\Mapper\ExamMapper;
use App\Components\Exam\Persistence\Repository\ExamRepository;
use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ExamRepositoryTest extends KernelTestCase
{
    private ExamRepositoryInterface $examRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $parameterBagStub = new ParameterBag([
            'app_content_folder' => __DIR__ . '/content'
        ]);
        $this->examRepository = new ExamRepository(
            new ExamMapper(),
            $parameterBagStub
        );
    }

    public function testGetAll(): void
    {
        $examDataProviderList = $this->examRepository->getAll();

        self::assertCount(2, $examDataProviderList);
        self::assertSame('OOP', $examDataProviderList[0]->getName());
        self::assertSame('SOLID', $examDataProviderList[1]->getName());
    }

    public function testGetByNamePositiv(): void
    {
        $examDataProvider = $this->examRepository->getByName('OOP');

        self::assertSame('OOP', $examDataProvider->getName());
    }

    public function testGetByNameNegativ(): void
    {
        $result = $this->examRepository->getByName('Test');

        self::assertNull($result);

        $result = $this->examRepository->getByName('');

        self::assertNull($result);
    }
}