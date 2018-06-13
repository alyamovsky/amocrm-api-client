<?php

namespace ddlzz\AmoAPI\Model\Amo;

use ddlzz\AmoAPI\Model\AbstractModel;
use ddlzz\AmoAPI\Model\ModelInterface;

/**
 * Class Task.
 *
 * @author ddlzz
 */
final class Task extends AbstractModel implements ModelInterface
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
            'type' => 'int', // todo_ddlzz int|string
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

    /** @var array */
    protected $aliasesAppend = [
        'is_completed' => 'status', // todo_ddlzz bool => int
        'complete_till_at' => 'complete_till',
    ];
}
