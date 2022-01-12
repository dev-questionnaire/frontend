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
        $container = static::getContainer();

        $this->examRepository = $container->get(ExamRepository::class);
    }

    public function testGetAll(): void
    {
        $examDataProviderList = $this->examRepository->getAll();

        self::assertCount(4, $examDataProviderList);
        self::assertSame('Haruns geiles Quiz', $examDataProviderList[0]->getName());
        self::assertSame('OOP', $examDataProviderList[1]->getName());
        self::assertSame('SOLID', $examDataProviderList[2]->getName());
        self::assertSame('Testing', $examDataProviderList[3]->getName());

        self::assertSame('harun', $examDataProviderList[0]->getSlug());
        self::assertSame('oop', $examDataProviderList[1]->getSlug());
        self::assertSame('solid', $examDataProviderList[2]->getSlug());
        self::assertSame('testing', $examDataProviderList[3]->getSlug());
    }

    public function testGetByNamePositiv(): void
    {
        $examDataProvider = $this->examRepository->getBySlug('oop');

        self::assertSame('OOP', $examDataProvider->getName());
    }

    public function testGetByNameNegativ(): void
    {
        $result = $this->examRepository->getBySlug('does not exist');

        self::assertNull($result);

        $result = $this->examRepository->getBySlug('');

        self::assertNull($result);
    }
}