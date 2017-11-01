<?php


namespace ddlzz\AmoAPI;


/**
 * Class SettingsStorage. All amo- and library related variables are stored here.
 * @package ddlzz\AmoAPI
 * @author ddlzz
 */
class SettingsStorage
{
    const LIB_PATH = __DIR__ . '/..';

    const COOKIE_PATH = self::LIB_PATH . '/var/cookie.txt';

    const NAMESPACE_PREFIX = '\ddlzz\AmoAPI';

    const SENDER_HTTP_HEADER = 'Content-Type: hal/json';

    /** @var array */
    private $methodsPaths = [
        'auth' => '/private/api/auth.php?type=json',
        'current' => '/api/v2/account?with=users,custom_fields',
        'leads' => '/api/v2/leads',
    ];

    /** @var string */
    private $scheme = 'https';

    /** @var string */
    private $domain = 'amocrm.ru';

    /** @var string */
    private $userAgent = 'amoAPI PHP Client';

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return array
     */
    public function getMethodPaths()
    {
        return $this->methodsPaths;
    }
}