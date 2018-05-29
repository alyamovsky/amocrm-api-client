<?php


namespace Tests\AmoAPI;

use ddlzz\AmoAPI\CredentialsManager;
use PHPUnit\Framework\TestCase;

/**
 * Class CredentialsManagerTest
 * @package Tests\AmoAPI
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\CredentialsManager
 */
final class CredentialsManagerTest extends TestCase
{
    /** @var CredentialsManager */
    private $credentialsManager;

    /** @var string */
    private $subdomain = 'test';

    /** @var string */
    private $login = 'testlogin@test.com';

    /** @var string */
    private $hash = 'b5b973a1dd4bf82c01180731acb8a615';

    protected function setUp()
    {
        $this->credentialsManager = new CredentialsManager($this->subdomain, $this->login, $this->hash);
    }

    public function testCreationFromValidParams()
    {
        self::assertInstanceOf(CredentialsManager::class, new CredentialsManager($this->subdomain, $this->login, $this->hash));
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     */
    public function testSubdomainValidationFail()
    {
        new CredentialsManager('some string', $this->login, $this->hash);
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     */
    public function testLoginValidationFail()
    {
        new CredentialsManager($this->subdomain, 'test login', $this->hash);
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     */
    public function testHashValidationFail()
    {
        new CredentialsManager($this->subdomain, $this->login, '@some $string!');
    }

    public function testGetSubdomain()
    {
        self::assertEquals($this->subdomain, $this->credentialsManager->getSubdomain());
    }
    
    public function testGetLogin()
    {
        self::assertEquals($this->login, $this->credentialsManager->getLogin());
    }

    public function testGetCredentials()
    {
        $expectedResult = ['USER_LOGIN' => $this->login, 'USER_HASH' => $this->hash];
        self::assertEquals($expectedResult, $this->credentialsManager->getCredentials());
    }
}