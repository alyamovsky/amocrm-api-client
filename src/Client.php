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
     * @param $entityType
     * @param $id
     * @return EntityInterface
     * @throws InvalidArgumentException
     */
    public function findById($entityType, $id)
    {
        $method = $this->settings->getMethodCodeByType($entityType);
        $params = ['id' => $id];
        $url = $this->urlBuilder->prepareMethodUrl($method, $params);
        $result = json_decode($this->dataSender->send($url), true);

        if (empty($result)) {
            throw new InvalidArgumentException("The $entityType with id $id is not found on the server");
        }

        $entityFactory = new EntityFactory($this->settings);
        /** @var EntityInterface $entity */
        $entity = $entityFactory->create($entityType);
        $entity->fill($result['_embedded']['items'][0]);

        return $entity;
    }

    /**
     * @param EntityInterface $entity
     * @return string
     */
    public function add(EntityInterface $entity)
    {
        return $this->set($entity, 'add');
    }

    /**
     * @param EntityInterface $entity
     * @return string
     */
    public function update(EntityInterface $entity)
    {
        $entity->setUpdatedAtParam();
        return $this->set($entity, 'update');
    }

    /**
     * @throws RuntimeException
     */
    private function checkAuth()
    {
        if (!$this->auth->isAuthenticated()) {
            $result = $this->dataSender->send($this->urlBuilder->prepareMethodUrl('auth'), $this->credentials->getCredentials());

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
     */
    private function set(EntityInterface $entity, $action)
    {
        $entity->setFieldsParams($action);

        $data = [];
        $data[$action][] = $entity->getFields();
        $url = $this->urlBuilder->prepareMethodUrl($entity->getRequestName());
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
            $lastRequestTime = $now - $lastCheck;
            if ($lastRequestTime < $sleepTime) {
                usleep(($sleepTime - $lastRequestTime) * 1000000);
            }
        }

        $lastCheck = microtime(true);
    }
}