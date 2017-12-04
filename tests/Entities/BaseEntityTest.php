<?php


namespace Tests\AmoAPI\Entities;


use ddlzz\AmoAPI\Entities\BaseEntity;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseEntityTest
 * @package Tests\AmoAPI\Entities
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Entities\BaseEntity
 */
class BaseEntityTest extends TestCase
{
    public function testCanBeCreated()
    {
        self::assertInstanceOf(BaseEntity::class, new class extends BaseEntity {});
    }

    public function testGetRequestName()
    {
        $child = new class extends BaseEntity
        {
            protected $requestName = 'foo';
        };
        self::assertEquals('foo', $child->getRequestName());
    }

    public function testSetFields()
    {
        $child = new class extends BaseEntity {};
        $child['id'] = 42;
        $child['created_at'] = time();
        $child->setFields('add');

        self::assertEquals(42, $child['id']);
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     */
    public function testSetFieldsException()
    {
        $child = new class extends BaseEntity {};
        $child['id'] = 42;
        $child->setFields('foo');
    }

    public function testGetFields()
    {
        $child = new class extends BaseEntity {};
        $child['id'] = 42;
        $child['created_at'] = time();
        $child->setFields('fill');

        self::assertEquals(42, $child->getFields()['id']);
    }

    public function testSetCreatedAt()
    {
        $child = new class extends BaseEntity {};
        $child['id'] = 42;
        $child->setFields('fill');

        self::assertTrue(!empty($child->getFields()['created_at']));
    }

    public function testFill()
    {
        $child = new class extends BaseEntity {};
        $data = [
            'id' => 42,
            'name' => 'Test name',
            'responsible_user_id' => 123456,
            'created_by' => 123,
            'created_at' => 12345,
            'updated_at' => 123,
            'account_id' => 12,
        ];

        $child->fill($data);
        self::assertEquals(42, $child->getFields()['id']);
    }
}