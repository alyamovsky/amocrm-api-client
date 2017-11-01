<?php


namespace ddlzz\AmoAPI;

use ddlzz\AmoAPI\Exceptions\InvalidArgumentException;
use ddlzz\AmoAPI\Utils\StringUtil;


/**
 * Class CredentialsManager. It stores and validates user credentials.
 * @package ddlzz\AmoAPI
 * @author ddlzz
 */
class CredentialsManager
{
    /** @var string */
    private $subdomain;

    /** @var string */
    private $login;

    /** @var string */
    private $hash;

    /**
     * CredentialsManager constructor.
     * @param string $domain
     * @param string $login
     * @param string $hash
     */
    public function __construct($domain, $login, $hash)
    {
        $this->subdomain = $this->validateSubdomain($domain);
        $this->login = $this->validateLogin($login);
        $this->hash = $this->validateHash($hash);
    }

    /**
     * @param string $subdomain
     * @return string
     * @throws InvalidArgumentException
     */
    private function validateSubdomain($subdomain)
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
    private function validateLogin($login)
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
    private function validateHash($hash)
    {
        if (!StringUtil::isAlNum($hash)) {
            $message = sprintf('"%s" is not a valid hash', $hash);
            throw new InvalidArgumentException($message);
        }

        return $hash;
    }

    /**
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @return array
     */
    public function getCredentials()
    {
        return [
            'USER_LOGIN' => $this->login,
            'USER_HASH' => $this->hash,
        ];
    }
}