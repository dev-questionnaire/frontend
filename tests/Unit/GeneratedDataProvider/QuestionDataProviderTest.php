<?php
declare(strict_types=1);

namespace App\Tests\Unit\GeneratedDataProvider;

use App\GeneratedDataTransferObject\QuestionDataProvider;
use PHPUnit\Framework\TestCase;

class QuestionDataProviderTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $questionDataProvider = new QuestionDataProvider();

        $questionDataProvider
            ->setId(1)
            ->setName('testQuestion')
            ->setAnswers(['testAnswer'])
            ->setCreatedAt('01.01.2001')
            ->setUpdatedAt('01.01.2001')
            ->setExam_id(1);

        self::assertSame(1, $questionDataProvider->getId());
        self::assertSame('testQuestion', $questionDataProvider->getName());
        self::assertSame('testAnswer', $questionDataProvider->getAnswers()[0]);
        self::assertSame('01.01.2001', $questionDataProvider->getCreatedAt());
        self::assertSame('01.01.2001', $questionDataProvider->getUpdatedAt());
        self::assertSame(1, $questionDataProvider->getExam_id());

        self::assertTrue($questionDataProvider->hasId());
        self::assertTrue($questionDataProvider->hasName());
        self::assertTrue($questionDataProvider->hasAnswers());
        self::assertTrue($questionDataProvider->hasCreatedAt());
        self::assertTrue($questionDataProvider->hasUpdatedAt());
        self::assertTrue($questionDataProvider->hasExam_id());

        $questionDataProvider
            ->unsetId()
            ->unsetName()
            ->unsetAnswers()
            ->unsetCreatedAt()
            ->unsetUpdatedAt()
            ->unsetExam_id();

        self::assertFalse($questionDataProvider->hasId());
        self::assertFalse($questionDataProvider->hasName());
        self::assertFalse($questionDataProvider->hasAnswers());
        self::assertFalse($questionDataProvider->hasCreatedAt());
        self::assertFalse($questionDataProvider->hasUpdatedAt());
        self::assertFalse($questionDataProvider->hasExam_id());
    }
}