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
     * @return bool
     */
    public function isValid($key, $value)
    {
        $this->validateRequired($key, $value);

        if (null !== $value) {
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
                sprintf('Internal error: the field "%s" doesn\'t match any of the entity predefined fields', $key)
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
            if (null === $value && (true === $this->fieldsParams[$key]['required_'.$this->action])) {
                throw new EntityFieldsException(sprintf('%s error: the required field "%s" is missing or empty', ucfirst($this->action), $key));
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
            throw new EntityFieldsException(sprintf('The field "%s" must contain digits only', $key));
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
            throw new EntityFieldsException(sprintf('The field "%s" must be string', $key));
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
            throw new EntityFieldsException(sprintf('The field "%s" must contain boolean values only', $key));
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
     * @param mixed  $value
     *
     * @throws EntityFieldsException
     *
     * @return bool
     */
    private static function validateArraystring($key, $value)
    {
        if ((!is_array($value)) && (!is_string($value) && !is_numeric($value))) {
            throw new EntityFieldsException(sprintf('The field "%s" must be an array or string', $key));
        }

        return true;
    }
}
