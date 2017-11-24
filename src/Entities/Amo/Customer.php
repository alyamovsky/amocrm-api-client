<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Customer
 * @package ddlzz\AmoAPI\Entities\Amo
 * @author ddlzz
 */
final class Customer extends BaseEntity implements EntityInterface
{
    /** @var string */
    protected $requestName = 'customers';

    /** @var array */
    protected $fieldsParamsAppend = [
        'name' => [
            'type' => 'string',
            'required_add' => true,
            'required_update' => false,
        ],
        'updated_by' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'is_deleted' => [
            'type' => 'bool',
            'required_add' => false,
            'required_update' => false,
        ],
        'main_contact' => [
            'type' => 'array', // [id => int, _links => array]
            'required_add' => false,
            'required_update' => false,
        ],
        'tags' => [
            'type' => 'array|string', // [[id => int, name => string], [id => int, name => string]]
            'required_add' => false,
            'required_update' => false,
        ],
        'custom_fields' => [
            'type' => 'array', // [[id => int, name => string, values => [value => string?, enum => string?]], ...]
            'required_add' => false,
            'required_update' => false,
        ],
        'next_price' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'closest_task_at' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'period_id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'periodicity' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'next_date' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'contacts' => [
            'type' => 'array', // [id => int, id => int]
            'required_add' => false,
            'required_update' => false,
        ],
        'company' => [
            'type' => 'array', // [id => int, name => string, _links => array]
            'required_add' => false,
            'required_update' => false,
        ],
    ];
}