<?php


namespace Tests\AmoAPI\Utils;


use ddlzz\AmoAPI\Utils\StringUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class StringUtilTest
 * @package Tests\AmoAPI\Utils
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Utils\StringUtil
 */
final class StringUtilTest extends TestCase
{
    /**
     * @return array
     */
    public function provideDataForTestIsAlNumTrue()
    {
        return [
            ['Test123'],
            ['12334'],
            [42],
            ['543test'],
            [42 . 'test'],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsAlNumTrue
     * @param $value
     */
    public function testIsAlNumTrue($value)
    {
        self::assertTrue(StringUtil::isAlNum($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsAlNumFalse()
    {
        return [
            ['Test test'],
            ['4321!'],
            [['foo']],
            [true],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsAlNumFalse
     * @param $value
     */
    public function testIsAlNumFalse($value)
    {
        self::assertFalse(StringUtil::isAlNum($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsEmailTrue()
    {
        return [
            ['test@test.com'],
            ['12345@45678.test'],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsEmailTrue
     * @param $value
     */
    public function testIsEmailTrue($value)
    {
        self::assertTrue(StringUtil::isEmail($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsEmailFalse()
    {
        return [
            [true],
            [42],
            ['foo'],
            ['http://test@test.com'],
            ['mailto:test@test.com'],
            [['foo@bar.com']],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsEmailFalse
     * @param $value
     */
    public function testIsEmailFalse($value)
    {
        self::assertFalse(StringUtil::isEmail($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsDomainTrue()
    {
        return [
            ['test.com'],
            ['test.test123'],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsDomainTrue
     * @param $value
     */
    public function testIsDomainTrue($value)
    {
        self::assertTrue(StringUtil::isDomain($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsDomainFalse()
    {
        return [
            [true],
            ['https://foo.com'],
            [['test.com']],
            [null],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsDomainFalse
     * @param $value
     */
    public function testIsDomainFalse($value)
    {
        self::assertFalse(StringUtil::isDomain($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsOnlyLettersTrue()
    {
        return [
            ['test'],
            ['Test'],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsOnlyLettersTrue
     * @param $value
     */
    public function testIsOnlyLettersTrue($value)
    {
        self::assertTrue(StringUtil::isOnlyLetters($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsOnlyLettersFalse()
    {
        return [
            ['test test'],
            ['42'],
            ['test42'],
            [['test']],
            ['test!'],
            [true],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsOnlyLettersFalse
     * @param $value
     */
    public function testIsOnlyLettersFalse($value)
    {
        self::assertFalse(StringUtil::isOnlyLetters($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsTextTrue()
    {
        return [
            ['Test foo bar! 42 catDog meow; 13 baz?..'],
            ["First line\n Second line"],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsTextTrue
     * @param $value
     */
    public function testIsTextTrue($value)
    {
        self::assertTrue(StringUtil::isText($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsTextFalse()
    {
        return [
            ['Test@text'],
            [['Test text']],
            [true],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsTextFalse
     * @param $value
     */
    public function testIsTextFalse($value)
    {
        self::assertFalse(StringUtil::isText($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsUrlPathTrue()
    {
        return [
            ['/var/test.php?user=test@test.com&password=kek#header'],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsUrlPathTrue
     * @param $value
     */
    public function testIsUrlPathTrue($value)
    {
        self::assertTrue(StringUtil::isUrlPath($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsUrlPathFalse()
    {
        return [
            ['var/test'],
            ['http://test.com'],
            [['/var/test']],
            [true],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsUrlPathFalse
     * @param $value
     */
    public function testIsUrlPathFalse($value)
    {
        self::assertFalse(StringUtil::isUrlPath($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsFilePathTrue()
    {
        return [
            ['vfs://var/file.txt'],
            ['/var/file.txt'],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsFilePathTrue
     * @param $value
     */
    public function testIsFilePathTrue($value)
    {
        self::assertTrue(StringUtil::isFilePath($value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsFilePathFalse()
    {
        return [
            ['http://test.com/file.txt'],
            ['file.txt'],
            [['/var/file.txt']],
            [true],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsFilePathFalse
     * @param $value
     */
    public function testIsFilePathFalse($value)
    {
        self::assertFalse(StringUtil::isFilePath($value));
    }
}