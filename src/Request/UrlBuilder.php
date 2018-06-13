<?php

namespace ddlzz\AmoAPI\Request;

use ddlzz\AmoAPI\Exception\InvalidArgumentException;
use ddlzz\AmoAPI\SettingsStorage;

/**
 * Class UrlBuilder.
 *
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
     *
     * @param SettingsStorage $settings
     * @param string          $subdomain
     */
    public function __construct(SettingsStorage $settings, $subdomain)
    {
        $this->settings = $settings;
        $this->subdomain = $subdomain;
    }

    /**
     * @param string $code
     * @param array  $params
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function buildMethodUrl($code, $params = [])
    {
        $host = $this->makeUserHost($this->subdomain);
        $path = $this->settings->getMethodPath($code);

        if (!empty($params)) {
            $query = '?';
            foreach ($params as $key => $param) {
                $query .= $key.'='.$param;

                if ($param !== end($params)) {
                    $query .= '&';
                }
            }
        }

        $result = isset($query) ? $host.$path.$query : $host.$path;

        return $result;
    }

    /**
     * @param string $subdomain
     *
     * @return string
     */
    private function makeUserHost($subdomain)
    {
        return $this->settings->getScheme().'://'.$subdomain.'.'.$this->settings->getDomain();
    }
}
