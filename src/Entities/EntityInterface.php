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

    /**
     * @param array $data
     * @return EntityInterface
     */
    public function fill(array $data);

}