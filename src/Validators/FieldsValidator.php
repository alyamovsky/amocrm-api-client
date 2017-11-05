<?php


namespace ddlzz\AmoAPI\Validators;

use ddlzz\AmoAPI\Exceptions\EntityFieldsException;


/**
 * Class FieldsValidator
 * @package ddlzz\AmoAPI\Validators
 * @author ddlzz
 */
class FieldsValidator
{
    /** @var array */
    private $fieldsParams;

    /**
     * FieldsValidator constructor.
     * @param array $fieldsParams
     */
    public function __construct(array $fieldsParams)
    {
        $this->fieldsParams = $fieldsParams;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws EntityFieldsException
     */
    public function isValid($key, $value)
    {
        // if the required field is missing
        if (empty($value) && (true === $this->fieldsParams[$key]['required_add'])) {
            throw new EntityFieldsException("The required field \"$key\" is missing or empty");
        } elseif (!empty($value)) {
            // if the field type doesn't match with the value
            switch ($this->fieldsParams[$key]['type']) {
                case 'int':
                    self::validateInt($key, $value);
                    break;
                case 'string':
                    self::validateString($key, $value);
                    break;
                case 'bool':
                    self::validateBool($key, $value);
                    break;
                case 'array':
                    self::validateArray($key, $value);
                    break;
                default:
                    throw new EntityFieldsException(
                        "Internal error: the field \"$key\" doesn't match any of the entity predefined fields"
                    );
            }
        }

        return true;
    }

    /**
     * @param string $key
     * @param int $value
     * @return bool
     * @throws EntityFieldsException
     */
    private static function validateInt($key, $value)
    {
        if (!ctype_digit($value)) {
            throw new EntityFieldsException("The field \"$key\" must contain digits only");
        }

        return true;
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     * @throws EntityFieldsException
     */
    private static function validateString($key, $value) // todo_ddlzz test this
    {
        if (!is_string($value) && !is_numeric($value)) {
            throw new EntityFieldsException("The field \"$key\" must be string");
        }

        return true;
    }

    /**
     * @param string $key
     * @param bool $value
     * @return bool
     * @throws EntityFieldsException
     */
    private static function validateBool($key, $value)
    {
        if (is_null(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            throw new EntityFieldsException("The field \"$key\" must contain boolean values only");
        }

        return true;
    }

    /**
     * @param string $key
     * @param array $value
     * @return bool
     * @throws EntityFieldsException
     */
    private static function validateArray($key, $value)
    {
        if (!is_array($value)) {
            throw new EntityFieldsException("The field \"$key\" must be an array");
        }

        return true;
    }
}