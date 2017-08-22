<?php
namespace CsvMate;

/**
 * Class ConditionTest
 * @package CsvMate
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class ConditionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param           string $value
     * @param           array $condition
     * @dataProvider    getPassedConditions
     */
    public function testIsPassed($value, array $condition)
    {
        $this->assertTrue(Condition::isPassed($value, $condition));
    }

    /**
     * @param           string $value
     * @param           array $condition
     * @dataProvider    getFailedConditions
     */
    public function testIsPassedFailed($value, array $condition)
    {
        $this->assertFalse(Condition::isPassed($value, $condition));
    }

    /**
     * @param               string $value
     * @param               array $condition
     * @dataProvider        getConditionsThrowException
     * @expectedException   \CsvMate\CsvException
     */
    public function testIsPassedThrowException($value, array $condition)
    {
        $this->assertTrue(Condition::isPassed($value, $condition));
    }

    /**
     * @return array
     */
    public function getPassedConditions()
    {
        return [
            ['uk', ['eq'        => ['uk']]],
            ['uk', ['eq'        => 'uk']],
            ['uk', ['in'        => ['uk', 'fr', 'de']]],
            ['uk', ['neq'       => 'fr']],
            ['uk', ['neq'       => ['fr']]],
            ['uk', ['nin'       => ['sp', 'fr', 'de']]],
            [50, ['gt'          => [1, 22, 36, 49]]],
            ['50', ['gt'        => ['1', '22', '36', '49']]],
            ['50', ['gt'        => ['1', 22, '36', 49]]],
            ['50', ['gteq'      => ['1', 22, '36', 50]]],
            ['50', ['gteq'      => ['1', 22, '36', '50']]],
            ['50', ['lt'      => ['51', 322, '136', '501']]],
            ['50', ['lteq'      => ['100', 122, '136', '50']]],
        ];
    }

    /**
     * @return array
     */
    public function getFailedConditions()
    {
        return [
            ['uk', ['neq'        => ['uk']]],
            ['uk', ['neq'        => 'uk']],
            ['uk', ['nin'        => ['uk', 'fr', 'de']]],
            ['uk', ['eq'       => 'fr']],
            ['uk', ['eq'       => ['fr']]],
            ['uk', ['in'       => ['sp', 'fr', 'de']]],
            [50, ['lt'          => [1, 22, 36, 49]]],
            ['50', ['lt'        => ['1', '22', '36', '49']]],
            ['50', ['lt'        => ['1', 22, '36', 49]]],
            ['50', ['lteq'      => ['1', 22, '36', 50]]],
            ['50', ['lteq'      => ['1', 22, '36', '50']]],
            ['50', ['gt'      => ['51', 322, '136', '501']]],
            ['50', ['gteq'      => ['100', 122, '136', '50']]],
        ];
    }

    /**
     * @return array
     */
    public function getConditionsThrowException()
    {
        return [
            ['uk', ['equals'        => ['uk']]],
        ];
    }
}