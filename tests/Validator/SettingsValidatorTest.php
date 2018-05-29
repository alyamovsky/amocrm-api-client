<?php


namespace Tests\AmoAPI\Validator;


use ddlzz\AmoAPI\Validator\SettingsValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class SettingsValidatorTest
 * @package Tests\AmoAPI\Validator
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Validator\SettingsValidator
 */
final class SettingsValidatorTest extends TestCase
{
    /** @var SettingsValidator */
    private $validator;

    protected function setUp()
    {
        $this->validator = new SettingsValidator();
    }

    public function testValidateScheme()
    {
        self::assertTrue($this->validator->validateScheme('ftp'));
    }

    public function provideDataForTestValidateIncorrectScheme()
    {
        return [
            ['http://test.com?key=param&key2=param'],
            ['test@test.com'],
            [true],
        ];
    }

    /**
     * @dataProvider provideDataForTestValidateIncorrectScheme
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     * @param string $scheme
     */
    public function testValidateIncorrectScheme($scheme)
    {
        $this->validator->validateScheme($scheme);
    }

    public function testValidateDomain()
    {
        self::assertTrue($this->validator->validateDomain('test.com'));
    }

    public function provideDataForTestValidateIncorrectDomain()
    {
        return [
            ['http://test.com?key=param&key2=param'],
            ['test@test.com'],
            [true],
        ];
    }

    /**
     * @dataProvider provideDataForTestValidateIncorrectDomain
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     * @param string $domain
     */
    public function testValidateIncorrectDomain($domain)
    {
        $this->validator->validateDomain($domain);
    }

    public function testValidateUserAgent()
    {
        self::assertTrue($this->validator->validateUserAgent('Test user agent123.'));
    }

    public function provideDataForTestValidateIncorrectUserAgent()
    {
        return [
            ['http://test.com?key=param&key2=param'],
            ['test@test.com'],
            [true],
        ];
    }

    /**
     * @dataProvider provideDataForTestValidateIncorrectUserAgent
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     * @param string $agent
     */
    public function testValidateIncorrectUserAgent($agent)
    {
        $this->validator->validateUserAgent($agent);
    }

    public function testValidateCookiePath()
    {
        self::assertTrue($this->validator->validateCookiePath('/var/cookie.txt'));
    }

    public function provideDataForTestValidateIncorrectCookiePath()
    {
        return [
            ['http://test.com?key=param&key2=param'],
            ['test@test.com'],
            [true],
        ];
    }

    /**
     * @dataProvider provideDataForTestValidateIncorrectCookiePath
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     * @param string $path
     */
    public function testValidateIncorrectCookiePath($path)
    {
        $this->validator->validateCookiePath($path);
    }

    public function testValidateMethodPaths()
    {
        $paths = [
            'foo' => '/test1',
            'bar' => '/test2',
        ];

        self::assertTrue($this->validator->validateMethodsPaths($paths));
    }

    public function provideDataForTestValidateIncorrectMethodsPaths()
    {
        return [
            [[]],
            [['foo' => '']],
            [['foo' => 'bar']],
        ];
    }

    /**
     * @dataProvider provideDataForTestValidateIncorrectMethodsPaths
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     * @param array $paths
     */
    public function testValidateIncorrectMethodsPaths(array $paths)
    {
        $this->validator->validateMethodsPaths($paths);
    }
}