<?php


namespace ddlzz\AmoAPI\Utils;


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
     */
    public static function isEmail($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isDomain($value)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $value) // valid chars check
            && preg_match("/^.{1,253}$/", $value) // overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $value)); // length of each label
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isOnlyLetters($value)
    {
        if (!preg_match('/[A-Za-z]/', $value)) {
            return false;
        }

        return true;
    }
}