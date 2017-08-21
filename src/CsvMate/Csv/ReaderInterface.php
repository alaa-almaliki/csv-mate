<?php
namespace CsvMate\Csv;

/**
 * Interface ReaderInterface
 * @package CsvMate\Csv
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
interface ReaderInterface
{
    /**
     * @param  bool $useCache
     * @param  bool $assoc
     * @return mixed
     */
    public function getData($useCache = true, $assoc = false);

    /**
     * @param  bool $useCache
     * @return array
     */
    public function getAssocData($useCache = true);

    /**
     * @return mixed
     */
    public function reset();

    /**
     * @param  string|int $column
     * @return mixed
     */
    public function selectColumn($column);

    /**
     * @param  array $columnNames
     * @return mixed
     */
    public function selectColumns(array $columnNames);

    /**
     * @return bool
     */
    public function hasSelectedColumns();

    /**
     * @return array
     */
    public function getSelectedColumns();

    /**
     * @param  string|int $column
     * @return mixed
     */
    public function getColumn($column);

    /**
     * @param array $columns
     * @return mixed
     */
    public function getColumns(array $columns);

    /**
     * @param  string $column
     * @param  array $condition
     * @return mixed
     */
    public function where($column, array $condition);

    /**
     * @return array
     */
    public function getFilterableColumns();

    /**
     * @param array $columns
     * @return mixed
     */
    public function renameHeaders(array $columns);

    /**
     * @param  string $name
     * @param  string $newName
     * @return mixed
     */
    public function renameHeader($name, $newName);

    /**
     * @return mixed
     */
    public function getRenamedHeaders();

    /**
     * @param  string $columnName
     * @return mixed
     */
    public function addSelectedColumn($columnName);
}