<?php
namespace CsvMate;

/**
 * Class Csv
 * @package CsvMate
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
abstract class AbstractCsv
{
    /** @var  string */
    protected $filePath;
    /** @var  string */
    protected $delimiter = ',';
    /** @var  string */
    protected $lineLength = 0;
    /** @var  string */
    protected $enclosure = '"';
    /** @var  array  */
    protected $headers = [];
    /** @var  bool  */
    protected $hasHeaders = true;

    /**
     * @return array
     */
    abstract public function getHeaders();

    /**
     * Csv constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        \PropertySetterConfig::setObjectProperties($this, $config);
    }

    /**
     * @param  string $filePath
     * @return $this
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param  string $delimiter
     * @return $this
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @param  string $lineLength
     * @return $this
     */
    public function setLineLength($lineLength)
    {
        $this->lineLength = $lineLength;
        return $this;
    }

    /**
     * @return string
     */
    public function getLineLength()
    {
        return $this->lineLength;
    }

    /**
     * @param  string $enclosure
     * @return $this
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param  array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @throws CsvException
     */
    protected function validate()
    {
        if (!file_exists($this->filePath)) {
            throw new CsvException('File "'. $this->filePath .'" do not exists');
        }
    }

    /**
     * @param  null|bool $flag
     * @return bool
     */
    public function hasHeaders($flag = null)
    {
        if (is_bool($flag)) {
            $this->hasHeaders = $flag;
        }

        return $this->hasHeaders;
    }
}