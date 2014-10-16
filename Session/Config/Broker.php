<?php
/**
 * Created by PhpStorm.
 * User: almado
 * Date: 10/16/14
 * Time: 1:03 PM
 */

namespace Session\Config;
use Session;
use Storage;

class Broker extends Storage\Broker
{
    const SESSION_CONFIG_KEYS_ROUTER    = '__keys_router';
    const SESSION_CONFIG_ROUTE_OPTIONS  = '__route_options';

    private $logicHelper;

    public function __construct(Session\Helper $logicHelper, Storage\GenericStorage $storage)
    {
        parent::__construct($storage);
        $this->logicHelper  = $logicHelper;
    }

    public function getConfig()
    {
        return $this->readStorageData();
    }

}