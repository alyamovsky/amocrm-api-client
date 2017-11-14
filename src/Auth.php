<?php


namespace ddlzz\AmoAPI;


/**
 * Class Auth
 * @package ddlzz\AmoAPI
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
     * @return bool
     */
    public function isAuthenticated()
    {
        $cookieFile = $this->cookiePath;
        $fourteenMinAgo = time() - 60 * 14;

        if ((!file_exists($cookieFile)) || (filemtime($cookieFile) <= $fourteenMinAgo)) {
            return false;
        }

        // If login has been changed, we need to delete the cookie file for the changes to take effect
        if (false === (strpos(file_get_contents($cookieFile), (str_replace('@', '%40', $this->login))))) {
            unlink($cookieFile);
            return false;
        }

        return true;
    }
}