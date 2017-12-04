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
        if (!self::isCorrectType($value) || !ctype_alnum((string)$value)) {
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
        return (self::isCorrectType($value))
            && (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $value) // valid chars check
            && preg_match("/^.{1,253}$/", $value) // overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $value)); // length of each label
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isOnlyLetters($value)
    {
        if (!self::isCorrectType($value) || !ctype_alpha($value)) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the variable contains letters and punctuation marks only.
     * @param string $value
     * @return bool
     */
    public static function isText($value)
    {
        return (self::isCorrectType($value) && preg_match('/^[A-Za-z0-9\.\-\'"!,;:\?()_\/\s]+$/', $value));
    }

    /**
     * Checks if the variable is a valid URL path.
     * @param string $value
     * @return bool
     */
    public static function isUrlPath($value)
    {
        return (self::isCorrectType($value) && preg_match('/^\/[A-Za-z0-9\/?&=,;@\[\]\.#]+$/', $value));
    }

    /**
     * The valid file path starts with '/' or 'vfs://'
     * @param string $value
     * @return bool
     */
    public static function isFilePath($value)
    {
        return (self::isCorrectType($value) && preg_match('/^(vfs:\/)?\/[A-Za-z0-9\/\._\s-]+$/', $value));
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private static function isCorrectType($value)
    {
        return (is_int($value) || (is_string($value)));
    }
}