<?php
declare(strict_types=1);

namespace App\Components\Exam\Persistence\Repository;

use App\Components\Exam\Persistence\Mapper\ExamMapper;
use App\DataTransferObject\ExamDataProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

class ExamRepository implements ExamRepositoryInterface
{
    private string $pathToFolder;

    public function __construct(
        private ExamMapper $examMapper,
        ParameterBagInterface $params
    )
    {
        $this->pathToFolder = $params->get('app_content_folder');
    }

    public function getBySlug(string $slug):  ?ExamDataProvider
    {
        $examDataProvider = null;

        if (empty($slug)) {
            return null;
        }

        $fileList = (new Finder())
            ->in($this->pathToFolder . '/*/')
            ->name('index.json')
            ->sortByName()
            ->files()->contains(['slug' => $slug]);

        foreach ($fileList as $file) {
            $examDataProvider = $this->examMapper->map($file->getPathname());
        }

        return $examDataProvider;
    }

    /**
     * @return ExamDataProvider[]
     */
    public function getAll(): array
    {
        $examDataProviderList = [];

        $fileList = (new Finder())
            ->in($this->pathToFolder . '/*/')
            ->name('index.json')
            ->sortByName();

        foreach ($fileList as $file) {
            $examDataProviderList[] = $this->examMapper->map($file->getPathname());
        }

        return $examDataProviderList;
    }
}