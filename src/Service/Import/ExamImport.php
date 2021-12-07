<?php
declare(strict_types=1);

namespace App\Service\Import;

use App\Components\Exam\Business\Model\ExamMapperCSV;
use App\Components\Exam\Persistence\EntityManager\ExamEntityManagerInterface;
use App\Components\Exam\Persistence\Repository\ExamRepository;
use App\Entity\Exam;
use App\GeneratedDataTransferObject\ExamDataProvider;

class ExamImport
{
    public function __construct(
        private ExamMapperCSV $examMapperCSV,
        private ExamRepository $examRepository,
        private CSVFileReader $CSVFileReader,
        private ExamEntityManagerInterface $entityManager,
    )
    {
    }

    public function import(string $filePath): array
    {
        $countImports = [0, 0];

        $examDTOList = $this->convertToDTO($filePath);

        foreach ($examDTOList as $examDTO) {
            ++$countImports[1];

            $examDTOCheck = $this->examRepository->getByName($examDTO->getName());

            if(!$examDTOCheck instanceof ExamDataProvider) {
                ++$countImports[0];

                $this->entityManager->create($examDTO);
            }
        }

        return $countImports;
    }

    private function convertToDTO(string $filePath): array
    {
        $examDTOList = [];

        $records = $this->CSVFileReader->read($filePath);

        foreach ($records as $record) {
            $examDTOList[] = $this->examMapperCSV->map($record);
        }

        return $examDTOList;
    }
}