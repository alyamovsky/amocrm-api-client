<?php

namespace ddlzz\AmoAPI\Model\Amo;

use ddlzz\AmoAPI\Model\AbstractModel;
use ddlzz\AmoAPI\Model\ModelInterface;

/**
 * Class Customer.
 *
 * @author ddlzz
 */
final class Customer extends AbstractModel implements ModelInterface
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

    /** @var array */
    protected $aliasesAppend = [
        'responsible_user_id' => 'main_user_id',
        'updated_at' => 'date_modify',
        'is_deleted' => 'deleted',
        'main_contact' => 'main_contact_id',
        'closest_task_at' => 'last_task_date',
    ];
}
