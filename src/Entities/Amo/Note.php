<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Note
 * @package ddlzz\AmoAPI\Entities\Amo
 * @author ddlzz
 */
final class Note extends BaseEntity implements EntityInterface
{
    /** @var string */
    protected $requestName = 'notes';

    /** @var array */
    protected $fieldsParamsAppend = [
        'group_id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'is_editable' => [
            'type' => 'bool',
            'required_add' => false,
            'required_update' => false,
        ],
        'element_id' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'element_type' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'attachment' => [
            'type' => 'string', // ?
            'required_add' => false,
            'required_update' => false,
        ],
        'note_type' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => false,
        ],
        'text' => [
            'type' => 'string',
            'required_add' => false,
            'required_update' => false,
        ],
    ];

    /** @var array */
    protected $aliasesAppend = [
        'updated_by' => 'editable',
        'attachment' => 'ATTACHEMENT',
    ];
}