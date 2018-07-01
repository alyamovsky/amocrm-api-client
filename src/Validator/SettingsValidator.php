<?php

namespace ddlzz\AmoAPI\Validator;

use ddlzz\AmoAPI\Exception\InvalidArgumentException;
use ddlzz\AmoAPI\Utils\StringUtil;

/**
 * Class SettingsValidator.
 *
 * @author ddlzz
 */
class SettingsValidator
{
    /**
     * @param string $scheme
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function validateScheme($scheme)
    {
        if (!StringUtil::isOnlyLetters($scheme)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid scheme', $scheme));
        }

        return true;
    }

    /**
     * @param string $domain
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function validateDomain($domain)
    {
        if (!StringUtil::isDomain($domain)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid domain', $domain));
        }

        return true;
    }

    /**
     * @param string $userAgent
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function validateUserAgent($userAgent)
    {
        if (!StringUtil::isText($userAgent)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid user agent', $userAgent));
        }

        return true;
    }

    /**
     * @param array $paths
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function validateMethodsPaths(array $paths)
    {
        if (empty($paths)) {
            throw new InvalidArgumentException('An array "method Paths" must not be empty');
        }

        foreach ($paths as $key => $item) {
            if (empty($item)) {
                throw new InvalidArgumentException(sprintf('An array item "%s" must not be empty', $key));
            }

            if (!StringUtil::isUrlPath($item)) {
                throw new InvalidArgumentException(sprintf('"%s" is not a valid method path', $item));
            }
        }

        return true;
    }

    /**
     * @param string $path
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function validateCookiePath($path)
    {
        if (!StringUtil::isFilePath($path)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid file path', $path));
        }

        return true;
    }
}
