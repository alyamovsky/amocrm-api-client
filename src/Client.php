<?php


namespace ddlzz\AmoAPI;

use ddlzz\AmoAPI\Exceptions\InvalidArgumentException;
use ddlzz\AmoAPI\Entities\EntityInterface;
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
        $this->urlBuilder = new UrlBuilder($this->settings); // Composition

        if (!$this->isAuthorized()) {
            $this->authorize();
        }
    }

    /**
     * Checks if the cookie file exists and if it's valid.
     * @return bool
     */
    private function isAuthorized()
    {
        $cookieFile = $this->settings->getCookiePath();
        $fourteenMinAgo = time() - 60 * 14;

        if ((!file_exists($cookieFile)) || (filemtime($cookieFile) <= $fourteenMinAgo)) {
            return false;
        }

        // If login has been changed, we need to delete the cookie file for the changes to take effect
        if (false === (strpos(file_get_contents($cookieFile), (str_replace('@', '%40', $this->credentials->getLogin()))))) {
            unlink($cookieFile);
            return false;
        }

        return true;
    }

    /**
     * @param string $methodCode
     * @return string
     * @throws InvalidArgumentException
     */
    private function prepareMethodUrl($methodCode)
    {
        $host = $this->urlBuilder->makeUserHost($this->credentials->getSubdomain());
        $methodPath = $this->settings->getMethodPath($methodCode);

        return $host . $methodPath;
    }

    /**
     * @return string
     */
    private function authorize()
    {
        $authUrl = $this->prepareMethodUrl('auth');

        $result = $this->dataSender->send($authUrl, $this->credentials->getCredentials());

        return $result;
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
        return $this->set($entity, 'update');
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
        $data[$action][] = $entity->getFields();
        $url = $this->prepareMethodUrl($entity->getRequestName());
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

        if (null === $lastCheck) {
            $sleepTime = 1;
            $lastRequestTime = $now - $lastCheck;
            if ($lastRequestTime < $sleepTime) {
                usleep(($sleepTime - $lastRequestTime) * 1000000);
            }
        }

        $lastCheck = microtime(true);
    }
}