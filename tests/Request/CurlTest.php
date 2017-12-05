<?php


namespace Tests\AmoAPI\Request;


use ddlzz\AmoAPI\Request\Curl;
use ddlzz\AmoAPI\SettingsStorage;
use phpmock\functions\FixedValueFunction;
use phpmock\Mock;
use PHPUnit\Framework\TestCase;
use phpmock\MockBuilder;

/**
 * Class CurlTest
 * @package Tests\AmoAPI\Request
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Request\Curl
 */
final class CurlTest extends TestCase
{
    /** @var SettingsStorage */
    private $settings;

    /** @var Curl */
    private $curl;

    /** @var string */
    private $url = 'http://catdog.test';

    /** @var MockBuilder */
    private $mockBuilder;

    protected function setUp()
    {
        $this->settings = new SettingsStorage();
        $this->curl = new Curl();
        $this->mockBuilder = new MockBuilder();
    }

    /**
     * It doesn't really belong here but it may be useful to detect possible tests failures
     */
    public function testCurlExtensionIsLoaded()
    {
        self::assertTrue(extension_loaded('curl'));
    }

    public function testInit()
    {
        $this->curl->init();
        self::assertTrue(is_resource($this->curl->getResource()));
        $this->curl->close();
    }

    public function testClose()
    {
        $this->curl->init();
        $this->curl->close();
        self::assertFalse(is_resource($this->curl));
    }

    public function testDestructor()
    {
        $curl1 = new Curl();
        $curl1->init();
        unset($curl);
        $curl2 = new Curl();
        self::assertTrue(null === $curl2->getResource());
    }

    public function testExec()
    {
        $this->curl->init();
        $this->curl->setUrl($this->url);
        $this->curl->setReturnTransfer(true);

        $output = 'Cats and dogs are friends!';

        $this->mockBuilder->setNamespace($this->settings::NAMESPACE_PREFIX . '\Request')
            ->setName("curl_exec")
            ->setFunctionProvider(new FixedValueFunction($output));

        $mock = $this->mockBuilder->build();
        $mock->enable();
        self::assertEquals($output, $this->curl->exec());
        $mock->disable();

        $this->curl->close();
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exceptions\CurlException
     * @expectedExceptionMessageRegExp ~.*not properly initialized.*~
     */
    public function testExecFail()
    {
        $this->curl->exec();
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exceptions\CurlException
     */
    public function testSetOptFail()
    {
        $this->curl->setUrl($this->url);
    }

    public function testSetUrl()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setUrl($this->url));
        $this->curl->close();
    }

    public function testSetReturnTransfer()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setReturnTransfer(true));
        $this->curl->close();
    }

    public function testSetUserAgent()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setUserAgent('foo'));
        $this->curl->close();
    }

    public function testSetHttpHeader()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setHttpHeader(['foo: bar']));
        $this->curl->close();
    }

    public function testSetHeader()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setHeader(true));
        $this->curl->close();
    }

    public function testSetCookieFile()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setCookieFile('foo.txt'));
        $this->curl->close();
    }

    public function testSetCookieJar()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setCookieJar('foo.txt'));
        $this->curl->close();
    }

    public function testSetSSLVerifyPeer()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setSSLVerifyPeer(true));
        $this->curl->close();
    }

    public function testSetSSLVerifyHost()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setSSLVerifyHost(2));
        $this->curl->close();
    }

    public function testSetCustomRequest()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setCustomRequest('POST'));
        $this->curl->close();
    }

    public function testSetPostFields()
    {
        $this->curl->init();
        self::assertTrue($this->curl->setPostFields('{"foobar":"42"}'));
        $this->curl->close();
    }

    public function testGetHttpCode()
    {
        $this->curl->init();
        $this->curl->setUrl($this->url);
        $this->curl->setReturnTransfer(true);
        $this->curl->exec();

        $this->mockBuilder->setNamespace($this->settings::NAMESPACE_PREFIX . '\Request')
            ->setName("curl_getinfo")
            ->setFunctionProvider(new FixedValueFunction(200));

        $mock = $this->mockBuilder->build();
        $mock->enable();
        self::assertEquals(200, $this->curl->getHttpCode());
        $mock->disable();

        $this->curl->close();
    }

    protected function tearDown()
    {
        Mock::disableAll();
    }
}