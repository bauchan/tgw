<?php
include_once 'config.inc.php';
$str = $_SERVER["PATH_INFO"];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$classname = "\\$request[0]\\$request[1]";
$c1 = new $classname(); 
$c1->setContent(@$request[2]);
$c1->Bind();
?>