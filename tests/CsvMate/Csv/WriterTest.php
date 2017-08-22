<?php
namespace CsvMate\Csv;

/**
 * Class WriterTest
 * @package CsvMate\Csv
 * @author Alaa Al-Maliki <alaa.almaliki@gmai.com>
 */
class WriterTest extends \PHPUnit\Framework\TestCase
{
    /** @var  \CsvMate\Csv\Writer*/
    private $csvWriter;
    private $dataCopyFile = __DIR__ . '/../../resources/dataCopy.csv';

    public function setUp()
    {
        parent::setUp();

        $this->csvWriter = new \CsvMate\Csv\Writer(
            ['file_path' => $this->dataCopyFile]
        );
    }

    public function testWriteData()
    {
        $newCsvReader = new \CsvMate\Csv\Reader();
        $newCsvReader->setFilePath($this->dataCopyFile);

        $this->csvWriter->writeData($this->getDataToWrite());
        $this->assertTrue(file_exists($this->dataCopyFile));
        $this->assertTrue(is_array($newCsvReader->getData()));
    }

    /**
     * @return array
     */
    public function getDataToWrite()
    {
        return [
            ['first_name', 'last_name', 'email', 'occupation'],
            ['Alaa', 'Al-Maliki', 'alaa.almaliki@gmail.com', 'Software Engineer'],
            ['John', 'Doe', 'john.doe@gmail.com', 'Test Analyst'],
            ['Kate', 'Foxon', 'kate.foxon@gmail.com', 'Project Manager'],
            ['Allan', 'McGain', 'allan.mcgain@gmail.com', 'Database Administrator'],
            ['Andrew', 'James', 'andrew.james@gmail.com', 'System Analyst'],
            ['Jamie', 'Doug', 'jamie.doug@gmail.com', 'Technical Lead'],
            ['Karen', 'Hostfield', 'karne.hostfield@gmail.com', 'System Architect'],
        ];
    }
}