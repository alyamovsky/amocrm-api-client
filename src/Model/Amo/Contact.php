<?php


namespace ddlzz\AmoAPI\Model\Amo;


use ddlzz\AmoAPI\Model\AbstractModel;
use ddlzz\AmoAPI\Model\ModelInterface;

/**
 * Class Contact
 * @package ddlzz\AmoAPI\Model\Amo
 * @author ddlzz
 */
final class Contact extends AbstractModel implements ModelInterface
{
    /** @var string */
    protected $requestName = 'contacts';

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
            'type' => 'array|string',
            'required_add' => false,
            'required_update' => false,
        ],
        'company_name' => [
            'type' => 'string',
            'required_add' => false,
            'required_update' => false,
        ],
        'leads' => [
            'type' => 'array',
            'required_add' => false,
            'required_update' => false,
        ],
        'leads_id' => [
            'type' => 'array|string',
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
        'customers' => [
            'type' => 'array',
            'required_add' => false,
            'required_update' => false,
        ],
        'customers_id' => [
            'type' => 'array|string',
            'required_add' => false,
            'required_update' => false,
        ],
    ];

    /** @var array */
    protected $aliasesAppend = [
        'updated_by' => 'modified_user_id',
        'company_id' => 'linked_company_id',
        'leads_id' => 'linked_leads_id',
        'closest_task_at' => 'closest_task',
    ];
}