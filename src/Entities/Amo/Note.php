<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Note
 * @package ddlzz\AmoAPI\Entities\Amo
 * @author ddlzz
 */
class Note extends BaseEntity implements EntityInterface
{
    /** @var string */
    protected $requestName = 'notes';
}