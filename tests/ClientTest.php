<?php


namespace Tests\AmoAPI;

use ddlzz\AmoAPI\Client;
use ddlzz\AmoAPI\CredentialsManager;
use ddlzz\AmoAPI\Entities\Amo\Lead;
use ddlzz\AmoAPI\Entities\EntityInterface;
use ddlzz\AmoAPI\Request\DataSender;
use ddlzz\AmoAPI\SettingsStorage;
use PHPUnit\Framework\TestCase;


/**
 * Class ClientTest
 * @package Tests\AmoAPI
 * @author ddlzz
 * @covers \ddlzz\AmoAPI\Client
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

    protected function setUp()
    {
        $login = 'test@test.com';
        $this->credentials = new CredentialsManager('test', $login, md5('test'));
        $this->dataSender = $this->createMock(DataSender::class);
        $this->settings = new SettingsStorage();

        file_put_contents(__DIR__ . '/../var/test_correct_login_cookie.txt', str_replace('@', '%40', $login));
    }

    /**
     * @param EntityInterface $entity
     * @return string
     */
    private function buildDataSenderResponse(EntityInterface $entity)
    {
        $method = $this->settings->getMethodPath($entity->getRequestName());
        $id = rand(200000, 300000);

        $response = [
            '_link' => [
                'self' => [
                    'href' => $method,
                    'method' => 'post',
                ]
            ],
            '_embedded' => [
                'items' => [
                    [
                        'id' => $id,
                        '_link' => [
                            'self' => [
                                'href' => $method . '?id=' . $id,
                                'method' => 'get',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return json_encode($response);
    }

    public function testCookieFileCreation()
    {
        $this->settings->setCookiePath('/var/nonexistent_cookie.txt');
        $this::assertInstanceOf(Client::class, new Client($this->credentials, $this->dataSender, $this->settings));
    }

    public function testCreationFromValidParams()
    {
        $this->settings->setCookiePath('/var/test_correct_login_cookie.txt');
        $this::assertInstanceOf(Client::class, new Client($this->credentials, $this->dataSender, $this->settings));
    }

    public function testCheckIrrelevantCookie()
    {
        file_put_contents(__DIR__ . '/../var/test_wrong_login_cookie.txt', 'wrong_login%40.test.com');

        $this->settings->setCookiePath('/var/test_wrong_login_cookie.txt');
        new Client($this->credentials, $this->dataSender, $this->settings);
        $this::assertFalse(file_exists(__DIR__ . '/../var/test_wrong_login_cookie.txt'));
    }

    // todo_ddlzz switch to Guzzle someday.
    // Because of async nature of curl_exec we can not implement this check correctly.
    //    /**
    //     * @expectedException \ddlzz\AmoAPI\Exceptions\RuntimeException
    //     */
    //    public function testUnreachableCookiePath()
    //    {
    //        $this->settings->setCookiePath('/missing_folder/test_cookie.txt');
    //        new Client($this->credentials, $this->dataSender, $this->settings);
    //    }

    /**
     * @param EntityInterface $entity
     * @return string
     */
    private function fakeAddEntity(EntityInterface $entity)
    {
        $response = $this->buildDataSenderResponse($entity);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->dataSender->method('send')->willReturn($response);

        $this->settings->setCookiePath('/var/test_correct_login_cookie.txt');
        $client = new Client($this->credentials, $this->dataSender, $this->settings);

        return $client->add($entity);
    }

    public function testAddLead()
    {
        $entity = new Lead();
        $entity['name'] = 'test_add';
        $entity['created_at'] = time();
        $entity['sale'] = 150000;

        $result = json_decode($this->fakeAddEntity($entity), true);

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
            $this->fakeAddEntity($entity);
        }

        $end = microtime(true);

        $deltaTime = $end - $start;

        $this::assertGreaterThan($repeatCounts - 1, $deltaTime);
    }

    protected function tearDown()
    {
        if (file_exists(__DIR__ . '/../var/test_wrong_login_cookie.txt')) {
            unlink(__DIR__ . '/../var/test_wrong_login_cookie.txt');
        }

        unlink(__DIR__ . '/../var/test_correct_login_cookie.txt');
    }
}