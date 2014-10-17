<?php

namespace Session;

/**
 * Class Switcher
 * @package Session
 */
class Switcher
{
    /**
     *  Key for session pair to store primary session id
     *  Primary session id is the one which points to session data storage and is same across all session group
     */
    const SESSION_SWITCH_PRIMARY_ID = '__primary_id';
    /**
     *  Key for session pair to store current session id.
     *  Current session id is one which corresponds current group value
     */
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

    /**
     * @param $currentSessionId string Current session id to get pair of primary id and corresponding new id
     * @return array
     */
    public function getSessionIdPair($currentSessionId)
    {
        if(isset($currentSessionId) && $primaryId = $this->keysStorageBroker->getPrimaryId($currentSessionId)){
            return array(
                self::SESSION_SWITCH_PRIMARY_ID => $primaryId,
                self::SESSION_SWITCH_CURRENT_ID => $this->keysStorageBroker->getCurrentId($this->configStorageBroker, $currentSessionId, $primaryId)
            );
        }
        return $this->initSessionIdPair();
    }

    /**
     * Initialize session id pair with new primary id and corresponding current id.
     * This will create all group keys in keys storage
     * @return array
     */
    public function initSessionIdPair()
    {
        $primaryId  = $this->logicHelper->generateId();
        return array(
            self::SESSION_SWITCH_PRIMARY_ID => $primaryId,
            self::SESSION_SWITCH_CURRENT_ID => $this->keysStorageBroker->initKeys($primaryId, $this->configStorageBroker)
        );
    }
}