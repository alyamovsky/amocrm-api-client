<?php


namespace ddlzz\AmoAPI\Exceptions;


/**
 * Class ErrorCodeException
 * @package ddlzz\AmoAPI\Exceptions
 * @author ddlzz
 */
class ErrorCodeException extends AmoException
{
    /**
     * ErrorCodeException constructor.
     * @param string $message
     * @param string $response
     * @param string $url
     */
    public function __construct($message = '', $response = '', $url = '')
    {
        parent::__construct($message);

        $this->message .= '. Server response: ' . $response;
        $this->message .= '. Url: ' . $url;
    }
}