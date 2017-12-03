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
class CurlTest extends TestCase
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
        $this::assertTrue(extension_loaded('curl'));
    }

    public function testInit()
    {
        $this->curl->init();
        $this::assertTrue(is_resource($this->curl->getResource()));
        $this->curl->close();
    }

    public function testClose()
    {
        $this->curl->init();
        $this->curl->close();
        $this::assertFalse(is_resource($this->curl));
    }

    public function testDestructor()
    {
        $curl = new Curl();
        $curl->init();
        $handle = $curl->getResource();
        unset($curl);
        $this::assertFalse('curl' === get_resource_type($handle));
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
        $this::assertEquals($output, $this->curl->exec());
        $mock->disable();

        $this->curl->close();
    }

    /**
     * @expectedException \ddlzz\amoAPI\Exceptions\CurlException
     * @expectedExceptionMessage Curl class is not properly initialized
     */
    public function testExecFail()
    {
        $this->curl->exec();
    }

    /**
     * @expectedException \ddlzz\amoAPI\Exceptions\CurlException
     */
    public function testSetOptFail()
    {
        $this->curl->setUrl('http://127.0.0.1');
    }

    public function testSetUrl()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setUrl('http://127.0.0.1'));
        $this->curl->close();
    }

    public function testSetReturnTransfer()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setReturnTransfer(true));
        $this->curl->close();
    }

    public function testSetUserAgent()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setUserAgent('foo'));
        $this->curl->close();
    }

    public function testSetHttpHeader()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setHttpHeader(['foo: bar']));
        $this->curl->close();
    }

    public function testSetHeader()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setHeader(true));
        $this->curl->close();
    }

    public function testSetCookieFile()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setCookieFile('foo.txt'));
        $this->curl->close();
    }

    public function testSetCookieJar()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setCookieJar('foo.txt'));
        $this->curl->close();
    }

    public function testSetSSLVerifyPeer()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setSSLVerifyPeer(true));
        $this->curl->close();
    }

    public function testSetSSLVerifyHost()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setSSLVerifyHost(2));
        $this->curl->close();
    }

    public function testSetCustomRequest()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setCustomRequest('POST'));
        $this->curl->close();
    }

    public function testSetPostFields()
    {
        $this->curl->init();
        $this::assertTrue($this->curl->setPostFields('{"foobar":"42"}'));
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
        $this::assertEquals(200, $this->curl->getHttpCode());
        $mock->disable();

        $this->curl->close();
    }

    protected function tearDown()
    {
        Mock::disableAll();
    }
}