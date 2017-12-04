<?php


namespace Tests\AmoAPI\Exceptions;

use ddlzz\AmoAPI\Exceptions\ErrorCodeException;
use PHPUnit\Framework\TestCase;


/**
 * Class ErrorCodeExceptionTest
 * @package Tests\AmoAPI\Exceptions
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Exceptions\ErrorCodeException
 */
class ErrorCodeExceptionTest extends TestCase
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