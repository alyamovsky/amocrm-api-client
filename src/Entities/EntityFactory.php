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
    private static $settings;

    /**
     * @param SettingsStorage $settings
     */
    private static function initSettings(SettingsStorage $settings = null)
    {
        self::$settings = isset($settings) ? $settings: new SettingsStorage(); // todo_ddlzz hidden dependency
    }

    /**
     * @param string $entityType
     * @return EntityInterface
     */
    public static function create($entityType)
    {
        self::initSettings();

        $entity = self::prepareClassName($entityType);
        self::validateClass($entity);

        return new $entity;
    }

    /**
     * @param string $className
     * @return string
     */
    private static function prepareClassName($className)
    {
        return self::$settings::NAMESPACE_PREFIX . '\Entities\Amo\\' . rtrim(ucfirst($className), 's');
    }

    /**
     * @param string $className
     * @return bool
     * @throws EntityFactoryException
     */
    private static function validateClass($className)
    {
        if (!class_exists($className)) {
            throw new EntityFactoryException("Class \"$className\" does not exists");
        }

        $reflection = new \ReflectionClass($className);

        if (!$reflection->implementsInterface(self::$settings::NAMESPACE_PREFIX . '\Entities\EntityInterface')) {
            throw new EntityFactoryException("Class \"$className\" does not implement EntityInterface");
        }

        return true;
    }
}