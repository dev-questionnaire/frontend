<?php
declare(strict_types=1);

namespace App\Components\Question\Dependency;

use App\Components\Exam\Business\FacadeExamInterface;
use App\DataTransferObject\ExamDataProvider;

class BridgeExam implements BridgeExamInterface
{
    public function __construct(
        private FacadeExamInterface $facadeExam,
    )
    {
    }

    public function getByName(string $exam): ?ExamDataProvider
    {
        return $this->facadeExam->getByName($exam);
    }
}