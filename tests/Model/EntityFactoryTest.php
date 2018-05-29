<?php


namespace Tests\AmoAPI\Model;


use ddlzz\AmoAPI\Model\EntityFactory;
use ddlzz\AmoAPI\Model\EntityInterface;
use ddlzz\AmoAPI\SettingsStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class EntityFactoryTest
 * @package Tests\AmoAPI\Model
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Model\EntityFactory
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
     * @expectedException \ddlzz\AmoAPI\Exception\EntityFactoryException
     * @expectedExceptionMessageRegExp ~.*does not exists~
     */
    public function testCreateEntityNonexistentClassException()
    {
        $factory = new EntityFactory($this->settings);
        $factory->create('foobar');
    }
}