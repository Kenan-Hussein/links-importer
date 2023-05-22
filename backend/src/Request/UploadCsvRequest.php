<?php


namespace App\Request;


class UploadCsvRequest
{
    private mixed $csvFile;

    /**
     * @return mixed
     */
    public function getCsvFile(): mixed
    {
        return $this->csvFile;
    }

    /**
     * @param mixed $csvFile
     */
    public function setCsvFile(mixed $csvFile): void
    {
        $this->csvFile = $csvFile;
    }

}