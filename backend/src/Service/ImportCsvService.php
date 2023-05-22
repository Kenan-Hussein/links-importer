<?php

namespace App\Service;

use League\Csv\Reader;

class ImportCsvService
{
    /**
     * Import csv file using league
     *
     * @return array
     *
     * @throws \League\Csv\Exception
     */
    public function import(mixed $csvFile): array
    {
        $stream = fopen($csvFile->getPathname(), 'r');

        $csv = Reader::createFromStream($stream, 'r+');
        $csv->setHeaderOffset(0);

        //let us get rid of TabularDataReader
        $rows = iterator_to_array($csv);

        //clean
        unset($csv);

        return $rows;
    }
}