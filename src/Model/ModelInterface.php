<?php

namespace ddlzz\AmoAPI\Model;

/**
 * Interface ModelInterface.
 *
 * @author ddlzz
 */
interface ModelInterface
{
    /**
     * @param $action
     *
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
     *
     * @return ModelInterface
     */
    public function fill(array $data);

    /**
     * Sets the 'updated_at' value as current time if nothing was passed to it.
     */
    public function setUpdatedAt();
}
