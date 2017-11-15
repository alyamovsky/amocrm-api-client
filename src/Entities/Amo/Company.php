<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Company
 * @package ddlzz\AmoAPI\Entities\Amo
 * @author ddlzz
 */
class Company extends BaseEntity implements EntityInterface
{
    /** @var string */
    protected $requestName = 'companies';
}