<?php


namespace Tests\AmoAPI;

use ddlzz\AmoAPI\Client;
use ddlzz\AmoAPI\ClientFactory;
use ddlzz\AmoAPI\CredentialsManager;
use ddlzz\AmoAPI\SettingsStorage;
use PHPUnit\Framework\TestCase;


/**
 * Class ClientFactoryTest
 * @package Tests\AmoAPI
 * @author ddlzz
 * @covers  \ddlzz\AmoAPI\ClientFactory
 */
class ClientFactoryTest extends TestCase
{
    /** @var CredentialsManager */
    private $credentials;

    /** @var SettingsStorage */
    private $settings;

    protected function setUp()
    {
        $login = 'test@test.com';
        $this->credentials = new CredentialsManager('test', $login, md5('test'));

        file_put_contents(__DIR__ . '/../var/test_correct_login_cookie.txt', str_replace('@', '%40', $login));

        $this->settings = new SettingsStorage();
        $this->settings->setCookiePath('/var/test_correct_login_cookie.txt');
    }

    public function testCanBeCreated()
    {
        $client = ClientFactory::create($this->credentials, $this->settings);
        $this::assertInstanceOf(Client::class, $client);
    }

    protected function tearDown()
    {
        unlink(__DIR__ . '/../var/test_correct_login_cookie.txt');
    }
}