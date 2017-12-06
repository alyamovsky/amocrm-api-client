<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Company
 * @package ddlzz\AmoAPI\Entities\Amo
 * @author ddlzz
 */
final class Company extends BaseEntity implements EntityInterface
{
    /** @var string */
    protected $requestName = 'companies';


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
        'contacts' => [
            'type' => 'array',
            'required_add' => false,
            'required_update' => false,
        ],
        'contacts_id' => [
            'type' => 'array|string',
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
        'leads_id' => 'linked_leads_id',
        'contacts_id' => 'linked_contacts_id',
        'closest_task_at' => 'closest_task',
    ];
}