<?php
declare(strict_types=1);

namespace App\Components\Exam\Business;

use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\DataTransferObject\ExamDataProvider;

class FacadeExam implements FacadeExamInterface
{
    public function __construct(
        private ExamRepositoryInterface $examRepository,
    )
    {
    }

    public function getByName(string $exam): ?ExamDataProvider
    {
        return $this->examRepository->getByName($exam);
    }
}