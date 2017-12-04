<?php


namespace Tests\AmoAPI\Validators;


use ddlzz\AmoAPI\Validators\FieldsValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldsValidatorTest
 * @package Tests\AmoAPI\Validators
 * @author ddlzz
 * @covers \ddlzz\amoAPI\Validators\FieldsValidator
 */
class FieldsValidatorTest extends TestCase
{
    /** @var array */
    private $fieldsParams = [
        'int_param' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'string_param' => [
            'type' => 'string',
            'required_add' => false,
            'required_update' => false,
        ],
        'bool_param' => [
            'type' => 'bool',
            'required_add' => false,
            'required_update' => false,
        ],
        'array_param' => [
            'type' => 'array',
            'required_add' => false,
            'required_update' => false,
        ],
        'array_string_param' => [
            'type' => 'array|string',
            'required_add' => false,
            'required_update' => false,
        ],
        'required_add_param' => [
            'type' => 'int',
            'required_add' => true,
            'required_update' => false,
        ],
        'required_update_param' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => true,
        ],
    ];

    /**
     * @return array
     */
    public function provideDataForTestIsValid()
    {
        return [
            ['int_param', 123],
            ['string_param', 'foo'],
            ['string_param', 'bar123 !@#$%^&*()_+='],
            ['bool_param', true],
            ['bool_param', false],
            ['array_param', ['foo', 'bar']],
            ['array_param', ['foo' => 'bar', 'baz' => 'meow']],
            ['array_string_param', 'foo'],
            ['array_string_param', []],
            ['array_string_param', ['foo', 'bar']],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsValid
     * @param string $key
     * @param mixed $value
     */
    public function testIsValid($key, $value)
    {
        $validator = new FieldsValidator($this->fieldsParams);
        self::assertTrue($validator->isValid($key, $value));
    }

    /**
     * @return array
     */
    public function provideDataForTestIsValidException()
    {
        return [
            ['int_param', -123],
            ['int_param', 'foo'],
            ['int_param', []],
            ['int_param', ['foo']],
            ['int_param', true],
            ['int_param', false],
            ['string_param', ['foo']],
            ['string_param', []],
            ['string_param', false],
            ['bool_param', 123],
            ['bool_param', 'foo'],
            ['bool_param', []],
            ['bool_param', ['foo']],
            ['array_param', 123],
            ['array_param', 'foo'],
            ['array_param', true],
            ['array_string_param', true],
            ['array_string_param', false],
        ];
    }

    /**
     * @dataProvider provideDataForTestIsValidException
     * @param string $key
     * @param mixed $value
     * @expectedException \ddlzz\AmoAPI\Exceptions\EntityFieldsException
     */
    public function testIsValidException($key, $value)
    {
        $validator = new FieldsValidator($this->fieldsParams);
        $validator->isValid($key, $value);
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exceptions\EntityFieldsException
     */
    public function testIsValidIncorrectType()
    {
        $params = ['incorrect_param' => ['type' => 'foo']];
        $key = 'incorrect_param';
        $value = 'bar';

        $validator = new FieldsValidator($params);
        foreach (['add', 'update'] as $action) {
            $validator->setAction($action);
            $validator->isValid($key, $value);
        }
    }

    /**
     * @return array
     */
    public function provideDataForTestValidateRequired()
    {
        return [
            ['required_add_param', 42],
            ['required_update_param', 123],
        ];
    }

    /**
     * @dataProvider provideDataForTestValidateRequired
     * @param string $key
     * @param mixed $value
     */
    public function testValidateRequired($key, $value)
    {
        $validator = new FieldsValidator($this->fieldsParams);
        foreach (['add', 'update'] as $action) {
            $validator->setAction($action);
            self::assertTrue($validator->isValid($key, $value));
        }
    }

    /**
     * @return array
     */
    public function provideDataForTestValidateRequiredException()
    {
        return [
            ['required_add_param', null],
            ['required_update_param', null],
        ];
    }

    /**
     * @dataProvider provideDataForTestValidateRequiredException
     * @param string $key
     * @param mixed $value
     * @expectedException \ddlzz\AmoAPI\Exceptions\EntityFieldsException
     */
    public function testValidateRequiredException($key, $value)
    {
        $validator = new FieldsValidator($this->fieldsParams);
        foreach (['add', 'update'] as $action) {
            $validator->setAction($action);
            $validator->isValid($key, $value);
        }
    }
}