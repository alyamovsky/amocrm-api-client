<?php


namespace ddlzz\AmoAPI\Request;
use ddlzz\AmoAPI\Exceptions\ErrorCodeException;
use ddlzz\AmoAPI\Exceptions\FailedAuthException;
use ddlzz\AmoAPI\SettingsStorage;


/**
 * Class DataSender. It's responsible for interacting with amoCRM.
 * @package ddlzz\AmoAPI
 * @author ddlzz
 */
class DataSender
{
    /** @var SettingsStorage */
    private $settings;

    /** @var Curl */
    private $curl;

    /** DataSender constructor.
     * @param Curl $curl
     * @param SettingsStorage $settings
     */
    public function __construct(Curl $curl, SettingsStorage $settings)
    {
        $this->curl = $curl;
        $this->settings = $settings;
    }

    /**
     * @param string $url
     * @param array $data
     * @return string
     * @throws ErrorCodeException
     * @throws FailedAuthException
     */
    public function send($url, array $data = [])
    {
        $this->curl->init();

        $this->curl->setReturnTransfer(true);
        $this->curl->setUserAgent($this->settings->getUserAgent());
        $this->curl->setUrl($url);
        $this->curl->setHttpHeader([$this->settings::SENDER_HTTP_HEADER]);
        $this->curl->setHeader(false);
        $this->curl->setCookieFile($this->settings->getCookiePath());
        $this->curl->setCookieJar($this->settings->getCookiePath());
        $this->curl->setSSLVerifyPeer(false);
        $this->curl->setSSLVerifyHost(false);

        if (!empty($data)) {
            $this->curl->setCustomRequest('POST');
            $this->curl->setPostFields(json_encode($data));
        }

        $response = $this->curl->exec();
        $httpCode = $this->curl->getHttpCode();

        $this->curl->close();

        if ((401 === $httpCode) || (403 === $httpCode)) {
            throw new FailedAuthException('Auth failed! ' . self::getErrorByHttpCode($httpCode), $response);
        } elseif ((200 !== $httpCode) && (204 !== $httpCode)) {
            throw new ErrorCodeException(self::getErrorByHttpCode($httpCode), $response, $url);
        }

        return $response;
    }

    /**
     * @param int $httpCode
     * @return string
     */
    private static function getErrorByHttpCode($httpCode)
    {
        $errors = [
            301 => '301 Moved permanently',
            400 => '400 Bad request',
            401 => '401 Unauthorized or Blocked',
            403 => '403 Forbidden',
            404 => '404 Not found',
            429 => '429 Too Many Requests',
            500 => '500 Internal server error',
            502 => '502 Bad gateway',
            503 => '503 Service unavailable',
            504 => '504 Gateway Timeout',
        ];

        return isset($errors[$httpCode]) ? $errors[$httpCode] : $httpCode . ' Unknown error';
    }
}