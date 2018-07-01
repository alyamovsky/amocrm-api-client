<?php

namespace ddlzz\AmoAPI;

use ddlzz\AmoAPI\Exception\InvalidArgumentException;
use ddlzz\AmoAPI\Validator\SettingsValidator;

/**
 * Class SettingsStorage. All amo- and library related variables are stored here.
 *
 * @author ddlzz
 */
class SettingsStorage
{
    const LIB_PATH = __DIR__.'/..';

    const NAMESPACE_PREFIX = '\ddlzz\AmoAPI';

    const SENDER_HTTP_HEADER = 'Content-Type: hal/json';

    /** @var array */
    private $methodsPaths = [
        'auth' => '/private/api/auth.php?type=json',
        'current' => '/api/v2/account?with=users,custom_fields',
        'leads' => '/api/v2/leads',
        'contacts' => '/api/v2/contacts',
        'companies' => '/api/v2/companies',
        'tasks' => '/api/v2/tasks',
        'customers' => '/api/v2/customers',
        'notes' => '/api/v2/notes',
    ];

    /** @var array */
    private $entitiesTypes = [
        'lead' => 'leads',
        'contact' => 'contacts',
        'company' => 'companies',
        'customer' => 'customers',
        'task' => 'tasks',
        'note' => 'notes',
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
    private $cookiePath = self::LIB_PATH.'/var/cookie.txt';

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
     *
     * @throws InvalidArgumentException
     *
     * @return string mixed
     */
    public function getMethodPath($code)
    {
        if (!isset($this->methodsPaths[$code])) {
            throw new InvalidArgumentException("The method with code \"$code\" doesn't exist");
        }

        return $this->methodsPaths[$code];
    }

    /**
     * @param array $paths
     */
    public function setMethodsPaths(array $paths)
    {
        $this->validator->validateMethodsPaths($paths);
        $this->methodsPaths = $paths;
    }

    /**
     * @return string
     */
    public function getCookiePath()
    {
        return $this->cookiePath;
    }

    /**
     * @param string $path
     */
    public function setCookiePath($path)
    {
        $this->validator->validateCookiePath($path);
        $this->cookiePath = $path;
    }

    /**
     * @param string $type
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function getMethodCodeByType($type)
    {
        if (!isset($this->entitiesTypes[$type])) {
            throw new InvalidArgumentException("The entity with type \"$type\" doesn't exist");
        }

        return $this->entitiesTypes[$type];
    }
}
