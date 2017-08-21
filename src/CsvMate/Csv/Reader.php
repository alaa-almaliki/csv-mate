<?php
namespace CsvMate\Csv;

use CsvMate\AbstractCsv;
use CsvMate\Csv\Reader\Helper;

/**
 * Class Reader
 * @package CsvMate\Csv
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Reader extends AbstractCsv implements ReaderInterface
{
    /** @var  Helper */
    protected $helper;
    /** @var  array  */
    protected $data                 = [];
    /** @var  array  */
    protected $selectedColumns      = [];
    /** @var  array  */
    protected $filterableColumns    = [];
    /** @var  array  */
    protected $renamedHeaders       = [];

    /**
     * Reader constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->helper = new Helper($this);
    }

    /**
     * Gets the csv data
     *
     * @param  bool $useCache
     * @param  bool $assoc
     * @return array
     */
    public function getData($useCache = true, $assoc = false)
    {
        if ($useCache && !empty($this->data)) {
            return $this->data;
        }

        $this->populate($assoc);
        return $this->data;
    }

    /**
     * Gets the csv data, each row is associated with header as key => value pairs
     *
     * @param  bool $useCache
     * @return array
     */
    public function getAssocData($useCache = true)
    {
        return $this->getData($useCache, true);
    }

    /**
     * Gets specified columns data fromm csv data
     *
     * @param  array $columns
     * @return array
     */
    public function getColumns(array $columns)
    {
        $columnsData = [];
        foreach ($columns as $column) {
            $columnsData[] = $this->getColumn($column);
        }

        return $columnsData;
    }

    /**
     * Gets a single column data from csv data, parameter can be header name or column index
     *
     * @param  int|string $column
     * @return array
     */
    public function getColumn($column)
    {
        return $this->helper->getColumn($column);
    }

    /**
     * Populates the csv data into php array
     * if flag assoc is true, it will return associate array with headers names
     *
     * @param  bool $assoc
     * @return $this
     */
    protected function populate($assoc = false)
    {
        $this->validate();
        $data = [];
        $rowIndex = 0;
        $fh = fopen($this->filePath, 'r');

        while ($rowData = fgetcsv($fh, $this->lineLength, $this->delimiter, $this->enclosure)) {
            if ($rowIndex === 0 && $this->hasHeaders()) {
                $data[] = $this->getSelectedColumns();
                $rowIndex++;
                continue;
            }

            if (!$this->helper->canAddToResult($rowData)) {
                continue;
            }

            $rowIndex++;
            $refined = $this->helper->refineData($rowData);
            if ($assoc) {
                $refined = array_combine($this->getSelectedColumns(), $refined);
            }

            $data[] = $refined;
        }
        fclose($fh);
        $this->data =  $data;
        ksort($this->data);

        return $this;
    }

    /**
     * Adds a column to be selected, so only selected columns are returned
     *
     * @param  string $columnName
     * @return $this
     */
    public function selectColumn($columnName)
    {
        $headers = $this->getHeaders();
        if ($this->hasHeaders()) {
            if (in_array($columnName, $headers)) {
                $this->selectedColumns[array_flip($headers)[$columnName]] = $columnName;
            } else {
                foreach ($this->getRenamedHeaders() as $renamedHeader) {
                    if ($renamedHeader['new_name'] == $columnName) {
                        $idx = array_flip($headers)[$renamedHeader['original_name']];
                        $this->selectedColumns[$idx] = $renamedHeader['original_name'];
                        break;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Selects multi columns to be returned
     *
     * @param array $columnNames
     * @return $this
     */
    public function selectColumns(array $columnNames)
    {
        foreach ($columnNames as $columnName) {
            $this->selectColumn($columnName);
        }

        return $this;
    }

    /**
     * Checks if selected columns are present
     * @return bool
     */
    public function hasSelectedColumns()
    {
        return count($this->selectedColumns) > 0;
    }

    /**
     * @return array
     */
    public function getSelectedColumns()
    {
        if (empty($this->selectedColumns)) {
            $this->selectedColumns = $this->getHeaders();
        }

        $this->renameCsvHeaders();
        ksort($this->selectedColumns);
        return $this->selectedColumns;
    }

    /**
     * @param  string $columnName
     * @return $this
     */
    public function addSelectedColumn($columnName)
    {
        $headers = $this->getHeaders();
        $this->selectedColumns[array_flip($headers)[$columnName]] = $columnName;
        return $this;
    }

    /**
     * Renames csv header name by a given custom name
     *
     * @param  string $name
     * @param  string $newName
     * @return $this
     */
    public function renameHeader($name, $newName)
    {
        $this->renamedHeaders[$this->helper->getColumnIndex($name)] = [
            'original_name' => $name,
            'new_name' => $newName
        ];

        return $this;
    }

    /**
     * Resets the csv data
     *
     * @return $this
     */
    public function reset()
    {
        $this->data = [];
        return $this;
    }

    /**
     * Returns the csv headers
     * @return array
     */
    public function getHeaders()
    {
        if (empty($this->headers)) {
            $fh = fopen($this->filePath, 'r');
            $this->headers = fgetcsv($fh, $this->lineLength, $this->delimiter, $this->enclosure);
            fclose($fh);
        }
        return $this->headers;
    }

    /**
     * Adds conditions to csv data returned
     *
     * @param  string $column
     * @param  array $condition
     * @return $this
     */
    public function where($column, array $condition)
    {
        $conditions = [];
        $columnIndex = $this->helper->getColumnIndex($column);
        if (array_key_exists($columnIndex, $this->filterableColumns)) {
            $conditions = $this->filterableColumns[$columnIndex];
        }
        $conditions[] = $condition;
        $this->filterableColumns[$columnIndex] = $conditions;

        return $this;
    }

    /**
     * @return array
     */
    public function getFilterableColumns()
    {
        return $this->filterableColumns;
    }

    /**
     * Renames csv header names by a given custom names
     *
     * @param  array $columns
     * @return $this
     */
    public function renameHeaders(array $columns)
    {
        foreach ($columns as $name => $newName) {
            $this->renameHeader($name, $newName);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRenamedHeaders()
    {
        return $this->renamedHeaders;
    }

    /**
     * @return $this
     */
    protected function renameCsvHeaders()
    {
        if (!empty($this->getRenamedHeaders())) {
            foreach ($this->getRenamedHeaders() as $originalHeaderIndex => $header) {
                if (in_array($header['original_name'], $this->selectedColumns)) {
                    $this->selectedColumns[$originalHeaderIndex] = $header['new_name'];
                }
            }
        }

        return $this;
    }
}