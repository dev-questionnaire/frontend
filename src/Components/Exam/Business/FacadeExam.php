<?php
declare(strict_types=1);

namespace App\Components\Exam\Business;

use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use App\DataTransferObject\ExamDataProvider;
use App\DataTransferObject\QuestionDataProvider;

class FacadeExam implements FacadeExamInterface
{
    public function __construct(
        private ExamRepositoryInterface $examRepository,
    )
    {
    }

    public function getBySlug(string $slug): ?ExamDataProvider
    {
        return $this->examRepository->getBySlug($slug);
    }
}