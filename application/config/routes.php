<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller']='marbetes';
$route['404_override']='marbetes/missing';
$route['login']='marbetes/login';
$route['logout']='marbetes/logout';
$route['capture']='marbetes/screen/capture';
$route['capture/mobile']='marbetes/screen/capture';
$route['audit']='marbetes/screen/audit';
foreach(array('progress','variation','total','typeraw','audit','history','logs','import') as $p) $route['reports/'.$p]='marbetes/screen/'.$p;
foreach(array('lookup','checklocation','capture','audit','editable','correct','importfile','createrange') as $p) $route['api/'.$p]='marbetes/'.$p;
$route['api/report/(:any)']='marbetes/report/$1';
$route['export/(:any)']='marbetes/export/$1';
// Fail closed: no legacy TAG, BATCH, Openbox, upload or AJAX endpoints in TAGS.
$route['(.+)']='marbetes/missing';
