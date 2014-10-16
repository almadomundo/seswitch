<?php
/**
 * Created by PhpStorm.
 * User: almado
 * Date: 10/16/14
 * Time: 12:54 PM
 */

namespace Session;

class Helper
{
    const SESSION_ID_PREFIX = 'dbg--';

    public function generateId()
    {
        return self::SESSION_ID_PREFIX.uniqid(mt_rand(1000000000, 2000000000), false);
    }
}