<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$active_group = 'default';
$query_builder = TRUE;
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	
	'username' => 'root',
	'password' => '',
	//'database' => 'ifa',

	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => FALSE,//(ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
$tmparr=explode('/', $_SERVER['REQUEST_URI']);
$dbstr='';
switch ($tmparr[1]) {
  case 'APICS':
    $dbstr='nv_apics';
    break;
  case 'APICSVALLARTA':
    $dbstr='nv_apicsvallarta';
    break;
  case 'APICSMONTERREY':
    $dbstr='nv_apicsmonterrey';
    break;
  case 'APICSMEXICO':
    $dbstr='nv_apicsmexico';
    break;
  case 'APICSMATRIZ':
    $dbstr='nv_apicsmatriz';
    break;
  case 'APICSGUADALAJARA':
    $dbstr='nv_apicsguadalajara';
    break;
  case 'APICSCANCUN':
    $dbstr='nv_apicscancun';
    break;
  case 'APICSCABOS':
    $dbstr='nv_apicscabos';
    break;
}

$db['default']['database']='ifa';//$dbstr;