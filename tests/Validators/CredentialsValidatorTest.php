<?php


namespace Tests\AmoAPI\Validators;


use ddlzz\AmoAPI\Validators\CredentialsValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class CredentialsValidatorTest
 * @package Tests\AmoAPI\Validators
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Validators\CredentialsValidator
 */
final class CredentialsValidatorTest extends TestCase
{
    /** @var CredentialsValidator */
    private $validator;

    protected function setUp()
    {
        $this->validator = new CredentialsValidator();
    }

    public function testValidateCorrectSubdomain()
    {
        self::assertTrue($this->validator->validateSubdomain('testsubdomain'));
    }

    public function provideDataForTestValidateIncorrectSubdomain()
    {
        return [
            ['test subdomain'],
            ['http://subdomain'],
            ['subdomain!'],
            ['subdomain@test.com'],
        ];
    }

    /**
     * @dataProvider provideDataForTestValidateIncorrectSubdomain
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     * @param $subdomain
     */
    public function testValidateIncorrectSubdomain($subdomain)
    {
        $this->validator->validateSubdomain($subdomain);
    }

    public function testValidateCorrectLogin()
    {
        self::assertTrue($this->validator->validateLogin('test@test.test'));
    }

    public function provideDataForTestValidateIncorrectLogin()
    {
        return [
            ['test login@test.com'],
            ['http://login'],
            ['login'],
            ['login!'],
            ['@test.com'],
        ];
    }

    /**
     * @dataProvider provideDataForTestValidateIncorrectLogin
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     * @param $login
     */
    public function testValidateIncorrectLogin($login)
    {
        $this->validator->validateLogin($login);
    }

    public function testValidateCorrectHash()
    {
        self::assertTrue($this->validator->validateHash(md5('test')));
    }

    public function provideDataForTestValidateIncorrectHash()
    {
        return [
            ['test Hash@test.com'],
            ['http://Hash'],
            ['Hash!'],
            ['@test.com'],
        ];
    }

    /**
     * @dataProvider provideDataForTestValidateIncorrectHash
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     * @param $Hash
     */
    public function testValidateIncorrectHash($Hash)
    {
        $this->validator->validateHash($Hash);
    }
}