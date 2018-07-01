<?php

namespace ddlzz\AmoAPI;

use ddlzz\AmoAPI\Validator\CredentialsValidator;

/**
 * Class CredentialsManager. It stores and validates user credentials.
 *
 * @author ddlzz
 */
class CredentialsManager
{
    /** @var CredentialsValidator */
    private $validator;

    /** @var string */
    private $subdomain;

    /** @var string */
    private $login;

    /** @var string */
    private $hash;

    /**
     * CredentialsManager constructor.
     *
     * @param string $domain
     * @param string $login
     * @param string $hash
     */
    public function __construct($domain, $login, $hash)
    {
        $this->validator = new CredentialsValidator(); // Composition

        $this->validator->validateSubdomain($domain);
        $this->validator->validateLogin($login);
        $this->validator->validateHash($hash);

        $this->subdomain = $domain;
        $this->login = $login;
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /** @return string */
    public function getLogin()
    {
        return $this->login;
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
