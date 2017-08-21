<?php
namespace CsvMate;

/**
 * Interface CsvInterface
 * @package CsvMate
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
interface CsvInterface
{
    /**
     * @param  string $type
     * @param  array $arguments
     * @param  bool $singleton
     * @return mixed
     */
    public function getInstance($type, $arguments = [], $singleton = false);

    /**
     * @param  array $arguments
     * @param  bool $singleton
     * @return mixed
     */
    public function getWriteCsv($arguments = [], $singleton = false);

    /**
     * @param  array $arguments
     * @param  bool $singleton
     * @return mixed
     */
    public function getReadCsv($arguments = [], $singleton = false);
}