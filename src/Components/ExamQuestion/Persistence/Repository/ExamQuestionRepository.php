<?php
declare(strict_types=1);

namespace App\Components\ExamQuestion\Persistence\Repository;

use App\Components\ExamQuestion\Persistence\Mapper\ExamQuestionMapper;
use App\DataTransferObject\ExamQuestionDataProvider;
use App\Repository\ExamRepository;

class ExamQuestionRepository implements ExamQuestionRepositoryInterface
{
    public function __construct(
        private \App\Repository\AnswerRepository $examQuestionRepository,
        private ExamRepository                   $examRepository,
        private ExamQuestionMapper               $mapper,
    )
    {
    }

    /**
     * @return ExamQuestionDataProvider[]
     */
    public function getByExamId(int $examId): array
    {
        $examQuestionDataProviderList = [];

        $exam = $this->examRepository->find($examId);

        $examQuestionList = $this->examQuestionRepository->findBy(['exam' => $exam]);

        foreach ($examQuestionList as $examQuestion) {
            $examQuestionDataProviderList[] = $this->mapper->map($examQuestion);
        }

        return $examQuestionDataProviderList;
    }
}