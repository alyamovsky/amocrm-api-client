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
            throw new InvalidArgumentException(sprintf('"%s" is not a valid subdomain', $subdomain));
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
            throw new InvalidArgumentException(sprintf('"%s" is not a valid login', $login));
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
            throw new InvalidArgumentException(sprintf('"%s" is not a valid hash', $hash));
        }

        return true;
    }
}
