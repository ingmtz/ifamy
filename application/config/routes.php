<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller']='hus';
$route['404_override']='hus/missing';
$route['login']='hus/login';
$route['logout']='hus/logout';
$route['capture']='hus/screen/capture';
$route['capture/mobile']='hus/screen/capture';
$route['audit']='hus/screen/audit';
foreach(array('progress','variation','total','typeraw','audit','history','logs','import') as $p) $route['reports/'.$p]='hus/screen/'.$p;
foreach(array('lookup','checklocation','capture','audit','editable','correct','importfile') as $p) $route['api/'.$p]='hus/'.$p;
$route['api/report/(:any)']='hus/report/$1';
$route['export/(:any)']='hus/export/$1';
// Fail closed: no legacy TAG, BATCH, Openbox, upload or AJAX endpoints in HUS.
$route['api/schedule']='hus/schedule';
$route['(.+)']='hus/missing';
