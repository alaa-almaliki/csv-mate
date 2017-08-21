<?php
namespace CsvMate\Csv\Reader;

use CsvMate\Condition;
use CsvMate\CsvException;
use CsvMate\Csv\Reader;

/**
 * Class Helper
 * @package CsvMate\Csv
 * @auhtor Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Helper
{
    /** @var  Reader */
    protected $csvReader;

    /**
     * Helper constructor.
     * @param Reader $csvReader
     */
    public function __construct(Reader $csvReader)
    {
        $this->csvReader = $csvReader;
    }

    /**
     * @return Reader
     */
    public function getCsvReader()
    {
        return $this->csvReader;
    }

    /**
     * Gets the column position from csv data by a given header name
     * If position is given then that position is returned
     *
     * @param  string|int $column
     * @return int
     * @throws CsvException
     */
    public function getColumnIndex($column)
    {
        if (is_numeric($column)) {
            return $column;
        }

        if (in_array($column, $this->csvReader->getHeaders())) {
            return array_flip($this->csvReader->getHeaders())[$column];
        }

        foreach ($this->csvReader->getRenamedHeaders() as $renamedHeader) {
            if ($renamedHeader['new_name'] == $column) {
                return array_flip($this->csvReader->getHeaders())[$renamedHeader['original_name']];
            }
        }

        throw new CsvException($column . ' can not be found as csv header.');
    }

    /**
     * Filters the csv data returned
     *
     * @param  array $data
     * @return bool
     */
    public function canAddToResult(array $data)
    {
        $result = true;
        foreach ($this->csvReader->getFilterableColumns() as $columnIndex => $conditions) {
            foreach ($conditions as $condition) {
                $result &= Condition::isPassed($data[$columnIndex], $condition);
            }
        }

        return $result;
    }

    /**
     * Refine the row data, so only selected columns are returned
     *
     * @param  array $data
     * @return array
     */
    public function refineData(array $data)
    {
        $hasSelectedColumns = $this->csvReader->hasSelectedColumns();

        return $hasSelectedColumns ?
            array_intersect_key($data, $this->csvReader->getSelectedColumns()) :
            $data;
    }

    /**
     * Gets a single column data from csv data, parameter can be header name or column index
     *
     * @param  int|string $column
     * @return array
     */
    public function getColumn($column)
    {
        $columnData = [];
        $columnIndex = $this->getColumnIndex($column);
        foreach ($this->csvReader->getData() as $item) {
            $columnData[] = $item[$columnIndex];
        }

        return $columnData;
    }
}