<?php
declare(strict_types=1);

namespace App\Components\Question\Persistence\Repository;

use App\Components\Question\Persistence\Mapper\QuestionMapper;
use App\DataTransferObject\QuestionDataProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

class QuestionRepository implements QuestionRepositoryInterface
{
    private string $pathToFolder;

    public function __construct(
        private QuestionMapper $questionMapper,
        ParameterBagInterface  $params
    )
    {
        $this->pathToFolder = $params->get('app_content_folder');
    }

    /**
     * @return QuestionDataProvider[]
     */
    public function getByExamSlug(string $examSlug): array
    {
        $questionDataProviderList = [];

        if (empty($examSlug)) {
            return [];
        }

        $fileList = (new Finder())
            ->in($this->pathToFolder . '/' . $examSlug . '/')
            ->name('*.json')
            ->sortByName()
            ->files()->contains('question');

        foreach ($fileList as $file) {
            $questionDataProviderList[] = $this->questionMapper->map($file->getPathname());
        }

        return $questionDataProviderList;
    }
}