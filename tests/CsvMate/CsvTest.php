<?php
namespace CsvMate;

/**
 * Class CsvTest
 * @package CsvMate
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class CsvTest extends \PHPUnit\Framework\TestCase
{
    /** @var  \CsvMate\Csv */
    private $csv;

    public function setUp()
    {
        parent::setUp();
        $this->csv = new Csv();
    }

    public function testGetWriteCsv()
    {
        $this->assertInstanceOf('\CsvMate\Csv\Writer', $this->csv->getWriteCsv());
    }

    public function testGetWriteCsvSingleton()
    {
        $this->assertInstanceOf('\CsvMate\Csv\Writer', $this->csv->getWriteCsvSingleton());
    }

    public function testGetReadCsv()
    {
        $this->assertInstanceOf('\CsvMate\Csv\Reader', $this->csv->getReadCsv());
    }

    public function testGetReadCsvSingleton()
    {
        $this->assertInstanceOf('\CsvMate\Csv\Reader', $this->csv->getReadCsvSingleton());
    }

    /**
     * @expectedException \CsvMate\CsvException
     */
    public function testGetInstanceThrowException()
    {
        $this->csv->getInstance('unknown');
    }
}