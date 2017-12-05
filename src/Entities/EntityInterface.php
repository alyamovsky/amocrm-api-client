<?php


namespace ddlzz\AmoAPI\Entities;


/**
 * Interface EntityInterface
 * @package ddlzz\AmoAPI\Interfaces
 * @author ddlzz
 */
interface EntityInterface
{
    /**
     * @param $action
     * @return string
     */
    public function setFields($action);

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

    /**
     * Sets the 'updated_at' value as current time if nothing was passed to it.
     */
    public function setUpdatedAt();

}