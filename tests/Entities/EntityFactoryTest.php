<?php


namespace Tests\AmoAPI\Entities;


use ddlzz\AmoAPI\Entities\EntityFactory;
use ddlzz\AmoAPI\Entities\EntityInterface;
use ddlzz\AmoAPI\SettingsStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class EntityFactoryTest
 * @package Tests\AmoAPI\Entities
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Entities\EntityFactory
 */
final class EntityFactoryTest extends TestCase
{
    /** @var SettingsStorage */
    private $settings;

    protected function setUp()
    {
        $this->settings = new SettingsStorage();
    }

    public function testCanBeCreated()
    {
        self::assertInstanceOf(EntityFactory::class, new EntityFactory($this->settings));
    }

    public function testCreateEntity()
    {
        $factory = new EntityFactory($this->settings);
        self::assertInstanceOf(EntityInterface::class, $factory->create('lead'));
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exceptions\EntityFactoryException
     * @expectedExceptionMessageRegExp ~Class .* does not exists~
     */
    public function testCreateEntityNonexistentClassException()
    {
        $factory = new EntityFactory($this->settings);
        $factory->create('foobar');
    }
}