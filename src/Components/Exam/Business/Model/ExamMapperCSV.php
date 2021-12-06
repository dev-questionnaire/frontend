<?php
declare(strict_types=1);

namespace App\Components\Exam\Business\Model;

use App\GeneratedDataTransferObject\ExamDataProvider;

class ExamMapperCSV implements ExamMapperCSVInterface
{
    public function map(array $examCSV): ExamDataProvider
    {
        $examDTO = new ExamDataProvider();

        $time = (new \DateTime())->format('d.m.Y');

        if (isset($examCSV['createdAt']) && $examCSV['createdAt'] !== '' && $examCSV['createdAt'] !== '0' && $examCSV['createdAt'] !== 'null') {
            $time = $examCSV['createdAt'];
        }

        $examDTO->setName($examCSV['name'])
            ->setCreatedAt($time)
            ->setUpdatedAt($time);

        return $examDTO;
    }
}