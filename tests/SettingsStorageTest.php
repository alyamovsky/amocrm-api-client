<?php


namespace Tests\AmoAPI;

use ddlzz\AmoAPI\SettingsStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class SettingsStorageTest
 * @package Tests\AmoAPI
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\SettingsStorage
 * @covers \ddlzz\AmoAPI\Validators\SettingsValidator
 */
final class SettingsStorageTest extends TestCase
{
    /** @var string */
    private $scheme = 'http';

    /** @var string */
    private $domain = 'amocrm.saas';

    /** @var string */
    private $userAgent = 'Some user agent?!';

    /** @var string */
    private $cookiePath = '/var/cookie.txt';

    /** @var array */
    private $methodsPaths = [
        'foo' => '/foo/value',
        'bar' => '/value/bar',
        'baz' => '/BAZvalue',
    ];

    /** @var SettingsStorage */
    private $settingsStorage;

    protected function setUp()
    {
        $this->settingsStorage = new SettingsStorage();
    }

    public function testGetScheme()
    {
        $this->settingsStorage->setScheme($this->scheme);
        self::assertEquals($this->scheme, $this->settingsStorage->getScheme());
    }

    /**
     * @return array
     */
    public function provideDataForTestSetScheme()
    {
        return [
            ['test123'],
            ['test://'],
            ['$test'],
            ['<test>'],
        ];
    }

    /**
     * @dataProvider provideDataForTestSetScheme
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     * @param string $scheme
     */
    public function testSetScheme($scheme)
    {
        $this->settingsStorage->setScheme($scheme);
    }

    public function testGetDomain()
    {
        $this->settingsStorage->setDomain($this->domain);
        self::assertEquals($this->domain, $this->settingsStorage->getDomain());
    }

    /**
     * @return array
     */
    public function provideDataForTestSetDomain()
    {
        return [
            ['http://amocrm.ru'],
            ['test://'],
            ['$test'],
            ['<test>'],
        ];
    }

    /**
     * @dataProvider provideDataForTestSetDomain
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     * @param string $domain
     */
    public function testSetDomain($domain)
    {
        $this->settingsStorage->setDomain($domain);
    }

    public function testGetUserAgent()
    {
        $this->settingsStorage->setUserAgent($this->userAgent);
        self::assertEquals($this->userAgent, $this->settingsStorage->getUserAgent());
    }

    /**
     * @return array
     */
    public function provideDataForTestSetUserAgent()
    {
        return [
            ['$test'],
            ['<test>'],
            ['test@test'],
        ];
    }

    /**
     * @dataProvider provideDataForTestSetUserAgent
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     * @param string $userAgent
     */
    public function testSetUserAgent($userAgent)
    {
        $this->settingsStorage->setUserAgent($userAgent);
    }

    public function testGetMethodsPaths()
    {
        $this->settingsStorage->setMethodsPaths($this->methodsPaths);
        self::assertEquals($this->methodsPaths, $this->settingsStorage->getMethodsPaths());
    }

    /**
     * @return array
     */
    public function provideDataForTestSetMethodsPaths()
    {
        return [
            [['foo' => '/$test', 'bar' => '/123']],
            [['foo' => '/test', 'bar' => '/test@test']],
            [['foo' => 'test', 'bar' => '/testtest']],
            [['foo' => '/data://foo']],
            [['foo' => '']],
            [[]],
        ];
    }

    /**
     * @dataProvider provideDataForTestSetMethodsPaths
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     * @param array $methodsPaths
     */
    public function testSetMethodsPaths($methodsPaths)
    {
        $this->settingsStorage->setMethodsPaths($methodsPaths);
    }

    public function testGetCorrectMethodPath()
    {
        $this->settingsStorage->setMethodsPaths($this->methodsPaths);
        $this::assertEquals($this->settingsStorage->getMethodsPaths()['foo'], $this->settingsStorage->getMethodPath('foo'));
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     */
    public function testGetWrongMethodPath()
    {
        $this->settingsStorage->getMethodPath('wrong_code');
    }

    public function testGetCookiePath()
    {
        $this->settingsStorage->setCookiePath($this->cookiePath);
        self::assertEquals((str_replace('tests', 'src', __DIR__ . '/..')) . $this->cookiePath, $this->settingsStorage->getCookiePath());
    }

    /**
     * @return array
     */
    public function provideDataForTestSetCookiePath()
    {
        return [
            ['var/test.txt'],
            ['/var/$test'],
            ['http://test'],
            ['/var/test@test.txt'],
        ];
    }

    /**
     * @dataProvider provideDataForTestSetCookiePath
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     * @param string $cookiePath
     */
    public function testSetCookiePath($cookiePath)
    {
        $this->settingsStorage->setCookiePath($cookiePath);
    }
}