<?php
namespace CsvMate;

/**
 * Class AbstractCsvTest
 * @package CsvMate
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class AbstractCsvTest extends \PHPUnit\Framework\TestCase
{
    /** @var  \CsvMate\AbstractCsv */
    private $abstractCsv;

    public function setUp()
    {
        parent::setUp();
        $this->abstractCsv = $this->getMockForAbstractClass('\CsvMate\AbstractCsv');
    }

    public function testSetFilePath()
    {
        $this->abstractCsv->setFilePath('path/to/file.csv');
        $this->assertEquals('path/to/file.csv', $this->abstractCsv->getFilePath());
    }

    public function testSetDelimiter()
    {
        $this->abstractCsv->setDelimiter(',');
        $this->assertEquals(',', $this->abstractCsv->getDelimiter());
    }

    public function testSetLineLength()
    {
        $this->abstractCsv->setLineLength(100);
        $this->assertEquals(100, $this->abstractCsv->getLineLength());
    }

    public function testSetEnclosure()
    {
        $this->abstractCsv->setEnclosure('"');
        $this->assertEquals('"', $this->abstractCsv->getEnclosure());
    }

    public function testSetHeaders()
    {
        $headers = ['one', 'two', 'three'];
        $this->assertInstanceOf('\CsvMate\AbstractCsv', $this->abstractCsv->setHeaders($headers));
    }

    public function testHasHeaders()
    {
        $this->assertTrue($this->abstractCsv->hasHeaders());
        $this->assertFalse($this->abstractCsv->hasHeaders(false));
    }
}