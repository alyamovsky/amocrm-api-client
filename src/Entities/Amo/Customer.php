<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Customer
 * @package ddlzz\AmoAPI\Entities\Amo
 * @author ddlzz
 */
class Customer extends BaseEntity implements EntityInterface
{
    /** @var string */
    protected $requestName = 'customers';
}