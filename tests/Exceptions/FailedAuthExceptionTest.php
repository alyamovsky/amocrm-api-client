<?php


namespace Tests\AmoAPI\Exceptions;

use ddlzz\AmoAPI\Exceptions\FailedAuthException;
use PHPUnit\Framework\TestCase;


/**
 * Class ErrorCodeExceptionTest
 * @package Tests\AmoAPI\Exceptions
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Exceptions\FailedAuthException
 */
final class FailedAuthExceptionTest extends TestCase
{
    public function testExceptionParams()
    {
        try {
            throw new FailedAuthException('test message', 'test response');
        } catch (FailedAuthException $e) {
            self::assertEquals('test message. Server response: test response', $e->getMessage());
        }
    }
}