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
            $message = sprintf('"%s" is not a valid scheme', $scheme);
            throw new InvalidArgumentException($message);
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
            $message = sprintf('"%s" is not a valid domain', $domain);
            throw new InvalidArgumentException($message);
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
            $message = sprintf('"%s" is not a valid user agent', $userAgent);
            throw new InvalidArgumentException($message);
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
                throw new InvalidArgumentException("An array item \"$key\" must not be empty");
            }

            if (!StringUtil::isUrlPath($item)) {
                $message = sprintf('"%s" is not a valid method path', $item);
                throw new InvalidArgumentException($message);
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
            $message = sprintf('"%s" is not a valid file path', $path);
            throw new InvalidArgumentException($message);
        }

        return true;
    }
}
