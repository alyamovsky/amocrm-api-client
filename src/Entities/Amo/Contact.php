<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Contact
 * @package ddlzz\AmoAPI\Entities\Amo
 * @author ddlzz
 */
final class Contact extends BaseEntity implements EntityInterface
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
        'leads' => [
            'type' => 'array',
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
    ];
}