<?php

namespace Tests\AmoAPI\Model;

use ddlzz\AmoAPI\Model\ModelFactory;
use ddlzz\AmoAPI\Model\ModelInterface;
use ddlzz\AmoAPI\SettingsStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class EntityFactoryTest.
 *
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Model\ModelFactory
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
        self::assertInstanceOf(ModelFactory::class, new ModelFactory($this->settings));
    }

    public function testCreateEntity()
    {
        $factory = new ModelFactory($this->settings);
        self::assertInstanceOf(ModelInterface::class, $factory->create('lead'));
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exception\EntityFactoryException
     * @expectedExceptionMessageRegExp ~.*does not exists~
     */
    public function testCreateEntityNonexistentClassException()
    {
        $factory = new ModelFactory($this->settings);
        $factory->create('foobar');
    }
}
