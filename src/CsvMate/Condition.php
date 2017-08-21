<?php
namespace CsvMate;

/**
 * Class Condition
 * @package CsvMate
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
final class Condition
{
    /**
     * Checks if the given condition, upon which a csv data is selected, is passed
     *
     * @param  string $value
     * @param  array $condition
     * @return bool
     * @throws CsvException
     */
    static public function isPassed($value, array $condition)
    {
        $result = true;
        foreach ($condition as $op => $values) {
            $values = static::resolveValues($values);
            switch ($op) {
                case 'eq':
                case 'in':
                    $result = in_array($value, $values);
                    break;
                case 'neq':
                case 'nin':
                    $result = !in_array($value, $values);
                    break;
                case 'gt':
                    $result = static::getResult($op, $values, $value);
                    break;
                case 'gteq':
                    $result = static::getResult($op, $values, $value);
                    break;
                case 'lt':
                    $result = static::getResult($op, $values, $value);
                    break;
                case 'lteq':
                    $result = static::getResult($op, $values, $value);
                    break;
                default:
                    throw new CsvException('Operator: ' . $op . ' is not allowed');
            }
        }

        return $result;
    }

    /**
     * @param  string $values
     * @return array
     */
    static private function resolveValues($values)
    {
        if (!is_array($values)) {
            $values = [$values];
        }

        return $values;
    }

    /**
     * @param  string $op
     * @param  array $values
     * @param  string $value
     * @return bool
     */
    static private function getResult($op, array $values, $value)
    {
        $result = true;
        foreach ($values as $val) {
            switch ($op) {
                case 'gt':
                    $result &= $value > $val;
                    break;
                case 'gteq':
                    $result &= $value >= $val;
                    break;
                case 'lt':
                    $result &= $value < $val;
                    break;
                case 'lteq':
                    $result &= $value <= $val;
                    break;
                default:
                    $result = false;
            }
        }

        return $result;
    }
}