<?php

namespace Tests\AmoAPI\Request;

use ddlzz\AmoAPI\Request\UrlBuilder;
use ddlzz\AmoAPI\SettingsStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class UrlBuilderTest.
 *
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Request\UrlBuilder
 */
final class UrlBuilderTest extends TestCase
{
    /** @var SettingsStorage */
    private $settings;

    /** @var string */
    private $subdomain;

    protected function setUp()
    {
        $this->settings = $this->createMock(SettingsStorage::class);
        $callback = function ($code) {return "/{$code}_method"; };
        $this->settings->method('getMethodPath')->will(self::returnCallback($callback));
        $this->settings->method('getDomain')->willReturn('test.com');
        $this->settings->method('getScheme')->willReturn('http');
        $this->subdomain = 'testsubdomain';
    }

    public function testCanBeCreated()
    {
        self::assertInstanceOf(UrlBuilder::class, new UrlBuilder($this->settings, $this->subdomain));
    }

    public function provideDataForTestPrepareMethodUrl()
    {
        return [
            ['foo', [], 'http://testsubdomain.test.com/foo_method'],
            ['bar', ['test_key' => 'test_param'], 'http://testsubdomain.test.com/bar_method?test_key=test_param'],
            ['baz', ['key1' => 'param1', 'key2' => 'param2'], 'http://testsubdomain.test.com/baz_method?key1=param1&key2=param2'],
        ];
    }

    /**
     * @dataProvider provideDataForTestPrepareMethodUrl
     *
     * @param string $code
     * @param array  $params
     * @param string $result
     */
    public function testPrepareMethodUrl($code, array $params, $result)
    {
        $builder = new UrlBuilder($this->settings, $this->subdomain);
        self::assertSame($result, $builder->buildMethodUrl($code, $params));
    }
}
