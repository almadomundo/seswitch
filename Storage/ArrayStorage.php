<?php
/**
 * Created by PhpStorm.
 * User: almado
 * Date: 10/15/14
 * Time: 9:37 AM
 */

namespace Storage;

class ArrayStorage implements GenericStorage
{
    private $data = array();

    public function __construct(array $data=array())
    {
        $this->data = $data;
    }

    public function get($key)
    {
        return isset($this->data[$key])?$this->data[$key]:null;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function setAll($data)
    {
        $this->data = $data;
    }

    public function getAll()
    {
        return $this->data;
    }

    public function delete($key)
    {
        unset($this->data[$key]);
    }

    public function drop()
    {
        $this->data = [];
    }
}