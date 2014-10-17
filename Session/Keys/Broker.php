<?php
/**
 * Created by PhpStorm.
 * User: almado
 * Date: 10/16/14
 * Time: 1:03 PM
 */

namespace Session\Keys;
use Session\Config as Config;
use Session;
use Storage;

class Broker extends Storage\Broker
{
    const SESSION_KEYS_ROUTE_POINT  = '__route_point';
    const SESSION_KEYS_CURRENT_ID   = '__current_id';
    const SESSION_KEYS_PRIMARY_ID   = '__primary_id';
    const SESSION_KEYS_CREATED_AT   = '__created_at';

    private $logicHelper;

    public function __construct(Session\Helper $logicHelper, Storage\GenericStorage $storage)
    {
        parent::__construct($storage);
        $this->logicHelper  = $logicHelper;
    }

    public function getCurrentId(Config\Broker $config, $id, $primaryId=null)
    {
        $config = $config->getConfig();
        if(!isset($primaryId)){
            $primaryId = $this->getPrimaryId($id);
        }
        $entry = $this->searchByAllKeys(array(
            self::SESSION_KEYS_PRIMARY_ID   => $primaryId,
            self::SESSION_KEYS_ROUTE_POINT  => call_user_func($config[Config\Broker::SESSION_CONFIG_KEYS_ROUTER])
        ));
        return isset($entry[self::SESSION_KEYS_CURRENT_ID])?$entry[self::SESSION_KEYS_CURRENT_ID]:$primaryId;
    }

    public function getPrimaryId($id)
    {
        if($entry = $this->searchByKey(self::SESSION_KEYS_CURRENT_ID, $id)){
            return $entry[self::SESSION_KEYS_PRIMARY_ID];
        }
    }

    public function initKeys($id, Config\Broker $config)
    {
        $config = $config->getConfig();
        $keys   = array();
        $result = null;
        $time   = time();
        foreach($config[Config\Broker::SESSION_CONFIG_ROUTE_OPTIONS] as $option){
            $keys[] = array(
                self::SESSION_KEYS_ROUTE_POINT  => $option,
                self::SESSION_KEYS_CURRENT_ID   => ($option == call_user_func($config[Config\Broker::SESSION_CONFIG_KEYS_ROUTER]))
                                                    ?($result = $this->logicHelper->generateId())
                                                    :$this->logicHelper->generateId(),
                self::SESSION_KEYS_PRIMARY_ID   => $id,
                self::SESSION_KEYS_CREATED_AT   => $time
            );
        }
        $this->appendKeysToStorage($keys);
        return $result;
    }

    private function appendKeysToStorage(array $keys)
    {
        $data   = $this->readStorageData();
        $data   = array_merge($data, $keys);
        $this->writeStorageData($data);
    }

    private function searchByKey($key, $value)
    {
        //echo('<pre>');var_dump(debug_backtrace());
        $data   = $this->readStorageData();
        foreach($data as $entry)
        {
            if(isset($entry[$key]) && $entry[$key]==$value){
                return $entry;
            }
        }
    }

    private function searchByAllKeys(array $search)
    {
        $data   = $this->readStorageData();
        foreach($data as $entry)
        {
            if(array_intersect_key($entry, $search) == $search)
            {
                return $entry;
            }
        }
    }

    private function removeExpiredEntries($lifetime)
    {
        $createdAt = self::SESSION_KEYS_CREATED_AT; //as of 5.3
        $this->writeStorageData(
            array_filter($this->readStorageData(),
            function($entry) use ($lifetime, $createdAt) {
                return time() - $entry[$createdAt] <= $lifetime;
            })
        );
    }

}