<?php
/**
 * Created by PhpStorm.
 * User: almado
 * Date: 10/16/14
 * Time: 3:18 PM
 */

namespace Storage;

class Broker
{
    protected $storage;

    private $storageMethod  = null;

    public function __construct(GenericStorage $storage)
    {
        $this->storage      = $storage;
        $this->setStorageMethod();
    }

    public function readStorageData()
    {
        $data       = $this->storage->getAll();
        if($data && !is_array($data)){
            $data   = unserialize($data);
        }
        elseif(!$data){
            return array();
        }
        return $data;
    }

    public function writeStorageData($data)
    {
        if($this->storageMethod=='serialize'){
            $data = serialize($data);
        }
        $this->storage->setAll($data);
    }

    public function destroyStorageData()
    {
        $this->storage->drop();
    }

    private function setStorageMethod()
    {
        if(!isset($this->storageMethod)){
            $this->storageMethod =  is_array($this->storage->getAll())
                ?'array'
                :'serialize';
        }
    }
}