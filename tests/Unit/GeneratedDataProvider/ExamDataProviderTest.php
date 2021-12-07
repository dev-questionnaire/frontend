<?php
declare(strict_types=1);

namespace App\Tests\Unit\GeneratedDataProvider;

use App\GeneratedDataTransferObject\ExamDataProvider;
use PHPUnit\Framework\TestCase;

class ExamDataProviderTest extends TestCase
{
    public function testGetAndSet(): void
    {
        $examDataProvider = new ExamDataProvider();

        $examDataProvider
            ->setId(1)
            ->setName('testExam')
            ->setCreatedAt('01.01.2001')
            ->setUpdatedAt('01.01.2001');

        self::assertSame(1, $examDataProvider->getId());
        self::assertSame('testExam', $examDataProvider->getName());
        self::assertSame('01.01.2001', $examDataProvider->getCreatedAt());
        self::assertSame('01.01.2001', $examDataProvider->getUpdatedAt());

        self::assertTrue($examDataProvider->hasId());
        self::assertTrue($examDataProvider->hasName());
        self::assertTrue($examDataProvider->hasCreatedAt());
        self::assertTrue($examDataProvider->hasUpdatedAt());

        $examDataProvider
            ->unsetId()
            ->unsetName()
            ->unsetCreatedAt()
            ->unsetUpdatedAt();

        self::assertFalse($examDataProvider->hasId());
        self::assertFalse($examDataProvider->hasName());
        self::assertFalse($examDataProvider->hasCreatedAt());
        self::assertFalse($examDataProvider->hasUpdatedAt());
    }
}