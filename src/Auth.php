<?php

namespace ddlzz\AmoAPI;

/**
 * Class Auth.
 *
 * @author ddlzz
 */
class Auth
{
    /** @var string */
    private $login;

    /** @var string */
    private $cookiePath;

    /**
     * Auth constructor.
     *
     * @param string $login
     * @param string $cookiePath
     */
    public function __construct($login, $cookiePath)
    {
        $this->login = $login;
        $this->cookiePath = $cookiePath;
    }

    /**
     * Checks if the cookie file exists and if it's valid.
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        $cookie = $this->cookiePath;
        $cookieLifetime = time() - 60 * 14; // 14 minutes

        if ((!file_exists($cookie)) || (filemtime($cookie) <= $cookieLifetime)) {
            return false;
        }

        // If login has been changed, we need to delete the cookie file for the changes to take effect
        if (false === (mb_strpos(file_get_contents($cookie), (str_replace('@', '%40', $this->login))))) {
            unlink($cookie);

            return false;
        }

        return true;
    }
}
