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
     */
    public function __construct($message = '', $response = '')
    {
        parent::__construct($message);

        $this->message .= ' Server response: ' . $response;
    }
}