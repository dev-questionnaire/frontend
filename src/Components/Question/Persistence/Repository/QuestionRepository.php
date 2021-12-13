<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\Repository;

use App\Components\Question\Persistence\Mapper\QuestionMapper;
use App\DataTransferObject\QuestionDataProvider;
use App\Repository\ExamRepository;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function __construct(
        private \App\Repository\QuestionRepository $questionRepository,
        private ExamRepository $examRepository,
        private QuestionMapper $mapper,
    )
    {
    }

    /**
     * @return QuestionDataProvider[]
     */
    public function getByExamId(int $examId): array
    {
        $questionDataProviderList = [];

        $exam = $this->examRepository->find($examId);

        $questionList = $this->questionRepository->findBy(['exam' => $exam]);

        foreach ($questionList as $question) {
            $questionDataProviderList[] = $this->mapper->map($question);
        }

        return $questionDataProviderList;
    }
}