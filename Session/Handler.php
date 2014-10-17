<?php
/**
 * Created by PhpStorm.
 * User: almado
 * Date: 10/15/14
 * Time: 9:35 AM
 */

namespace Session;
use Storage;

class Handler
{
    const SESSION_DEFAULT_NAME  = 'PHPSESSID';

    private $sessionStorage;
    private $logicHelper;

    public function __construct(Helper $logicHelper, Storage\Broker $sessionStorage)
    {
        $this->logicHelper      = $logicHelper;
        $this->sessionStorage   = $sessionStorage;
    }

    public function open($savePath, $sessionName)
    {
        return true;
    }

    public function close()
    {
        return true; //add invalidation
    }

    public function read($sessionId)
    {
        return $this->sessionStorage->readStorageData();
    }

    public function write($sessionId, $data)
    {
        $this->sessionStorage->writeStorageData($data);
        return true;
    }

    public function destroy($sessionId)
    {
        $this->sessionStorage->destroyStorageData();
        return true;
    }

    public function gc($lifetime)
    {
        return true; //no cleanup yet
    }
}
