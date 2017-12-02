<?php


namespace Tests\AmoAPI\Request;


use ddlzz\AmoAPI\Request\Curl;
use PHPUnit\Framework\TestCase;

/**
 * Class CurlTest
 * @package Tests\AmoAPI\Request
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Request\Curl
 */
class CurlTest extends TestCase
{
    /** @var Curl */
    private $curl;

    protected function setUp()
    {
        $this->curl = new Curl();
    }

    public function testExtensionIsLoaded()
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

    /**
     * @expectedException \ddlzz\amoAPI\Exceptions\CurlException
     * @expectedExceptionMessage Curl class was not properly closed
     */
    public function testDestructor()
    {
        $testCurl = new Curl();
        $testCurl->init();
        unset($testCurl);
    }

    public function testExec()
    {
        $this->curl->init();
        $this->curl->setUrl('file://' . __FILE__);
        $this->curl->setReturnTransfer(true);
        $this::assertStringEqualsFile(__FILE__, $this->curl->exec());
        $this->curl->close();
    }
}