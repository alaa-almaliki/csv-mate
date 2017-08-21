<?php
namespace CsvMate;

/**
 * Class Csv
 * @package CsvMate
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Csv implements CsvInterface
{
    const TYPE_CSV_WRITE    = 'write_csv';
    const TYPE_CSV_READ     = 'read_csv';

    /** @var array  */
    private static $csvs = [];

    /**
     * Gets a csv instance with type, whether a reader or writer csv class
     *
     * @param  string $type
     * @param  array $arguments
     * @param  bool $singleton
     * @return \CsvMate\Csv\Reader|\CsvMate\Csv\Writer
     */
    public function getInstance($type, $arguments = [], $singleton = false)
    {
        $class = $this->getClass($type);
        if ($singleton === true) {
            if (!static::$csvs[$type] instanceof AbstractCsv) {
                static::$csvs[$type] = new $class($arguments);
            }

            return static::$csvs[$type];
        }

        return new $class($arguments);
    }

    /**
     * @param  array $arguments
     * @param  bool $singleton
     * @return \CsvMate\Csv\Writer
     */
    public function getWriteCsv($arguments = [], $singleton = false)
    {
        return $this->getInstance(self::TYPE_CSV_WRITE, $arguments, $singleton);
    }

    /**
     * @param  array $arguments
     * @return Csv\Writer
     */
    public function getWriteCsvSingleton($arguments = [])
    {
        return $this->getWriteCsv($arguments, true);
    }

    /**
     * @param  array $arguments
     * @param  bool $singleton
     * @return \CsvMate\Csv\Reader
     */
    public function getReadCsv($arguments = [], $singleton = false)
    {
        return $this->getInstance(self::TYPE_CSV_READ, $arguments, $singleton);
    }

    /**
     * @param  array $arguments
     * @return Csv\Reader
     */
    public function getReadCsvSingleton($arguments = [])
    {
        return $this->getReadCsv($arguments, true);
    }

    /**
     * @param  string $type
     * @return string
     * @throws CsvException
     */
    protected function getClass($type)
    {
        $instances = [
            self::TYPE_CSV_READ => 'Reader',
            self::TYPE_CSV_WRITE => 'Writer'
        ];

        if (!in_array($type, array_flip($instances))) {
            throw new CsvException('Type ' . $type . ' is not recognized');
        }

        $instanceParts = [
            'CsvMate',
            'Csv',
            $instances[$type],
        ];

        return implode('\\', $instanceParts);
    }
}