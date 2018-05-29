<?php


namespace Tests\AmoAPI\Exception;

use ddlzz\AmoAPI\Exception\ErrorCodeException;
use PHPUnit\Framework\TestCase;


/**
 * Class ErrorCodeExceptionTest
 * @package Tests\AmoAPI\Exception
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Exception\ErrorCodeException
 */
final class ErrorCodeExceptionTest extends TestCase
{
    public function testExceptionParams()
    {
        try {
            throw new ErrorCodeException('test message', 'test response', 'http://test.com');
        } catch (ErrorCodeException $e) {
            self::assertEquals('test message. Server response: test response. Url: http://test.com', $e->getMessage());
        }
    }
}