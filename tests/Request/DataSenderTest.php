<?php


namespace Tests\AmoAPI\Request;


use ddlzz\AmoAPI\Request\Curl;
use ddlzz\AmoAPI\Request\DataSender;
use ddlzz\AmoAPI\SettingsStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class DataSenderTest
 * @package Tests\AmoAPI\Request
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Request\DataSender
 */
class DataSenderTest extends TestCase
{
    /** @var SettingsStorage */
    private $settings;

    /** @var Curl */
    private $curl;

    protected function setUp()
    {
        $this->settings = new SettingsStorage();
        $this->curl = $this->createMock(Curl::class);
        $this->curl->method('exec')->willReturn('send ok');
    }

    public function testCanBeCreatedWithValidParams()
    {
        self::assertInstanceOf(DataSender::class, new DataSender($this->curl, $this->settings));
    }

    public function testSend()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->curl->method('getHttpCode')->willReturn(200);

        $dataSender = new DataSender($this->curl, $this->settings);
        self::assertEquals($this->curl->exec(), $dataSender->send('http://catdog.test', ['foo']));
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exceptions\FailedAuthException
     */
    public function testFailedAuth()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->curl->method('getHttpCode')->willReturn(401);

        $dataSender = new DataSender($this->curl, $this->settings);
        $dataSender->send('http://catdog.test', ['foo']);
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exceptions\ErrorCodeException
     */
    public function testHttpError()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->curl->method('getHttpCode')->willReturn(500);

        $dataSender = new DataSender($this->curl, $this->settings);
        $dataSender->send('http://catdog.test', ['foo']);
    }
}