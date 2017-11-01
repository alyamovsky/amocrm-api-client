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

    /**
     * UrlBuilder constructor.
     * @param SettingsStorage $settings
     */
    public function __construct(SettingsStorage $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param string $domain
     * @return string
     */
    public function makeUserHost($domain)
    {
        return $this->settings->getScheme() . '://' . $domain . '.' . $this->settings->getDomain();
    }
}