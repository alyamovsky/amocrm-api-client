<?php


namespace ddlzz\AmoAPI\Validators;

use ddlzz\AmoAPI\Exceptions\InvalidArgumentException;
use ddlzz\AmoAPI\Utils\StringUtil;


/**
 * Class SettingsValidator
 * @package ddlzz\AmoAPI\Validators
 * @author ddlzz
 */
class SettingsValidator
{
    /**
     * @param string $scheme
     * @return bool
     * @throws InvalidArgumentException
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
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validateDomain(string $domain)
    {
        if (!StringUtil::isDomain($domain)) {
            $message = sprintf('"%s" is not a valid domain', $domain);
            throw new InvalidArgumentException($message);
        }

        return true;
    }

    /**
     * @param string $userAgent
     * @return bool
     * @throws InvalidArgumentException
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
     * @param array $methodsPaths
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validateMethodsPaths(array $methodsPaths)
    {
        if (empty($methodsPaths)) {
            throw new InvalidArgumentException('An array "method Paths" must not be empty');
        }

        foreach ($methodsPaths as $key => $item) {
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
     * @param string $cookiePath
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validateCookiePath($cookiePath)
    {
        if (!StringUtil::isFilePath($cookiePath)) {
            $message = sprintf('"%s" is not a valid file path', $cookiePath);
            throw new InvalidArgumentException($message);
        }

        return true;
    }
}