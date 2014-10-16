<?php
/**
 * Created by PhpStorm.
 * User: almado
 * Date: 10/15/14
 * Time: 9:32 AM
 */
namespace Storage;

interface GenericStorage
{
    public function get($key);
    public function set($key, $value);
    public function setAll($data);
    public function getAll();
    public function delete($key);
    public function drop();
}