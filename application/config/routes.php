<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller']='batch';
$route['404_override']='batch/missing';
$route['login']='batch/login';
$route['logout']='batch/logout';
$route['capture']='batch/screen/capture';
$route['capture/mobile']='batch/screen/capture';
$route['audit']='batch/screen/audit';
foreach(array('progress','variation','total','typeraw','audit','history','logs','import') as $p) $route['reports/'.$p]='batch/screen/'.$p;
foreach(array('lookup','checklocation','capture','audit','editable','correct','importfile') as $p) $route['api/'.$p]='batch/'.$p;
$route['api/report/(:any)']='batch/report/$1';
$route['export/(:any)']='batch/export/$1';
// Fail closed: no legacy TAG, BATCH, Openbox, upload or AJAX endpoints in BATCH.
$route['api/schedule']='batch/schedule';
$route['(.+)']='batch/missing';
