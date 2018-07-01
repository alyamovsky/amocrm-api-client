<?php

namespace ddlzz\AmoAPI;

use ddlzz\AmoAPI\Request\Curl;
use ddlzz\AmoAPI\Request\DataSender;

/**
 * Class ClientFactory. It handles all dependencies for Client class.
 *
 * @author ddlzz
 */
class ClientFactory
{
    /**
     * @param CredentialsManager   $credentials
     * @param SettingsStorage|null $settings
     *
     * @return Client
     */
    public static function create(CredentialsManager $credentials, SettingsStorage $settings = null)
    {
        $settings = $settings ?: new SettingsStorage();
        $dataSender = self::buildSender($settings);

        return new Client($credentials, $dataSender, $settings);
    }

    /**
     * @param SettingsStorage $settings
     *
     * @return DataSender
     */
    private static function buildSender(SettingsStorage $settings)
    {
        return new DataSender(new Curl(), $settings);
    }
}
