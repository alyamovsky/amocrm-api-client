<?php


namespace ddlzz\AmoAPI\Entities\Amo;


use ddlzz\AmoAPI\Entities\BaseEntity;
use ddlzz\AmoAPI\Entities\EntityInterface;

/**
 * Class Contact
 * @package ddlzz\AmoAPI\Entities\Amo
 * @author ddlzz
 */
class Contact extends BaseEntity implements EntityInterface
{
    /** @var string */
    protected $requestName = 'contacts';
}