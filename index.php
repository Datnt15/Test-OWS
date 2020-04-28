<?php
use Library\Route;

define("DIR", dirname(__FILE__));
require 'Config/Init.php';
$uri=isset($_GET['uri'])?$_GET['uri']:'';
if($uri!='404.html')
{
    Route::performRequest($uri);
}