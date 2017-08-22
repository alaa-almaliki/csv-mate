<?php
namespace CsvMate\Csv;

use CsvMate\AbstractCsv;
use CsvMate\CsvException;

/**
 * Class Writer
 * @package CsvMate\Csv
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Writer extends AbstractCsv implements WriterInterface
{
    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Saves an array of data into csv file
     * @param  array $data
     * @param  string $filePath
     * @return $this
     */
    public function writeData(array $data, $filePath = '')
    {
        $filePath = $this->resolveFilePath($filePath);
        $fh = fopen($filePath, 'w');

        foreach ($data as $dataRow) {
            $this->writeDataRow($fh, $dataRow, $this->getDelimiter(), $this->getEnclosure());
        }
        fclose($fh);
        return $this;
    }

    /**
     * copied from http://php.net
     * @link http://php.net/manual/en/function.fputcsv.php#84783
     *
     * Saves an array as a single row into a csv file
     *
     * @param  resource $handle
     * @param  array $fields
     * @param  string $delimiter
     * @param  string $enclosure
     * @return bool|int
     */
    public function writeDataRow(&$handle, array $fields = [], $delimiter = ',', $enclosure = '"') {

        $this->validateHandle($handle);
        $delimiter = $this->resolveDelimiter($delimiter);
        $enclosure = $this->resolveEnclosure($enclosure);
        $i = 0;
        $csvLine = '';
        $escapeChar = '\\';
        $fieldCount = count($fields);
        $isEnclosureQuote = in_array($enclosure, ['"',"'"]);
        reset($fields);

        foreach ($fields as $field) {
            if (is_string($field) && (
                    strpos($field, $delimiter)  !== false ||
                    strpos($field, $enclosure)  !== false ||
                    strpos($field, $escapeChar) !== false ||
                    strpos($field, "\n")        !== false ||
                    strpos($field, "\r")        !== false ||
                    strpos($field, "\t")        !== false ||
                    strpos($field, ' ')         !== false)
            ) {

                $fieldLength = strlen($field);
                $escaped = 0;

                $csvLine .= $enclosure;
                for ($ch = 0; $ch < $fieldLength; $ch++) {
                    if ($field[$ch] == $escapeChar && $field[$ch+1] == $enclosure && $isEnclosureQuote) {
                        continue;
                    } else if ($field[$ch] == $escapeChar) {
                        $escaped = 1;
                    } else if (!$escaped && $field[$ch] == $enclosure) {
                        $csvLine .= $enclosure;
                    } else {
                        $escaped = 0;
                    }
                    $csvLine .= $field[$ch];
                }
                $csvLine .= $enclosure;
            } else {
                $csvLine .= $field;
            }

            if ($i++ != $fieldCount) {
                $csvLine .= $delimiter;
            }
        }

        $csvLine .= "\n";

        return fwrite($handle, $csvLine);
    }

    /**
     * @param  resource $handle
     * @return $this
     * @throws CsvException
     */
    protected function validateHandle($handle)
    {
        if (!is_resource($handle)) {
            throw new CsvException('writeDataRow() expects parameter 1 to be resource, ' . gettype($handle) . ' given');
        }

        return $this;
    }

    /**
     * @param  string $delimiter
     * @return string
     * @throws CsvException
     */
    protected function resolveDelimiter($delimiter)
    {
        if ($delimiter != null) {
            if( strlen($delimiter) < 1 || strlen($delimiter) > 1) {
                throw new CsvException('delimiter must be a single character');
            }
            $delimiter = $delimiter[0];
        }

        return $delimiter;
    }

    /**
     * @param  string $enclosure
     * @return string
     * @throws CsvException
     */
    public function resolveEnclosure($enclosure)
    {
        if( $enclosure != null ) {
            if( strlen($enclosure) < 1 || strlen($enclosure) > 1) {
                throw new CsvException('enclosure must be a character');
            }
            $enclosure = $enclosure[0];
        }

        return $enclosure;
    }

    /**
     * @param  string $filePath
     * @return string
     */
    protected function resolveFilePath($filePath)
    {
        if (!$filePath) {
            $filePath = $this->getFilePath();
        }

        return $filePath;
    }
}