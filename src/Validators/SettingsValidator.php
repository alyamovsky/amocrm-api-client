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
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validateUserAgent($userAgent)
    {
        if (!StringUtil::isAlNum($userAgent)) {
            $message = sprintf('"%s" is not a valid user agent', $userAgent);
            throw new InvalidArgumentException($message);
        }

        return true;
    }
}