<?php


namespace ddlzz\AmoAPI;

use ddlzz\AmoAPI\Exceptions\InvalidArgumentException;
use ddlzz\AmoAPI\Validators\SettingsValidator;


/**
 * Class SettingsStorage. All amo- and library related variables are stored here.
 * @package ddlzz\AmoAPI
 * @author ddlzz
 */
class SettingsStorage
{
    const LIB_PATH = __DIR__ . '/..';

    const NAMESPACE_PREFIX = '\ddlzz\AmoAPI';

    const SENDER_HTTP_HEADER = 'Content-Type: hal/json';

    /** @var array */
    private $methodsPaths = [
        'auth' => '/private/api/auth.php?type=json',
        'current' => '/api/v2/account?with=users,custom_fields',
        'leads' => '/api/v2/leads',
    ];

    /** @var SettingsValidator */
    private $validator;

    /** @var string */
    private $scheme = 'https';

    /** @var string */
    private $domain = 'amocrm.ru';

    /** @var string */
    private $userAgent = 'amoAPI PHP Client';

    /** @var string */
    private $cookiePath = self::LIB_PATH . '/var/cookie.txt';

    /**
     * SettingsStorage constructor.
     */
    public function __construct()
    {
        $this->validator = new SettingsValidator(); // Composition
    }

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
        $this->validator->validateScheme($scheme);
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
        $this->validator->validateDomain($domain);
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
        $this->validator->validateUserAgent($userAgent);
        $this->userAgent = $userAgent;
    }

    /**
     * @return array
     */
    public function getMethodsPaths()
    {
        return $this->methodsPaths;
    }

    /**
     * @param string $code
     * @return string mixed
     * @throws InvalidArgumentException
     */
    public function getMethodPath($code)
    {
        if (!isset($this->methodsPaths[$code])) {
            throw new InvalidArgumentException("The method with code \"$code\" doesn't exist");
        }

        return $this->methodsPaths[$code];
    }

    /**
     * @param array $methodsPaths
     */
    public function setMethodsPaths(array $methodsPaths)
    {
        $this->validator->validateMethodsPaths($methodsPaths);
        $this->methodsPaths = $methodsPaths;
    }

    /**
     * @return string
     */
    public function getCookiePath()
    {
        return $this->cookiePath;
    }

    /**
     * Please use the relative path only for this parameter.
     * @param string $cookiePath
     */
    public function setCookiePath($cookiePath)
    {
        $this->validator->validateCookiePath($cookiePath);
        $this->cookiePath = self::LIB_PATH . $cookiePath;
    }
}