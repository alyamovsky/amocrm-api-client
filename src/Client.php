<?php


namespace ddlzz\AmoAPI;

use ddlzz\AmoAPI\Entities\EntityFactory;
use ddlzz\AmoAPI\Entities\EntityInterface;
use ddlzz\AmoAPI\Exceptions\InvalidArgumentException;
use ddlzz\AmoAPI\Exceptions\RuntimeException;
use ddlzz\AmoAPI\Request\DataSender;
use ddlzz\AmoAPI\Request\UrlBuilder;


/**
 * Class Client. The main class for interacting with amoCRM.
 * @package ddlzz\AmoAPI
 * @author ddlzz
 */
class Client
{
    /** @var CredentialsManager */
    private $credentials;

    /** @var DataSender */
    private $dataSender;

    /** @var SettingsStorage */
    private $settings;

    /** @var UrlBuilder */
    private $urlBuilder;

    /** @var Auth */
    private $auth;

    /**
     * Client constructor.
     * @param CredentialsManager $credentials
     * @param DataSender $dataSender
     * @param \ddlzz\AmoAPI\SettingsStorage $settings
     * @throws Exceptions\CurlException
     * @throws Exceptions\ErrorCodeException
     * @throws Exceptions\FailedAuthException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function __construct(CredentialsManager $credentials, DataSender $dataSender, SettingsStorage $settings)
    {
        $this->credentials = $credentials;
        $this->dataSender = $dataSender;
        $this->settings = $settings;
        $this->urlBuilder = new UrlBuilder($this->settings, $this->credentials->getSubdomain()); // Composition
        $this->auth = new Auth($this->credentials->getLogin(), $this->settings->getCookiePath());

        $this->checkAuth();
    }

    /**
     * @param $type
     * @param $id
     * @return EntityInterface
     * @throws Exceptions\CurlException
     * @throws Exceptions\EntityFactoryException
     * @throws Exceptions\ErrorCodeException
     * @throws Exceptions\FailedAuthException
     * @throws InvalidArgumentException
     */
    public function findById($type, $id)
    {
        $method = $this->settings->getMethodCodeByType($type);
        $params = ['id' => $id];
        $url = $this->urlBuilder->buildMethodUrl($method, $params);
        $result = json_decode($this->dataSender->send($url), true);

        if (empty($result)) {
            throw new InvalidArgumentException("The $type with id $id is not found on the server");
        }

        $entityFactory = new EntityFactory($this->settings);
        /** @var EntityInterface $entity */
        $entity = $entityFactory->create($type);
        $entity->fill($result['_embedded']['items'][0]);

        return $entity;
    }

    /**
     * @param EntityInterface $entity
     * @return string
     * @throws Exceptions\CurlException
     * @throws Exceptions\ErrorCodeException
     * @throws Exceptions\FailedAuthException
     * @throws InvalidArgumentException
     */
    public function add(EntityInterface $entity)
    {
        return $this->set($entity, 'add');
    }

    /**
     * @param EntityInterface $entity
     * @return string
     * @throws Exceptions\CurlException
     * @throws Exceptions\ErrorCodeException
     * @throws Exceptions\FailedAuthException
     * @throws InvalidArgumentException
     */
    public function update(EntityInterface $entity)
    {
        $entity->setUpdatedAt();
        return $this->set($entity, 'update');
    }

    /**
     * @throws Exceptions\CurlException
     * @throws Exceptions\ErrorCodeException
     * @throws Exceptions\FailedAuthException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    private function checkAuth()
    {
        if (!$this->auth->isAuthenticated()) {
            $result = $this->dataSender->send($this->urlBuilder->buildMethodUrl('auth'), $this->credentials->getCredentials());

            if (!empty($result) && (!file_exists($this->settings->getCookiePath()))) {
                $message = 'An error occurred while creating the cookie file ' . $this->settings->getCookiePath();
                throw new RuntimeException($message);
            }
        }
    }

    /**
     * Adds or updates an entity
     * @param EntityInterface $entity
     * @param string $action
     * @return string
     * @throws Exceptions\CurlException
     * @throws Exceptions\ErrorCodeException
     * @throws Exceptions\FailedAuthException
     * @throws InvalidArgumentException
     */
    private function set(EntityInterface $entity, $action)
    {
        $entity->setFields($action);

        $data = [];
        $data[$action][] = $entity->getFields();
        $url = $this->urlBuilder->buildMethodUrl($entity->getRequestName());
        $this->waitASec();
        $result = $this->dataSender->send($url, $data);

        return $result;
    }

    /**
     * Adds a one second pause because of Amo request limits
     */
    private function waitASec()
    {
        $now = microtime(true);
        static $lastCheck = null;

        if (null !== $lastCheck) {
            $sleepTime = 1;
            $lastRequest = $now - $lastCheck;
            if ($lastRequest < $sleepTime) {
                usleep(($sleepTime - $lastRequest) * 1000000);
            }
        }

        $lastCheck = microtime(true);
    }
}