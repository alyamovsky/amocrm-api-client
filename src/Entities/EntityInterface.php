<?php


namespace ddlzz\AmoAPI\Entities;


/**
 * Interface EntityInterface
 * @package ddlzz\AmoAPI\Interfaces
 * @author ddlzz
 * @codeCoverageIgnore
 */
interface EntityInterface
{
    /**
     * @param $action
     * @return string
     */
    public function setFieldsParams($action);

    /**
     * @return string
     */
    public function getRequestName();

    /**
     * @return array
     */
    public function getFields();

}