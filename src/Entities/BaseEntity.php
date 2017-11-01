<?php


namespace ddlzz\AmoAPI\Entities;

use ddlzz\AmoAPI\Exceptions\EntityFieldsException;
use ddlzz\AmoAPI\Utils\ArrayUtil;

/**
 * Class BaseEntity
 * @package ddlzz\AmoAPI\Entities
 * @author ddlzz
 */
abstract class BaseEntity implements \ArrayAccess, EntityInterface
{
    /** Constants for declaring fields type. */
    const INT = 'int';
    const STRING = 'string';
    const BOOL = 'bool';
    const ARRAY = 'array';

    /** This value will be overwritten in constructor. */
    const CURRENT_TIME = 'current_time';

    /** @var string */
    protected $requestName = '';

    /**
     * Data container for an ArrayAccess interface implementation.
     * @var array
     */
    protected $container = [];

    /**
     * Fields parameters for validation purposes. These are fields that are used in every entity.
     * @var array
     */
    protected $fieldsParams = [
        'id' => [
            'type' => self::INT,
            'required_add' => false,
            'required_update' => true,
            'alias' => null,
            'default' => null,
        ],
        'name' => [
            'type' => self::STRING,
            'required_add' => true,
            'required_update' => false,
            'alias' => null,
            'default' => null,
        ],
        'created_at' => [
            'type' => self::INT,
            'required_add' => true,
            'required_update' => false,
            'alias' => 'date_create',
            'default' => self::CURRENT_TIME,
        ],
        'updated_at' => [
            'type' => self::INT,
            'required_add' => false,
            'required_update' => true,
            'alias' => 'last_modified',
            'default' => self::CURRENT_TIME,
        ],
    ];

    /**
     * @var array
     */
    protected $fieldsParamsAppend = [];

    /**
     * EntityInterface data goes here
     * @var array
     */
    protected $fields = [];

    /**
     * BaseEntity constructor.
     */
    public function __construct()
    {
        // Because PHP doesn't support expressions as a default value for fields, we will evaluate them ourselves.
        $this->fieldsParams = ArrayUtil::searchAndReplace(self::CURRENT_TIME, time(), $this->fieldsParams);

        // Append fields of abstract parent class with the child entity data.
        $this->fieldsParams = array_merge($this->fieldsParams, $this->fieldsParamsAppend);
    }

    /**
     * @param array $data
     * @return array
     * @throws EntityFieldsException
     */
    private static function validateDataBeforeSet(array $data)
    {
        if (empty($data)) {
            throw new EntityFieldsException('Data is empty');
        }

        if (count(array_filter(array_keys($data), 'is_string')) < 1) {
            $message = sprintf('Data is not an associative array: "%s"', var_export($data, true));
            throw new EntityFieldsException($message);
        }

        return $data;
    }

    /**
     * @param string $action
     * @return void
     */
    public function setFieldsParams($action)
    {
        $data = self::validateDataBeforeSet($this->container);

        foreach ($this->fieldsParams as $key => $params) {
            $fieldData = isset($data[$key]) ? $data[$key] : null;
            if (($this->isValidatedField($key, $fieldData)) && (!empty($fieldData))) {
                $this->setField($key, $fieldData);
            }
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws EntityFieldsException
     */
    private function isValidatedField($key, $value)
    {
        // if the required field is missing
        if (empty($value) && (true === $this->fieldsParams[$key]['required_add'])) {
            throw new EntityFieldsException("The required field \"$key\" is missing or empty");
        } elseif (!empty($value)) {
            // if the field type doesn't match with the value
            switch ($this->fieldsParams[$key]['type']) {
                case self::INT:
                    if (!ctype_digit($value)) {
                        throw new EntityFieldsException("The field \"$key\" must contain digits only");
                    }
                    break;
                case self::STRING:
                    if (!is_string($value) && !is_numeric($value)) {
                        throw new EntityFieldsException("The field \"$key\" must be string");
                    }
                    break;
                case self::BOOL:
                    if (is_null(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
                        throw new EntityFieldsException("The field \"$key\" must contain boolean values only");
                    }
                    break;
                case self::ARRAY:
                    if (!is_array($value)) {
                        throw new EntityFieldsException("The field \"$key\" must be an array");
                    }
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
     * @param mixed $value
     */
    private function setField($key, $value)
    {
        switch ($this->fieldsParams[$key]['type']) {
            case self::INT:
                $value = (int)$value;
                break;
            case self::STRING:
                $value = (string)$value;
                break;
            case self::BOOL:
                $value = (bool)$value;
                break;
        }

        $this->fields[$key] = $value;
    }

    /**
     * @return string
     */
    public function getRequestName()
    {
        return $this->requestName;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    //////////////////////////////////////////////
    // The implementation of ArrayAccess interface
    //////////////////////////////////////////////

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->fields[$offset]) ? $this->fields[$offset] : null;
    }
}