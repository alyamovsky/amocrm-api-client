<?php


namespace ddlzz\AmoAPI\Request;

use ddlzz\AmoAPI\Exceptions\CurlException;


/**
 * An abstraction layer for interacting with cURL library.
 * @package ddlzz\AmoAPI
 * @author ddlzz
 */
class Curl
{
    /** @var resource */
    private $curl;

    /** Curl constructor. */
    public function init()
    {
        $this->curl = curl_init();
    }

    public function close()
    {
        curl_close($this->curl);
    }

    /**
     * @return array|false
     * @throws CurlException
     */
    public function exec()
    {
        if (!is_resource($this->curl)) {
            throw new CurlException('Curl class is not properly initialized');
        }

        return curl_exec($this->curl);
    }

    ///////////////////////////////
    // The list of possible options
    ///////////////////////////////

    /**
     * @param string $url
     * @return bool
     * @throws CurlException
     */
    public function setUrl($url)
    {
        return $this->setOpt(CURLOPT_URL, $url);
    }

    /**
     * @param bool $value
     * @return bool
     * @throws CurlException
     */
    public function setReturnTransfer($value)
    {
        return $this->setOpt(CURLOPT_RETURNTRANSFER, $value);
    }

    /**
     * @param string $agent
     * @return bool
     * @throws CurlException
     */
    public function setUserAgent($agent)
    {
        return $this->setOpt(CURLOPT_USERAGENT, $agent);
    }

    /**
     * @param array $header
     * @return bool
     * @throws CurlException
     */
    public function setHttpHeader(array $header)
    {
        return $this->setOpt(CURLOPT_HTTPHEADER, $header);
    }

    /**
     * @param bool $value
     * @return bool
     * @throws CurlException
     */
    public function setHeader($value)
    {
        return $this->setOpt(CURLOPT_HEADER, $value);
    }

    /**
     * @param string $cookieFile
     * @return bool
     * @throws CurlException
     */
    public function setCookieFile($cookieFile)
    {
        return $this->setOpt(CURLOPT_COOKIEFILE, $cookieFile);
    }

    /**
     * @param string $cookieJar
     * @return bool
     * @throws CurlException
     */
    public function setCookieJar($cookieJar)
    {
        return $this->setOpt(CURLOPT_COOKIEJAR, $cookieJar);
    }

    /**
     * @param bool $value
     * @return bool
     * @throws CurlException
     */
    public function setSSLVerifyPeer($value)
    {
        return $this->setOpt(CURLOPT_SSL_VERIFYPEER, $value);
    }

    /**
     * @param bool $value
     * @return bool
     * @throws CurlException
     */
    public function setSSLVerifyHost($value)
    {
        return $this->setOpt(CURLOPT_SSL_VERIFYHOST, $value);
    }

    /**
     * @param string $request
     * @return bool
     * @throws CurlException
     */
    public function setCustomRequest($request)
    {
        return $this->setOpt(CURLOPT_CUSTOMREQUEST, $request);
    }

    /**
     * @param mixed $postFields
     * @return bool
     * @throws CurlException
     */
    public function setPostFields($postFields)
    {
        return $this->setOpt(CURLOPT_POSTFIELDS, $postFields);
    }

    //////////////////////
    // The end of the list
    //////////////////////

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    }

    /**
     * Closes the connection in case of abnormal termination
     * @throws CurlException
     */
    public function __destruct()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
            throw new CurlException('Curl class was not properly closed');
        }
    }

    /**
     * Sets various cURL options.
     *
     * @param string $option
     * @param mixed $value
     * @return bool
     * @throws CurlException
     */
    private function setOpt($option, $value)
    {
        if (!is_resource($this->curl)) {
            throw new CurlException('Curl class is not properly initialized');
        }

        return curl_setopt($this->curl, $option, $value);
    }
}