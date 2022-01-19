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
        $path = $params->get('app_content_folder');

        if(!is_string($path)) {
            throw new \Exception("ParameterBagInterface 'app_content_folder' musst return a string");
        }

        $this->pathToFolder = $path;
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

        $finder = new Finder();

        $path = "{$this->pathToFolder}/{$examSlug}/";

        $fileList = $finder
            ->in($path)
            ->name('*.json')
            ->sortByName()
            ->files()->contains('question');

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($fileList as $file) {
            $questionDataProviderList[] = $this->questionMapper->map($file->getPathname());
        }

        return $questionDataProviderList;
    }

    public function getByExamAndQuestionSlug(string $examSlug, string $questionSlug): ?QuestionDataProvider
    {
        if (empty($examSlug) || empty($questionSlug)) {
            return null;
        }

        $finder = new Finder();

        $path = "{$this->pathToFolder}/{$examSlug}/";

        $fileList = $finder
            ->in($path)
            ->name('*.json')
            ->sortByName()
            ->files()->contains($questionSlug);

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($fileList as $file) {
            return $this->questionMapper->map($file->getPathname());
        }

        return null;
    }
}