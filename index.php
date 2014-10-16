<?php
/**
 * Created by PhpStorm.
 * User: almado
 * Date: 10/15/14
 * Time: 9:46 AM
 */
error_reporting(-1);
//spl_* ? 5.3 ?
function __autoload($class)
{
    $class      = explode('\\', $class);
    return require_once(__DIR__.DIRECTORY_SEPARATOR.join(DIRECTORY_SEPARATOR, $class).'.php');
}

$config = array(
    //this should be replaced by https/https switcher! :
    Session\Config\Broker::SESSION_CONFIG_KEYS_ROUTER   => function()
    {
        return $_GET['switcher'];
    },
    Session\Config\Broker::SESSION_CONFIG_ROUTE_OPTIONS => array(
        'X',
        'Y',
        'Z'
    )
);

$sessionHelper      = new Session\Helper();
$sessionConfig      = new Session\Config\Broker($sessionHelper, new \Storage\ArrayStorage($config));
$sessionKeys        = new Session\Keys\Broker($sessionHelper, new \Storage\FileStorage(__DIR__.'/.session/session.keys'));
$sessionSwitcher    = new Session\Switcher($sessionHelper, $sessionConfig, $sessionKeys);

$id     = isset($_COOKIE['PHPSESSID'])?$_COOKIE['PHPSESSID']:null;
$idData = $sessionSwitcher->getPrimarySessionId($id);

$sessionStorage     = new Storage\Broker(new Storage\FileStorage(__DIR__.'/.session/session.'.$idData[Session\Switcher::SESSION_SWITCH_PRIMARY_ID]));
$sessionHandler     = new Session\Handler($sessionHelper, $sessionStorage);

var_dump($idData);

session_id($idData[Session\Switcher::SESSION_SWITCH_CURRENT_ID]);
session_set_save_handler(
    array($sessionHandler, 'open'),
    array($sessionHandler, 'close'),
    array($sessionHandler, 'read'),
    array($sessionHandler, 'write'),
    array($sessionHandler, 'destroy'),
    array($sessionHandler, 'gc')
);
register_shutdown_function('session_write_close'); //?

session_start();

if(!isset($_SESSION['foo'])) {
    echo('Value was not set, proceed');
    $_SESSION['foo'] = 'bar';
}
else{
    echo('Value was set: '.$_SESSION['foo']);
}
