<?php


namespace Tests\AmoAPI;

use ddlzz\AmoAPI\Client;
use ddlzz\AmoAPI\CredentialsManager;
use ddlzz\AmoAPI\Entities\Amo\Lead;
use ddlzz\AmoAPI\Entities\EntityInterface;
use ddlzz\AmoAPI\Request\DataSender;
use ddlzz\AmoAPI\SettingsStorage;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;


/**
 * Class ClientTest
 * @package Tests\AmoAPI
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Client
 * @covers \ddlzz\AmoAPI\Auth
 * @covers \ddlzz\AmoAPI\Request\UrlBuilder
 */
final class ClientTest extends TestCase
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
        $this->settings = new SettingsStorage();

        $this->cookieDir = vfsStream::setup();
        $this->cookieFile = vfsStream::newFile('cookie.txt')
            ->at($this->cookieDir)
            ->setContent(str_replace('@', '%40', $login));

        $this->settings->setCookiePath($this->cookieFile->url());
    }

    public function testCookieFileCreation()
    {
        $this->settings->setCookiePath('/nonexistent_cookie.txt');
        $this::assertInstanceOf(Client::class, new Client($this->credentials, $this->dataSender, $this->settings));
    }

    public function testCreationFromValidParams()
    {
        $this::assertInstanceOf(Client::class, new Client($this->credentials, $this->dataSender, $this->settings));
    }

    public function testCheckIrrelevantCookie()
    {
        $incorrectCookie = vfsStream::newFile('incorrect_cookie.txt')
            ->at($this->cookieDir)
            ->setContent('wrong_login%40.test.com');

        $this->settings->setCookiePath($incorrectCookie->url());
        new Client($this->credentials, $this->dataSender, $this->settings);
        $this::assertFalse(file_exists($incorrectCookie->url()));
    }

    /** @expectedException \ddlzz\amoAPI\Exceptions\RuntimeException */
    public function testFailCreatingCookie()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->dataSender->method('send')->willReturn('Some non empty success result');

        $this->settings->setCookiePath('/nonexistent_cookie.txt');
        new Client($this->credentials, $this->dataSender, $this->settings);
    }

    public function testAddLead()
    {
        $entity = new Lead();
        $entity['name'] = 'test_add';
        $entity['created_at'] = time();
        $entity['sale'] = 150000;

        $result = json_decode($this->fakeSetEntity($entity, 'add'), true);

        $this::assertEquals($this->settings->getMethodPath($entity->getRequestName()), $result['_link']['self']['href']);
    }

    public function testUpdateLead()
    {
        $entity = new Lead();
        $entity['id'] = 42;
        $entity['name'] = 'test_update';
        $entity['created_at'] = time();
        $entity['updated_at'] = time();
        $entity['sale'] = 150000;

        $result = json_decode($this->fakeSetEntity($entity, 'update'), true);

        $this::assertEquals($this->settings->getMethodPath($entity->getRequestName()), $result['_link']['self']['href']);
    }

    public function testSlowDown()
    {
        $repeatCounts = 3;

        $entity = new Lead();
        $entity['name'] = 'test_add';
        $entity['created_at'] = time();

        $start = microtime(true);

        for ($i = 0; $i < $repeatCounts; $i++) {
            $this->fakeSetEntity($entity, 'add');
        }

        $end = microtime(true);

        $deltaTime = $end - $start;

        $this::assertGreaterThan($repeatCounts - 1, $deltaTime);
    }

    public function testCanFindById()
    {
        $type = 'lead';
        $id = 42;

        $response = $this->buildGetEntityResponse($type, $id);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->dataSender->method('send')->willReturn($response);

        $client = new Client($this->credentials, $this->dataSender, $this->settings);
        $entity = $client->findById($type, $id);

        $this::assertEquals($id, $entity->getFields()['id']);
    }

    /**
     * @expectedException \ddlzz\AmoAPI\Exceptions\InvalidArgumentException
     */
    public function testFindByNonexistentId()
    {
        $type = 'lead';
        $id = 42;

        /** @noinspection PhpUndefinedMethodInspection */
        $this->dataSender->method('send')->willReturn(''); // simulates an empty response

        $client = new Client($this->credentials, $this->dataSender, $this->settings);
        $client->findById($type, $id);
    }

    /**
     * @param EntityInterface $entity
     * @return string
     */
    private function buildSetEntityResponse(EntityInterface $entity)
    {
        $method = $this->settings->getMethodPath($entity->getRequestName());
        $id = rand(200000, 300000);

        $result = '{"_link":{"self":{"href":"' . $method . '","method":"post"}},"_embedded":{"items":[{"id":' . $id .
            ',"_link":{"self":{"href":"' . $method . '?id=' . $id . '","method":"get"}}}]}}';

        return $result;
    }

    /**
     * @param string $type
     * @param int $id
     * @return string
     */
    private function buildGetEntityResponse($type, $id)
    {
        $method = $this->settings->getMethodCodeByType($type);

        return '{"_links":{"self":{"href":"\/api\/v2\/' . $method . '?id=' . $id . '","method":"get"}},"_embedded": ' .
            '{"items":[{"id":' . $id . ',"name":"new_name1228818400","responsible_user_id":1371625,"created_by":1371625,' .
            '"created_at":1511350927,"updated_at":1512062652,"account_id":17397286,"is_deleted":false,' .
            '"main_contact":{},"group_id":0,"company":{},"closed_at":0,"closest_task_at":0,"tags":[{"id":118205,' .
            '"name":"tag_new 1"},{"id":118207,"name":"tag_new"},{"id":118209,"name":"2"}],"custom_fields":{},' .
            '"status_id":17397292,"sale":150000,"contacts":{},"pipeline":{"id":872851,"_links":{"self":{"href":' .
            '"\/api\/v2\/pipelines?id=872851","method":"get"}}},"_links":{"self":{"href":"\/api\/v2\/' . $method . '?' .
            'id=' . $id . '","method":"get"}}}]}}';
    }

    /**
     * @param EntityInterface $entity
     * @param string $action
     * @return string
     */
    private function fakeSetEntity(EntityInterface $entity, $action)
    {
        if (!in_array($action, ['add', 'update'])) {
            throw new \Exception('Wrong action'); // just in case
        }

        $response = $this->buildSetEntityResponse($entity);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->dataSender->method('send')->willReturn($response);

        $client = new Client($this->credentials, $this->dataSender, $this->settings);

        return $client->$action($entity);
    }
}