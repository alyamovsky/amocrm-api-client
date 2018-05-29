<?php


namespace ddlzz\AmoAPI\Exception;


/**
 * Class FailedAuthException
 * @package ddlzz\AmoAPI\Exception
 * @author ddlzz
 */
class FailedAuthException extends AmoException
{
    /**
     * ErrorCodeException constructor.
     * @param string $message
     * @param string $response
     */
    public function __construct($message = '', $response = '')
    {
        parent::__construct($message);

        $this->message .= '. Server response: ' . $response;
    }
}