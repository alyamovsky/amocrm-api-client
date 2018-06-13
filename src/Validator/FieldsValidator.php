<?php

namespace ddlzz\AmoAPI\Validator;

use ddlzz\AmoAPI\Exception\EntityFieldsException;

/**
 * Class FieldsValidator.
 *
 * @author ddlzz
 */
class FieldsValidator
{
    /** @var array */
    private $fieldsParams;

    /** @var string */
    private $action = '';

    /**
     * FieldsValidator constructor.
     *
     * @param array $fieldsParams
     */
    public function __construct(array $fieldsParams)
    {
        $this->fieldsParams = $fieldsParams;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @throws EntityFieldsException
     *
     * @return bool
     */
    public function isValid($key, $value)
    {
        $this->validateRequired($key, $value);

        if (isset($value)) {
            $validate = $this->prepareValidateType($this->fieldsParams[$key]['type']);
            self::$validate($key, $value);
        }

        return true;
    }

    /**
     * @param string $key
     *
     * @throws EntityFieldsException
     *
     * @return string
     */
    private function prepareValidateType($key)
    {
        $key = str_replace('|', '', $key);
        $method = 'validate'.ucfirst($key);
        if (!method_exists(self::class, $method)) {
            throw new EntityFieldsException(
                "Internal error: the field \"$key\" doesn't match any of the entity predefined fields"
            );
        }

        return $method;
    }

    /**
     * @param $key
     * @param $value
     *
     * @throws EntityFieldsException
     *
     * @return bool
     */
    private function validateRequired($key, $value)
    {
        if (('add' === $this->action) || ('update' === $this->action)) {
            if (!isset($value) && (true === $this->fieldsParams[$key]['required_'.$this->action])) {
                throw new EntityFieldsException(ucfirst($this->action)." error: the required field \"$key\" is missing or empty");
            }
        }

        return true;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */

    /**
     * @param string $key
     * @param int    $value
     *
     * @throws EntityFieldsException
     *
     * @return bool
     */
    private static function validateInt($key, $value)
    {
        if (!is_int($value) || !preg_match('/^\d+$/', (string) $value)) {
            throw new EntityFieldsException("The field \"$key\" must contain digits only");
        }

        return true;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */

    /**
     * @param string $key
     * @param string $value
     *
     * @throws EntityFieldsException
     *
     * @return bool
     */
    private static function validateString($key, $value)
    {
        if (!is_string($value) && !is_numeric($value)) {
            throw new EntityFieldsException("The field \"$key\" must be string");
        }

        return true;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */

    /**
     * @param string $key
     * @param bool   $value
     *
     * @throws EntityFieldsException
     *
     * @return bool
     */
    private static function validateBool($key, $value)
    {
        if (null === filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
            throw new EntityFieldsException("The field \"$key\" must contain boolean values only");
        }

        return true;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */

    /**
     * @param string $key
     * @param array  $value
     *
     * @throws EntityFieldsException
     *
     * @return bool
     */
    private static function validateArray($key, $value)
    {
        if (!is_array($value)) {
            throw new EntityFieldsException("The field \"$key\" must be an array");
        }

        return true;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */

    /**
     * Because some fields must be either strings during entity creation or arrays during it's obtaining from server,
     * we create this check.
     *
     * @param string $key
     * @param array  $value
     *
     * @throws EntityFieldsException
     *
     * @return bool
     */
    private static function validateArraystring($key, $value)
    {
        if ((!is_array($value)) && (!is_string($value) && !is_numeric($value))) {
            throw new EntityFieldsException("The field \"$key\" must be an array or string");
        }

        return true;
    }
}
