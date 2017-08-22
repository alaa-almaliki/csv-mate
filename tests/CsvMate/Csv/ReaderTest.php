<?php
namespace CsvMate\Csv;

/**
 * Class ReaderTest
 * @package CsvMate\Csv
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class ReaderTest extends \PHPUnit\Framework\TestCase
{
    /** @var  \CsvMate\Csv\Reader */
    private $csvReader;

    public function setUp()
    {
        parent::setUp();
        $this->csvReader = new \CsvMate\Csv\Reader();

        $this->csvReader->setFilePath(__DIR__ . '/../../resources/data.csv');
    }

    public function testGetData()
    {
        $data = $this->csvReader->getData();
        $cachedData = $this->csvReader->getData();
        $this->assertTrue($cachedData === $data);
        $this->assertTrue(is_array($data));
        $this->assertTrue(!empty($data));
        $this->assertTrue(count($data) === 101);
    }

    public function testGetAssocData()
    {
        $data = $this->csvReader->getAssocData();

        $randomIndex = mt_rand(1,100);
        $rowKeys = array_keys($data[$randomIndex]);

        $this->assertTrue(is_array($data));
        $this->assertTrue(!empty($data));
        $this->assertTrue(count($data) === 101);
        $this->assertTrue($rowKeys === $this->csvReader->getHeaders());
    }

    public function testGetColumns()
    {
        $columnsHeaders = ['policy_id', 'state_code'];
        $columns = $this->csvReader->getColumns($columnsHeaders);

        $this->assertTrue(is_array($columns));
        $this->assertTrue(in_array($columns[0][0], $columnsHeaders));
        $this->assertTrue(in_array($columns[1][0], $columnsHeaders));
    }

    public function testSelectColumns()
    {
        $this->csvReader->renameHeaders(['policy_id' => 'policyID']);
        $this->csvReader->selectColumns(['policyID', 'state_code']);
        $data = $this->csvReader->getAssocData();
        unset($data[0]); // unset headers

        foreach ($data as $row) {
            $this->assertTrue(is_array($row));
            $this->assertTrue(array_keys($row) === ['policyID', 'state_code']);
        }
    }

    public function testGetHeaders()
    {
        $this->assertEquals($this->csvReader->getData()[0], $this->csvReader->getHeaders());
    }

    public function testDataWithConditions()
    {
        $this->csvReader->renameHeaders(['construction' => 'construction_type']);

        $this->csvReader->where('construction_type', ['eq' => 'Wood'])
            ->where('eq_site_limit', ['gt' => '0'])
            ->where(3, ['lt' => '1920744']);
        $expected = 27;
        $this->assertEquals($expected, count($this->csvReader->getAssocData()));
    }

    public function testDataWithSelectedColumns()
    {
        $this->csvReader->renameHeaders(['policy_id' => 'PolicyID']);
        $this->csvReader->selectColumns(['PolicyID', 'state_code', 'county', 'eq_site_limit', 'construction']);
        $data = $this->csvReader->getAssocData();

        $this->assertEquals(5, count($data[0]));
        $this->assertEquals(5, count($data[1]));
    }
}