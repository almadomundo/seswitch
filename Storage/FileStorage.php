<?php
/**
 * Created by PhpStorm.
 * User: almado
 * Date: 10/15/14
 * Time: 9:33 AM
 */
namespace Storage;

class FileStorage implements GenericStorage
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
        touch($this->path);
    }

    public function get($key)
    {
        if(!$f = file_get_contents($this->path))
        {
            return null;
        }
        $data = unserialize($f);
        return isset($data[$key])?$data[$key]:null;//not good to manage null-values. who cares?
    }

    public function set($key, $value)
    {
        $data = ($f = file_get_contents($this->path))
            ?unserialize($f)
            :array();
        $data[$key] = $value;
        file_put_contents($this->path, serialize($data));
    }

    public function setAll($data)
    {
        file_put_contents($this->path, $data);
    }

    public function getAll()
    {
        return file_get_contents($this->path);
    }

    public function delete($key)
    {
        $data = unserialize(file_get_contents($this->path));
        unset($data[$key]);
        file_put_contents($this->path, serialize($data));
    }

    public function drop()
    {
        if(file_exists($this->path))
        {
            unlink($this->path);
        }
    }
}
