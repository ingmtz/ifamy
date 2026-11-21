<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
$runtime=getenv('PITS_BATCH_CONFIG')?:'/var/www/.pits-private/batch/config.json';
$secret=json_decode(file_get_contents($runtime),true);
if(!$secret || !preg_match('/^pits_(?:dev_)?batch(?:_[a-z0-9]+)*$/',$secret['database']))throw new RuntimeException('BATCH isolated database configuration required.');
$active_group='default';$query_builder=TRUE;
$db['default']=array('dsn'=>'','hostname'=>$secret['host'],'username'=>$secret['sql_user'],'password'=>$secret['sql_password'],'database'=>$secret['database'],'dbdriver'=>'mysqli','dbprefix'=>'','pconnect'=>FALSE,'db_debug'=>FALSE,'cache_on'=>FALSE,'cachedir'=>'','char_set'=>'utf8','dbcollat'=>'utf8_general_ci','swap_pre'=>'','encrypt'=>FALSE,'compress'=>FALSE,'stricton'=>TRUE,'failover'=>array(),'save_queries'=>FALSE);
unset($secret);
