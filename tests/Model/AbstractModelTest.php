<?php

namespace Tests\AmoAPI\Model;

use ddlzz\AmoAPI\Client;
use ddlzz\AmoAPI\CredentialsManager;
use ddlzz\AmoAPI\Model\AbstractModel;
use ddlzz\AmoAPI\Model\ModelInterface;
use ddlzz\AmoAPI\Request\DataSender;
use ddlzz\AmoAPI\SettingsStorage;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractModelTest.
 *
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Model\AbstractModel
 */
final class AbstractModelTest extends TestCase
{
    /** @var CredentialsManager */
    private $credentials;

    /** @var DataSender */
    private $dataSender;

    /** @var SettingsStorage */
    private $settings;

    /** @var vfsStreamDirectory */
    private $cookieDir;

    /** @var vfsStreamFile */
    private $cookieFile;

    protected function setUp()
    {
        $login = 'test@test.com';
        $this->credentials = new CredentialsManager('test', $login, md5('test'));

        $this->dataSender = $this->createMock(DataSender::class);
        $this->dataSender->method('send')->willReturn(true);

        $this->settings = $this->createMock(SettingsStorage::class);
        $this->settings->method('getMethodPath')->willReturn(true);

        $this->cookieDir = vfsStream::setup();
        $this->cookieFile = vfsStream::newFile('cookie.txt')
            ->at($this->cookieDir)
            ->setContent(str_replace('@', '%40', $login));

        $this->settings->method('getCookiePath')->willReturn($this->cookieFile->url());
    }

    public function testCanBeCreated()
    {
        self::assertInstanceOf(AbstractModel::class, new class() extends AbstractModel {
        });
    }

    public function testGetRequestName()
    {
        $child = new class() extends AbstractModel {
            protected $requestName = 'foo';
        };

        self::assertSame('foo', $child->getRequestName());
    }

    public function testSetFields()
    {
        $child = new class() extends AbstractModel {
        };
        $child['id'] = 42;
        $child['created_at'] = time();

        $child->setFields('add');

        self::assertSame(42, $child['id']);
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exception\InvalidArgumentException
     */
    public function testSetFieldsIncorrectAction()
    {
        $child = new class() extends AbstractModel {
        };
        $child['id'] = 42;

        $child->setFields('foo');
    }

    public function testGetFields()
    {
        $child = new class() extends AbstractModel {
        };
        $child['id'] = 42;
        $child['created_at'] = time();

        $child->setFields('fill');

        self::assertSame(42, $child->getFields()['id']);
    }

    public function testFill()
    {
        $child = new class() extends AbstractModel {
        };
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

        self::assertSame(42, $child->getFields()['id']);
    }

    public function testSetUpdatedAt()
    {
        $time = time() - 3600 * 24;
        $child = new class() extends AbstractModel implements ModelInterface {
            protected $requestName = 'foo';
        };

        $child['id'] = 42;
        $child['updated_at'] = $time; // for $fields

        $child->setFields('fill');
        $child['updated_at'] = $time; // for $container
        $client = new Client($this->credentials, $this->dataSender, $this->settings);
        $client->update($child);

        self::assertGreaterThan($time, $child['updated_at']);
    }

    public function testSetCreatedAt()
    {
        $child = new class() extends AbstractModel {
        };
        $child['id'] = 42;

        $child->setFields('fill');

        self::assertTrue(!empty($child->getFields()['created_at']));
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exception\EntityFieldsException
     * @expectedExceptionMessageRegExp ~.*is empty.*~
     */
    public function testEmptyData()
    {
        $child = new class() extends AbstractModel implements ModelInterface {
        };

        $child->setFields('fill');
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exception\EntityFieldsException
     * @expectedExceptionMessageRegExp ~.*not an associative array.*~
     */
    public function testIndexedArrayForData()
    {
        $child = new class() extends AbstractModel implements ModelInterface {
        };
        $child[0] = 'foo';
        $child[1] = 'bar';
        $child[2] = 'baz';

        $child->setFields('fill');
    }

    public function testRenameAliases()
    {
        $child = new class() extends AbstractModel implements ModelInterface {
            protected $fieldsParamsAppend = ['new_alias' => ['type' => 'int', 'required_add' => false, 'required_update' => false]];
            protected $aliasesAppend = ['new_alias' => 'old_alias'];
        };

        $child['old_alias'] = 42;

        $child->setFields('fill');
        self::assertSame(42, $child['new_alias']);
    }

    public function testFieldsTypes()
    {
        $child = new class() extends AbstractModel implements ModelInterface {
            protected $fieldsParamsAppend = [
                'int_param' => [
                    'type' => 'int',
                    'required_add' => false,
                    'required_update' => false,
                ],
                'string_param' => [
                    'type' => 'string',
                    'required_add' => false,
                    'required_update' => false,
                ],
                'bool_param' => [
                    'type' => 'bool',
                    'required_add' => false,
                    'required_update' => false,
                ],
            ];
        };

        $child['int_param'] = 42;
        $child['string_param'] = 'foo';
        $child['bool_param'] = true;

        $child->setFields('fill');

        self::assertTrue(is_int($child->getFields()['int_param']));
        self::assertTrue(is_string($child->getFields()['string_param']));
        self::assertTrue(is_bool($child->getFields()['bool_param']));
    }

    public function testOffsetSet()
    {
        $child = new class() extends AbstractModel {
            public function getContainer()
            {
                return $this->container;
            }
        };

        $child[] = 'baz';
        $child['foo'] = 'bar';

        self::assertSame('baz', $child->getContainer()[0]);
        self::assertSame('bar', $child->getContainer()['foo']);
    }

    public function testOffsetExists()
    {
        $child = new class() extends AbstractModel {
        };
        $child['foo'] = 'bar';

        self::assertTrue(empty($child['baz']));
        self::assertTrue(isset($child['foo']));
    }

    public function testOffsetUnset()
    {
        $child = new class() extends AbstractModel {
        };
        $child['foo'] = 'bar';

        unset($child['foo']);

        self::assertTrue(empty($child['foo']));
    }

    public function testOffsetGet()
    {
        $child = new class() extends AbstractModel {
        };
        $child['id'] = 42;

        $child->setFields('fill');

        self::assertSame(42, $child['id']);
    }
}
