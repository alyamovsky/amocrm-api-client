<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Lead
 * @package ddlzz\AmoAPI\Entities
 * @author ddlzz
 */
class Lead extends BaseEntity implements EntityInterface
{
    /** @var string */
    protected $requestName = 'leads';

    /** @var array */
    protected $fieldsParamsAppend = [
        'sale' => [
            'type' => 'int',
            'required_add' => false,
            'required_update' => true,
            'alias' => 'price',
            'default' => null,
        ],
    ];

    /** @var array */
    protected $aliasesAppend = [
        'sale' => 'price'
    ];
}