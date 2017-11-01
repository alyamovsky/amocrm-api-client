<?php
namespace ddlzz\AmoAPI\Entities;


/**
 * Class EntityFactory
 * @package ddlzz\AmoAPI\Entities
 * @author ddlzz
 */
interface EntityFactoryInterface
{
    /**
     * @param string $entityName
     * @return EntityInterface
     */
    public static function create($entityName);
}