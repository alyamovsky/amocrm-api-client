<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Task
 * @package ddlzz\AmoAPI\Entities\Amo
 * @author ddlzz
 */
final class Task extends BaseEntity implements EntityInterface
{
    /** @var string */
    protected $requestName = 'tasks';

    /** @var array */
    protected $fieldsParamsAppend = [
        'group_id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'element_type' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'element_id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'is_completed' => [
            'type' => 'bool',
            'required_add' => false,
            'required_update' => false,
        ],
        'task_type' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'complete_till_at' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'text' => [
            'type' => 'string',
            'required_add' => false,
            'required_update' => false,
        ],
        'result' => [
            'type' => 'array',
            'required_add' => false,
            'required_update' => false,
        ],
    ];
}