<?php
namespace CsvMate\Csv;

interface WriterInterface
{
    /**
     * @param  array $data
     * @param  string $filePath
     * @return mixed
     */
    public function writeData(array $data, $filePath = '');

    /**
     * @param  resource $handle
     * @param  array $fields
     * @param  string $delimiter
     * @param  string $enclosure
     * @return mixed
     */
    public function writeDataRow(&$handle, array $fields = [], $delimiter = ',', $enclosure = '"');
}