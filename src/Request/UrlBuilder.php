<?php


namespace ddlzz\AmoAPI\Request;
use ddlzz\AmoAPI\SettingsStorage;


/**
 * Class UrlBuilder
 * @package ddlzz\AmoAPI\Request
 * @author ddlzz
 */
class UrlBuilder
{
    /** @var SettingsStorage */
    private $settings;

    /** @var string */
    private $subdomain;

    /**
     * UrlBuilder constructor.
     * @param SettingsStorage $settings
     * @param string $subdomain
     */
    public function __construct(SettingsStorage $settings, $subdomain)
    {
        $this->settings = $settings;
        $this->subdomain = $subdomain;
    }

    /**
     * @param string $methodCode
     * @param array $params
     * @return string
     * @throws \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     */
    public function prepareMethodUrl($methodCode, $params = [])
    {
        $host = $this->makeUserHost($this->subdomain);
        $methodPath = $this->settings->getMethodPath($methodCode);

        if (!empty($params)) {
            $query = '?';
            foreach ($params as $key => $param) {
                $query .= $key . '=' . $param;

                if ($param !== end($params)) {
                    $query .= '&';
                }
            }
        }

        $result = isset($query) ? $host . $methodPath . $query : $host . $methodPath;

        return $result;
    }

    /**
     * @param string $domain
     * @return string
     */
    private function makeUserHost($domain)
    {
        return $this->settings->getScheme() . '://' . $domain . '.' . $this->settings->getDomain();
    }
}