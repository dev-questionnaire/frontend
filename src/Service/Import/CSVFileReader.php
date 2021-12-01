<?php
declare(strict_types=1);

namespace App\Service\Import;

use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\TabularDataReader;

class CSVFileReader
{
    public function read(string $filePath): TabularDataReader
    {
        $csv = Reader::createFromPath($filePath, 'r');

        $csv->setDelimiter(';')
            ->setHeaderOffset(0);

        return (new Statement())->process($csv);
    }
}