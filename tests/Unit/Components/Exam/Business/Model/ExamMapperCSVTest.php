<?php
declare(strict_types=1);

namespace App\Tests\Unit\Components\Exam\Business\Model;

use App\Components\Exam\Business\Model\ExamMapperCSV;
use PHPUnit\Framework\TestCase;

class ExamMapperCSVTest extends TestCase
{
    public function testMap(): void
    {
        $examMapperCSV = new ExamMapperCSV();
        $currentDate = (new \DateTime())->format('d.m.Y');

        $examCSV = [
            'name' => 'testExam',
        ];

        $examDataProvider = $examMapperCSV->map($examCSV);

        self::assertSame('testExam', $examDataProvider->getName());
        self::assertSame($currentDate, $examDataProvider->getCreatedAt());
        self::assertSame($currentDate, $examDataProvider->getUpdatedAt());

        $examCSV = [
            'name' => 'testExam',
            'createdAt' => '07.08.2021',
        ];

        $examDataProvider = $examMapperCSV->map($examCSV);

        self::assertSame('testExam', $examDataProvider->getName());
        self::assertSame('07.08.2021', $examDataProvider->getCreatedAt());
        self::assertSame('07.08.2021', $examDataProvider->getUpdatedAt());

        $examCSV = [
            'name' => 'testExam',
            'createdAt' => '0',
        ];

        $examDataProvider = $examMapperCSV->map($examCSV);

        self::assertSame('testExam', $examDataProvider->getName());
        self::assertSame($currentDate, $examDataProvider->getCreatedAt());
        self::assertSame($currentDate, $examDataProvider->getUpdatedAt());

        $examCSV = [
            'name' => 'testExam',
            'createdAt' => '',
        ];

        $examDataProvider = $examMapperCSV->map($examCSV);

        self::assertSame('testExam', $examDataProvider->getName());
        self::assertSame($currentDate, $examDataProvider->getCreatedAt());
        self::assertSame($currentDate, $examDataProvider->getUpdatedAt());

        $examCSV = [
            'name' => 'testExam',
            'createdAt' => 'null',
        ];

        $examDataProvider = $examMapperCSV->map($examCSV);

        self::assertSame('testExam', $examDataProvider->getName());
        self::assertSame($currentDate, $examDataProvider->getCreatedAt());
        self::assertSame($currentDate, $examDataProvider->getUpdatedAt());
    }
}