<?php


namespace Tests\AmoAPI\Utils;


use ddlzz\AmoAPI\Utils\ArrayUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayUtilTest
 * @package Tests\AmoAPI\Utils
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Utils\ArrayUtil
 */
class ArrayUtilTest extends TestCase
{

    /**
     * @return array
     */
    public function provideDataForTestSearchAndReplace()
    {
        return [
            ['foo', 'bar', ['foo', 'foo', 'cat', 'meow'],
                ['bar', 'bar', 'cat', 'meow']],
            ['foo', 'bar', ['foo', 'foo', 'kek' => 'cat', 'meow' => ['baz', 'test' => 'cat', 'bar' => 'foo123']],
                ['bar', 'bar', 'kek' => 'cat', 'meow' => ['baz', 'bar' => 'foo123', 'test' => 'cat']]],
        ];
    }

    /**
     * @dataProvider provideDataForTestSearchAndReplace
     * @param $search
     * @param $replace
     * @param $haystack
     * @param $reference
     */
    public function testSearchAndReplace($search, $replace, $haystack, $reference)
    {
        self::assertEquals($reference, ArrayUtil::searchAndReplace($search, $replace, $haystack));
    }
}