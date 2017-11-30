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

        $vfsRoot = vfsStream::setup();
        $file = vfsStream::newFile('test_correct_login_cookie.txt')->at($vfsRoot)->setContent(str_replace('@', '%40', $login));

        $this->settings = new SettingsStorage();
        $this->settings->setCookiePath($file->url());
    }

    public function testCanBeCreated()
    {
        $client = ClientFactory::create($this->credentials, $this->settings);
        $this::assertInstanceOf(Client::class, $client);
    }
}