<?php

namespace Tests\AmoAPI;

use ddlzz\AmoAPI\SettingsStorage;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * Class SettingsStorageTest.
 *
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\SettingsStorage
 */
final class SettingsStorageTest extends TestCase
{
    /** @var string */
    private $scheme = 'http';

    /** @var string */
    private $domain = 'amocrm.saas';

    /** @var string */
    private $userAgent = 'Some user agent?!';

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
        self::assertSame($this->scheme, $this->settingsStorage->getScheme());
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
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     *
     * @param string $scheme
     */
    public function testSetIncorrectScheme($scheme)
    {
        $this->settingsStorage->setScheme($scheme);
    }

    public function testGetDomain()
    {
        $this->settingsStorage->setDomain($this->domain);
        self::assertSame($this->domain, $this->settingsStorage->getDomain());
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
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     *
     * @param string $domain
     */
    public function testSetIncorrectDomain($domain)
    {
        $this->settingsStorage->setDomain($domain);
    }

    public function testGetUserAgent()
    {
        $this->settingsStorage->setUserAgent($this->userAgent);
        self::assertSame($this->userAgent, $this->settingsStorage->getUserAgent());
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
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     *
     * @param string $userAgent
     */
    public function testSetIncorrectUserAgent($userAgent)
    {
        $this->settingsStorage->setUserAgent($userAgent);
    }

    public function testGetMethodsPaths()
    {
        $this->settingsStorage->setMethodsPaths($this->methodsPaths);
        self::assertSame($this->methodsPaths, $this->settingsStorage->getMethodsPaths());
    }

    /**
     * @return array
     */
    public function provideDataForTestSetMethodsPaths()
    {
        return [
            [['foo' => '/$test', 'bar' => '/123']],
            [['foo' => 'test', 'bar' => '/testtest']],
            [['foo' => '/data://foo']],
            [['foo' => '']],
            [[]],
        ];
    }

    /**
     * @dataProvider provideDataForTestSetMethodsPaths
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     *
     * @param array $methodsPaths
     */
    public function testSetIncorrectMethodsPaths($methodsPaths)
    {
        $this->settingsStorage->setMethodsPaths($methodsPaths);
    }

    public function testGetCorrectMethodPath()
    {
        $this->settingsStorage->setMethodsPaths($this->methodsPaths);
        self::assertSame($this->settingsStorage->getMethodsPaths()['foo'], $this->settingsStorage->getMethodPath('foo'));
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     */
    public function testGetIncorrectMethodPath()
    {
        $this->settingsStorage->getMethodPath('incorrect_code');
    }

    public function testGetCookiePath()
    {
        $vfsRoot = vfsStream::setup();
        $file = vfsStream::newFile('cookie.txt')->at($vfsRoot);

        $this->settingsStorage->setCookiePath($file->url());
        self::assertSame($file->url(), $this->settingsStorage->getCookiePath());
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
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     *
     * @param string $cookiePath
     */
    public function testSetIncorrectCookiePath($cookiePath)
    {
        $this->settingsStorage->setCookiePath($cookiePath);
    }

    /**
     * @return array
     */
    public function provideDataForTestGetMethodCodeByType()
    {
        return [
            ['lead', 'leads'],
            ['contact', 'contacts'],
            ['company', 'companies'],
            ['customer', 'customers'],
            ['task', 'tasks'],
            ['note', 'notes'],
        ];
    }

    /**
     * @dataProvider provideDataForTestGetMethodCodeByType
     *
     * @param string $key
     * @param string $value
     */
    public function testGetMethodCodeByType($key, $value)
    {
        self::assertSame($this->settingsStorage->getMethodCodeByType($key), $value);
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     */
    public function testNonexistentMethodCode()
    {
        $this->settingsStorage->getMethodCodeByType('cat');
    }
}
