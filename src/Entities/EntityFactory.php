<?php


namespace ddlzz\AmoAPI\Entities;

use ddlzz\AmoAPI\Exceptions\EntityFactoryException;
use ddlzz\AmoAPI\SettingsStorage;


/**
 * Class EntityFactory
 * @package ddlzz\AmoAPI\Entities
 * @author ddlzz
 */
class EntityFactory
{
    /** @var SettingsStorage */
    private $settings;

    /**
     * @param SettingsStorage $settings
     */
    public function __construct(SettingsStorage $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param string $entityType
     * @return EntityInterface
     */
    public function create($entityType)
    {
        $entity = $this->prepareClassName($entityType);
        $this->validateClass($entity);

        return new $entity;
    }

    /**
     * @param string $className
     * @return string
     */
    private function prepareClassName($className)
    {
        return $this->settings::NAMESPACE_PREFIX . '\Entities\Amo\\' . rtrim(ucfirst($className), 's');
    }

    /**
     * @param string $className
     * @return bool
     * @throws EntityFactoryException
     */
    private function validateClass($className)
    {
        if (!class_exists($className)) {
            throw new EntityFactoryException("Class \"$className\" does not exists");
        }

        $reflection = new \ReflectionClass($className);

        if (!$reflection->implementsInterface($this->settings::NAMESPACE_PREFIX . '\Entities\EntityInterface')) {
            throw new EntityFactoryException("Class \"$className\" does not implement EntityInterface");
        }

        return true;
    }
}