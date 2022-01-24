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
        $path = $params->get('app_content_folder');

        if(!is_string($path)) {
            throw new \Exception("ParameterBagInterface 'app_content_folder' musst return a string");
        }

        $this->pathToFolder = $path;
    }

    public function getBySlug(string $slug):  ?ExamDataProvider
    {
        if (empty($slug)) {
            return null;
        }

        $finder = new Finder();

        $path = "{$this->pathToFolder}/*/";

        $fileList = $finder
            ->in($path)
            ->name('index.json')
            ->sortByName()
            ->files()->contains(['slug' => $slug]);

        /** @var null|ExamDataProvider $examDataProvider */
        $examDataProvider = null;

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($fileList as $file) {
            $examDataProvider = $this->examMapper->map($file->getPathname());
        }

        return $examDataProvider;
    }

    /**
     * @return ExamDataProvider[]
     * @throws \JsonException
     */
    public function getAll(): array
    {
        $examDataProviderList = [];

        $finder = new Finder();

        $path = "{$this->pathToFolder}/*/";

        $fileList = $finder
            ->in($path)
            ->name('index.json')
            ->sortByName();

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($fileList as $file) {
            $examDataProviderList[] = $this->examMapper->map($file->getPathname());
        }

        return $examDataProviderList;
    }
}