<?php

namespace ddlzz\AmoAPI\Utils;

/**
 * Class ArrayUtil.
 *
 * @author ddlzz
 */
class ArrayUtil
{
    /**
     * Replaces a value in a multidimensional array with another value.
     *
     * @param int|string $search
     * @param int|string $replace
     * @param array      $haystack
     *
     * @return array
     */
    public static function searchAndReplace($search, $replace, $haystack)
    {
        $callback = function (&$value, $key, array $params) {
            if ($params['search'] === $value) {
                $value = $params['replace'];
            }
        };

        array_walk_recursive($haystack, $callback, ['search' => $search, 'replace' => $replace]);

        return $haystack;
    }
}
