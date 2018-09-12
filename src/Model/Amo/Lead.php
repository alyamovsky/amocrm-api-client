<?php

namespace ddlzz\AmoAPI\Model\Amo;

use ddlzz\AmoAPI\Model\AbstractModel;
use ddlzz\AmoAPI\Model\ModelInterface;

/**
 * Class Lead.
 *
 * @author ddlzz
 */
final class Lead extends AbstractModel implements ModelInterface
{
    /** @var string */
    protected $requestName = 'leads';

    /** @var array */
    protected $fieldsParamsAppend = [
        'name' => [
            'type' => 'string',
            'required_add' => true,
            'required_update' => false,
        ],
        'is_deleted' => [
            'type' => 'bool',
            'required_add' => false,
            'required_update' => false,
        ],
        'main_contact' => [
            'type' => 'array|string', // [id => int, _links => array]
            'required_add' => false,
            'required_update' => false,
        ],
        'group_id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'company' => [
            'type' => 'array', // [id => int, name => string, _links => array]
            'required_add' => false,
            'required_update' => false,
        ],
        'company_id' => [
            'type' => 'array|string', // [id => int, name => string, _links => array]
            'required_add' => false,
            'required_update' => false,
        ],
        'closed_at' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'closest_task_at' => [
            'type' => 'int',
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
        'status_id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'sale' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'contacts' => [
            'type' => 'array', // [id => int, id => int]
            'required_add' => false,
            'required_update' => false,
        ],
        'contacts_id' => [
            'type' => 'array|string', // [id => int, id => int]
            'required_add' => false,
            'required_update' => false,
        ],
        'pipeline' => [
            'type' => 'array', // [id => int, _links => array]
            'required_add' => false,
            'required_update' => false,
        ],
        'pipeline_id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
    ];

    /** @var array */
    protected $aliasesAppend = [
        'is_deleted' => 'deleted',
        'main_contact' => 'main_contact_id',
        'company' => 'linked_company_id',
        'closed_at' => 'date_close',
        'closest_task_at' => 'closest_task',
        'sale' => 'price',
    ];
}
