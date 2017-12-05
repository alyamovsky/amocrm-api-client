<?php


namespace Tests\AmoAPI;

use ddlzz\AmoAPI\Client;
use ddlzz\AmoAPI\ClientFactory;
use ddlzz\AmoAPI\CredentialsManager;
use ddlzz\AmoAPI\SettingsStorage;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;


/**
 * Class ClientFactoryTest
 * @package Tests\AmoAPI
 * @author ddlzz
 * @covers  \ddlzz\AmoAPI\ClientFactory
 */
final class ClientFactoryTest extends TestCase
{
    /** @var CredentialsManager */
    private $credentials;

    /** @var SettingsStorage */
    private $settings;

    protected function setUp()
    {
        $login = 'test@test.com';
        $this->credentials = new CredentialsManager('test', $login, md5('test'));

        $cookieDir = vfsStream::setup();
        $cookieFile = vfsStream::newFile('cookie.txt')->at($cookieDir)->setContent(str_replace('@', '%40', $login));

        $this->settings = new SettingsStorage();
        $this->settings->setCookiePath($cookieFile->url());
    }

    public function testCanBeCreated()
    {
        $client = ClientFactory::create($this->credentials, $this->settings);
        self::assertInstanceOf(Client::class, $client);
    }
}