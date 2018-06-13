<?php

namespace ddlzz\AmoAPI\Validator;

use ddlzz\AmoAPI\Exception\InvalidArgumentException;
use ddlzz\AmoAPI\Utils\StringUtil;

/**
 * Class CredentialsValidator.
 *
 * @author ddlzz
 */
class CredentialsValidator
{
    /**
     * @param string $subdomain
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function validateSubdomain($subdomain)
    {
        if (!StringUtil::isAlNum($subdomain)) {
            $message = sprintf('"%s" is not a valid subdomain', $subdomain);
            throw new InvalidArgumentException($message);
        }

        return true;
    }

    /**
     * @param string $login
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function validateLogin($login)
    {
        if (!StringUtil::isEmail($login)) {
            $message = sprintf('"%s" is not a valid login', $login);
            throw new InvalidArgumentException($message);
        }

        return true;
    }

    /**
     * @param string $hash
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function validateHash($hash)
    {
        if (!StringUtil::isAlNum($hash)) {
            $message = sprintf('"%s" is not a valid hash', $hash);
            throw new InvalidArgumentException($message);
        }

        return true;
    }
}
