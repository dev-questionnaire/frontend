<?php
declare(strict_types=1);

namespace App\Components\Answer\Persistence\Repository;

use App\Components\Answer\Persistence\Mapper\AnswerMapper;
use App\DataTransferObject\AnswerDataProvider;
use App\Repository\QuestionRepository;

class AnswerRepository implements AnswerRepositoryInterface
{
    public function __construct(
        private \App\Repository\AnswerRepository $answerRepository,
        private QuestionRepository $questionRepository,
        private AnswerMapper $answerMapper,
    )
    {
    }

    /**
     * @return AnswerDataProvider[]
     */
    public function getByQuestion(int $questionId): array
    {
        $answerDataProviderList = [];

        $question = $this->questionRepository->find($questionId);
        $answerList = $this->answerRepository->findBy(['question' => $question]);

        foreach ($answerList as $answer) {
            $answerDataProviderList[] = $this->answerMapper->map($answer);
        }

        return $answerDataProviderList;
    }
}