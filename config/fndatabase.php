<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-10-28
 * Time: 오후 9:56
 */

set_include_path(app_path() . '/Models/Home' . PATH_SEPARATOR . get_include_path());
require_once 'Zend/Db.php';

$mysqlOptions = array(
    'host' => 'localhost',
    'dbname' => 'kyongsong'.'2018'/*date("20y")*/,
    'username' => 'root',
    'password' => '',
    'port' => 3306,
    'charset' => 'UTF8',
    'persistent' => false
);

global $g_dbAdapter;
$g_dbAdapter = Zend_Db::factory('pdo_mysql', $mysqlOptions);