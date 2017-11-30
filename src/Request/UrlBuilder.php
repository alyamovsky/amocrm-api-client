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
     * @param string $code
     * @param array $params
     * @return string
     * @throws \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     */
    public function prepareMethodUrl($code, $params = [])
    {
        $host = $this->makeUserHost($this->subdomain);
        $path = $this->settings->getMethodPath($code);

        if (!empty($params)) {
            $query = '?';
            foreach ($params as $key => $param) {
                $query .= $key . '=' . $param;

                if ($param !== end($params)) {
                    $query .= '&';
                }
            }
        }

        $result = isset($query) ? $host . $path . $query : $host . $path;

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