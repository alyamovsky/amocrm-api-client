<?php


namespace ddlzz\AmoAPI\Entities;

use ddlzz\AmoAPI\Exceptions\EntityFieldsException;
use ddlzz\AmoAPI\Exceptions\InvalidArgumentException;
use ddlzz\AmoAPI\Validators\FieldsValidator;

/**
 * Class BaseEntity
 * @package ddlzz\AmoAPI\Entities
 * @author ddlzz
 */
abstract class BaseEntity implements \ArrayAccess
{
    /** @var FieldsValidator */
    private $fieldsValidator;

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
    private $fieldsParams = [
        'id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => true,
        ],
        'responsible_user_id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'created_by' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'created_at' => [
            'type' => 'int',
            'required_add' => true,
            'required_update' => false,
        ],
        'updated_at' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => true,
        ],
        'account_id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
    ];

    /** @var array */
    protected $fieldsParamsAppend = [];

    /** @var array */
    private $aliases = [
        'created_at' => 'date_create',
        'updated_at' => 'last_modified',
        'created_by' => 'created_user_id',

    ];

    /** @var array */
    protected $aliasesAppend = [];

    /**
     * Entity data goes here
     * @var array
     */
    private $fields = [];

    /**
     * BaseEntity constructor.
     */
    public function __construct()
    {
        // Append fields and aliases of abstract parent class with the child entity data.
        $this->fieldsParams = array_merge($this->fieldsParams, $this->fieldsParamsAppend);
        $this->aliases = array_merge($this->aliases, $this->aliasesAppend);

        $this->fieldsValidator = new FieldsValidator($this->fieldsParams); // Composition
    }

    /**
     * @param string $action
     * @return void
     * @throws InvalidArgumentException
     * @throws EntityFieldsException
     */
    public function setFields($action)
    {
        if (('add' !== $action) && ('update' !== $action) && ('fill' !== $action)) {
            throw new InvalidArgumentException("Action \"$action\" is not a proper action parameter");
        }

        self::validateDataBeforeSet($this->container);

        if (!isset($this->container['created_at']) && !isset($this->container['date_create'])) {
            $this->setCreatedAt();
        }

        $this->container = $this->renameAliases($this->container);

        $this->fieldsValidator->setAction($action);

        foreach ($this->fieldsParams as $key => $params) {
            $fieldData = isset($this->container[$key]) ? $this->container[$key] : null;
            if (($this->fieldsValidator->isValid($key, $fieldData)) && (!empty($fieldData))) {
                $this->setField($key, $fieldData);
            }
        }
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
     * @return BaseEntity
     * @throws EntityFieldsException
     * @throws InvalidArgumentException
     */
    public function fill(array $data)
    {
        $this->container = $data;
        $this->setFields('fill');
        return $this;
    }

    /**
     * The updated_at field must be greater than the existing one, so
     * we update it automatically if none was passed by user.
     */
    public function setUpdatedAt()
    {
        if ((isset($this->fields['updated_at'])) && ($this->container['updated_at'] === $this->fields['updated_at'])) {
            $this->container['updated_at'] = time();
        }
    }

    private function setCreatedAt()
    {
        $this->container['created_at'] = time();
    }

    /**
     * @param array $data
     * @return bool
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

        return true;
    }

    /**
     * @param array $data
     * @return array
     */
    private function renameAliases(array $data)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->aliases)) {
                $newKey = array_search($key, $this->aliases);
                $data[$newKey] = $data[$key];
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    private function setField($key, $value)
    {
        switch ($this->fieldsParams[$key]['type']) {
            case 'int':
                $value = (int)$value;
                break;
            case 'string':
                $value = (string)$value;
                break;
            case 'bool':
                $value = (bool)$value;
                break;
        }

        $this->fields[$key] = $value;
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