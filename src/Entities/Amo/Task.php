<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Task
 * @package ddlzz\AmoAPI\Entities\Amo
 * @author ddlzz
 */
class Task extends BaseEntity implements EntityInterface
{
    /** @var string */
    protected $requestName = 'tasks';
}