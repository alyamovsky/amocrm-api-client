<?php


namespace ddlzz\AmoAPI\Entities;

use ddlzz\AmoAPI\Exceptions\EntityFieldsException;
use ddlzz\AmoAPI\Exceptions\InvalidArgumentException;
use ddlzz\AmoAPI\Utils\ArrayUtil;
use ddlzz\AmoAPI\Validators\FieldsValidator;

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

    /** @var FieldsValidator */
    protected $fieldsValidator;

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

        $this->fieldsValidator = new FieldsValidator($this->fieldsParams); // Composition
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
     * @throws InvalidArgumentException
     */
    public function setFieldsParams($action)
    {
        if (('add' !== $action) && ('update' !== $action) && ('fill' !== $action)) {
            throw new InvalidArgumentException("Action \"$action\" is not a proper action parameter");
        }

        $data = self::validateDataBeforeSet($this->container);

        foreach ($this->fieldsParams as $key => $params) {
            $fieldData = isset($data[$key]) ? $data[$key] : null;
            if (($this->fieldsValidator->isValid($key, $fieldData)) && (!empty($fieldData))) {
                $this->setField($key, $fieldData);
            }
        }
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

    /**
     * @param array $data
     * @return EntityInterface $this
     */
    public function fill(array $data)
    {
        $this->container = $data;
        $this->setFieldsParams('fill');
        return $this;
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