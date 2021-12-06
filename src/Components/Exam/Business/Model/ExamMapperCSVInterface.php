<?php
declare(strict_types=1);

namespace App\Components\Exam\Business\Model;

use App\GeneratedDataTransferObject\ExamDataProvider;

interface ExamMapperCSVInterface
{
    public function map(array $examCSV): ExamDataProvider;
}