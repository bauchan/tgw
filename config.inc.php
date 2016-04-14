<?php
include_once __DIR__ . '/autoload.php';
include_once('lib/adodb/adodb.inc.php');
include_once('lib/adodb/adodb-active-record.inc.php');

//隱藏錯誤訊息
error_reporting(0);


$c1 = new \DAO\IpTableDAO();
if(!$c1::checkIp()){
	header("HTTP/1.0 404 Not Found");
	exit;	
}

session_start();

$db = NewADOConnection('mysql');
$db->charPage =65001; 
$db->debug=0;
$db->PConnect('localhost','root','12345','tgw');# connect to MySQL, agora db
$db->Execute("set names 'utf8'"); 
ADOdb_Active_Record::SetDatabaseAdapter($db);	
?>