<?php

namespace ddlzz\AmoAPI;

use ddlzz\AmoAPI\Exception\InvalidArgumentException;
use ddlzz\AmoAPI\Exception\RuntimeException;
use ddlzz\AmoAPI\Model\ModelFactory;
use ddlzz\AmoAPI\Model\ModelInterface;
use ddlzz\AmoAPI\Request\DataSender;
use ddlzz\AmoAPI\Request\UrlBuilder;

/**
 * Class Client. The main class for interacting with amoCRM.
 *
 * @author ddlzz
 */
class Client
{
    /** @var CredentialsManager */
    protected $credentials;

    /** @var DataSender */
    protected $dataSender;

    /** @var SettingsStorage */
    protected $settings;

    /** @var UrlBuilder */
    protected $urlBuilder;

    /** @var Auth */
    protected $auth;

    /**
     * Client constructor.
     *
     * @param CredentialsManager            $credentials
     * @param DataSender                    $dataSender
     * @param \ddlzz\AmoAPI\SettingsStorage $settings
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
     *
     * @throws InvalidArgumentException
     *
     * @return ModelInterface
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

        $entityFactory = new ModelFactory($this->settings);
        /** @var ModelInterface $entity */
        $entity = $entityFactory->create($type);
        $entity->fill($result['_embedded']['items'][0]);

        return $entity;
    }

    /**
     * @param ModelInterface $entity
     *
     * @return string
     */
    public function add(ModelInterface $entity)
    {
        return $this->set($entity, 'add');
    }

    /**
     * @param ModelInterface $entity
     *
     * @return string
     */
    public function update(ModelInterface $entity)
    {
        $entity->setUpdatedAt();

        return $this->set($entity, 'update');
    }

    /**
     * @throws RuntimeException
     */
    protected function checkAuth()
    {
        if (!$this->auth->isAuthenticated()) {
            $result = $this->dataSender->send($this->urlBuilder->buildMethodUrl('auth'), $this->credentials->getCredentials());

            if (!empty($result) && (!file_exists($this->settings->getCookiePath()))) {
                $message = 'An error occurred while creating the cookie file '.$this->settings->getCookiePath();
                throw new RuntimeException($message);
            }
        }
    }

    /**
     * Adds or updates an entity.
     *
     * @param ModelInterface $entity
     * @param string         $action
     *
     * @return string
     */
    protected function set(ModelInterface $entity, $action)
    {
        $entity->setFields($action);

        $data = [];
        $data[$action][] = $entity->getFields();
        $url = $this->urlBuilder->buildMethodUrl($entity->getRequestName());
        $this->waitASec();

        return $this->dataSender->send($url, $data);
    }

    /**
     * Adds a one second pause because of Amo request limits.
     */
    protected function waitASec()
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

    /**
     * Provides some info about current account and its custom fields ids.
     *
     * @return string
     */
    public function checkAccount()
    {
        $url = $this->urlBuilder->buildMethodUrl('current');
        return $this->dataSender->send($url, []);
    }
}
