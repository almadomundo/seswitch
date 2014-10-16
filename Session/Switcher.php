<?php

namespace Session;

class Switcher
{
    const SESSION_SWITCH_PRIMARY_ID = '__primary_id';
    const SESSION_SWITCH_CURRENT_ID = '__current_id';

    private $keysStorageBroker;
    private $configStorageBroker;
    private $logicHelper;

    public function __construct(Helper $logicHelper, Config\Broker $configStorageBroker, Keys\Broker $keysStorageBroker)
    {
        $this->logicHelper          = $logicHelper;
        $this->configStorageBroker  = $configStorageBroker;
        $this->keysStorageBroker    = $keysStorageBroker;
    }

    public function getPrimarySessionId($currentSessionId)
    {
        if(isset($currentSessionId) && $primaryId = $this->keysStorageBroker->getPrimaryId($currentSessionId)){
            return array(
                self::SESSION_SWITCH_PRIMARY_ID => $primaryId,
                self::SESSION_SWITCH_CURRENT_ID => $this->keysStorageBroker->getCurrentId($this->configStorageBroker, $currentSessionId, $primaryId)
            );
        }
        return $this->initPrimarySessionId();
    }

    public function initPrimarySessionId()
    {
        $primaryId  = $this->logicHelper->generateId();
        return array(
            self::SESSION_SWITCH_PRIMARY_ID => $primaryId,
            self::SESSION_SWITCH_CURRENT_ID => $this->keysStorageBroker->initKeys($primaryId, $this->configStorageBroker)
        );
    }
}