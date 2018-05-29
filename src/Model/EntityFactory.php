<?php


namespace ddlzz\AmoAPI\Model;

use ddlzz\AmoAPI\Exception\EntityFactoryException;
use ddlzz\AmoAPI\SettingsStorage;


/**
 * Class EntityFactory
 * @package ddlzz\AmoAPI\Model
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
     * @param string $type
     * @return EntityInterface
     * @throws EntityFactoryException
     */
    public function create($type)
    {
        $entity = $this->prepareClassName($type);
        $this->validateClass($entity);

        return new $entity;
    }

    /**
     * @param string $name
     * @return string
     */
    private function prepareClassName($name)
    {
        return SettingsStorage::NAMESPACE_PREFIX . '\Model\Amo\\' . rtrim(ucfirst($name), 's');
    }

    /**
     * @param string $name
     * @return bool
     * @throws EntityFactoryException
     */
    private function validateClass($name)
    {
        if (!class_exists($name)) {
            throw new EntityFactoryException("Class \"$name\" does not exists");
        }

        return true;
    }
}