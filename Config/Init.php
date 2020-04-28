<?php
use Library\Load;

ignore_user_abort(true);
set_time_limit(0);
ob_end_clean();
ob_start();
header("Connection: close");
header('Content-Encoding: none');
define("SITE_NAME", "http://".$_SERVER['SERVER_NAME']);

define('SERVER','localhost');
define('DB_NAME','db_name');
define('DB_USERNAME','username');
define('DB_PASSWORD','password');

define('USE_MEMCACHE',false);
define('DATETIME_FORMAT',"Y-m-d H:i:s");


error_reporting(E_ALL);    
ini_set('display_errors', 1);
require_once DIR.'/Library/Load.php';
Load::autoLoad();