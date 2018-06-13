<?php

namespace Tests\AmoAPI\Exception;

use ddlzz\AmoAPI\Exception\FailedAuthException;
use PHPUnit\Framework\TestCase;

/**
 * Class ErrorCodeExceptionTest.
 *
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Exception\FailedAuthException
 */
final class FailedAuthExceptionTest extends TestCase
{
    public function testExceptionParams()
    {
        try {
            throw new FailedAuthException('test message', 'test response');
        } catch (FailedAuthException $e) {
            self::assertSame('test message. Server response: test response', $e->getMessage());
        }
    }
}
