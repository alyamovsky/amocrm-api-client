<?php


namespace ddlzz\AmoAPI\Validators;

use ddlzz\AmoAPI\Exceptions\InvalidArgumentException;
use ddlzz\AmoAPI\Utils\StringUtil;


/**
 * Class CredentialsValidator
 * @package ddlzz\AmoAPI\Validators
 * @author ddlzz
 */
class CredentialsValidator
{
    /**
     * @param string $subdomain
     * @return string
     * @throws InvalidArgumentException
     */
    public function validateSubdomain($subdomain)
    {
        if (!StringUtil::isAlNum($subdomain)) {
            $message = sprintf('"%s" is not a valid subdomain', $subdomain);
            throw new InvalidArgumentException($message);
        }

        return $subdomain;
    }

    /**
     * @param string $login
     * @return string
     * @throws InvalidArgumentException
     */
    public function validateLogin($login)
    {
        if (!StringUtil::isEmail($login)) {
            $message = sprintf('"%s" is not a valid login', $login);
            throw new InvalidArgumentException($message);
        }

        return $login;
    }

    /**
     * @param string $hash
     * @return string
     * @throws InvalidArgumentException
     */
    public function validateHash($hash)
    {
        if (!StringUtil::isAlNum($hash)) {
            $message = sprintf('"%s" is not a valid hash', $hash);
            throw new InvalidArgumentException($message);
        }

        return $hash;
    }
}