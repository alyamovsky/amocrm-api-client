<?php


namespace ddlzz\AmoAPI\Utils;


use ddlzz\AmoAPI\Exceptions\InvalidArgumentException;

/**
 * Class StringUtil
 * @package ddlzz\AmoAPI\Utils
 * @author ddlzz
 */
class StringUtil
{
    /**
     * @param string $value
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function isAlNum($value)
    {
        if (!ctype_alnum($value)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $value
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function isEmail($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}