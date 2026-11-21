<?php
// Execute only via CLI against the disposable BATCH QA database. Never deployed to docroot.
if(PHP_SAPI!=='cli')exit(1);
$runtime=getenv('PITS_BATCH_CONFIG');$secret=json_decode(file_get_contents($runtime),true);
if($secret['database']!=='pits_dev_batch_qa_20261121')throw new RuntimeException('QA-only test guard');
$code=getenv('PITS_BATCH_CODE')?:'/var/www/novarum/PITS-BATCH/';
define('BASEPATH',$code.'system/');define('APPPATH',$code.'application/');define('ENVIRONMENT','production');define('VIEWPATH',APPPATH.'views/');
function log_message($level,$message) { error_log($level.': '.$message); }
require BASEPATH.'core/Common.php';require BASEPATH.'database/DB.php';
require APPPATH.'config/database.php';
class CI_Model { public $db; }
require APPPATH.'models/Pits_batch_model.php';
$m=new Pits_batch_model();$m->db=DB($db['default'],true);
function query($sql,$args=array()) { global $m;$q=$m->db->query($sql,$args);if($q===false)throw new RuntimeException('Test SQL failed');return $q; }
function check($condition,$message){if(!$condition)throw new RuntimeException('FAIL: '.$message);echo 'PASS: '.$message."\n";}
function capture($batch){global $m;return $m->capture($batch,'ZZBATCH_LOC',99,bin2hex(random_bytes(10)));}
$mode=$argv[1]??'basic';
if($mode==='seed') {
 foreach(array('pits_batch_events'=>'batch','pits_batch_audits'=>'batch','pits_batch_corrections'=>'batch','pits_batch_captures'=>'batch','hus'=>'batch') as $t=>$k)query('DELETE FROM '.$t.' WHERE '.$k." LIKE 'ZZBATCH_%'");
 query("DELETE FROM masterdata WHERE itemcode LIKE 'ZZBATCH_%'");query("DELETE FROM perpetuo WHERE itemcode LIKE 'ZZBATCH_%'");query("DELETE FROM locations WHERE wh='ZZBATCH_WH'");
 query("INSERT INTO locations(wh,location,area) VALUES('ZZBATCH_WH','ZZBATCH_LOC','ZZBATCH_AREA')");
 query("INSERT INTO masterdata(itemcode,description,cost_price,uom) VALUES('ZZBATCH_A','Fixture A',1.25,'EA'),('ZZBATCH_B','Fixture B',2.5,'EA')");
 query("INSERT INTO perpetuo(itemcode,onhand) VALUES('ZZBATCH_A',20),('ZZBATCH_B',15)");
 foreach(array('ZZBATCH_MULTI26','ZZBATCH_RACE26','ZZBATCH_FAIL26') as $batch) {
  query("INSERT INTO hus(batch,itemcode,qty,warehouse,area,description,area_description,uom) VALUES(?,'ZZBATCH_A',1.2,'ZZBATCH_WH','ZZBATCH_AREA','Fixture A','Fixture area','EA'),(?,'ZZBATCH_B',3.4,'ZZBATCH_WH','ZZBATCH_AREA','Fixture B','Fixture area','EA'),(?,'ZZBATCH_A',2.3,'ZZBATCH_WH','ZZBATCH_AREA','Fixture A','Fixture area','EA')",array($batch,$batch,$batch));
 }
 check(true,'QA fixtures seeded');exit;
}
if($mode==='global') {
 query("INSERT INTO locations(wh,location) SELECT 'ZZBATCH_OTHER','ZZBATCH_ALT' WHERE NOT EXISTS(SELECT 1 FROM locations WHERE location='ZZBATCH_ALT')");
 try{$m->capture('ZZBATCH_WEB26','ZZBATCH_ALT',99,'qa-global-duplicate');throw new RuntimeException('Alternate warehouse allowed recapture');}catch(UnexpectedValueException $e){check(true,'captured BATCH remains globally unique across other warehouse/location');}
 try{$m->capture('ZZBATCH_UNKNOWN26','ZZBATCH_ALT',99,'qa-unknown');throw new RuntimeException('Unknown BATCH captured');}catch(DomainException $e){check(true,'unknown BATCH cannot create a capture');}
 exit;
}
if($mode==='race') { try{capture('ZZBATCH_RACE26');echo "RACE_WIN\n";}catch(UnexpectedValueException $e){echo "RACE_DUPLICATE\n";}exit; }
if($mode==='failure') {
 try{capture('ZZBATCH_FAIL26');throw new RuntimeException('Failure injection was not triggered');}catch(RuntimeException $e){if(strpos($e->getMessage(),'not triggered')!==false)throw $e;}
 check((int)query("SELECT COUNT(*) n FROM hus WHERE batch='ZZBATCH_FAIL26' AND status='Used'")->row()->n===0,'failure rolled back ALL detail rows');
 check((int)query("SELECT COUNT(*) n FROM pits_batch_captures WHERE batch='ZZBATCH_FAIL26'")->row()->n===0,'failure rolled back unique header');
 check((int)query("SELECT COUNT(*) n FROM pits_batch_events WHERE batch='ZZBATCH_FAIL26' AND outcome='rejected'")->row()->n===1,'failure log persisted AFTER rollback');exit;
}
if($mode==='basic') {
 $baseline=$m->report('progress')['totals'];
 $r=capture('ZZBATCH_MULTI26');check($r['partidas']===3,'one scan captures three detail rows');
 check((int)query("SELECT COUNT(*) n FROM hus WHERE batch='ZZBATCH_MULTI26' AND status='Used'")->row()->n===3,'all persisted detail rows marked captured');
 try{capture('ZZBATCH_MULTI26');throw new RuntimeException('duplicate accepted');}catch(UnexpectedValueException $e){check(true,'second scan rejected');}
 $progress=$m->report('progress')['totals'];check($progress['Captured']===$baseline['Captured']+1 && $progress['Batches']===$baseline['Batches'],'progress counts ONE BATCH rather than THREE rows');
 $v=$m->report('variation');$a=array_values(array_filter($v['rows'],function($r){return $r['Item Code']==='ZZBATCH_A';}))[0];
 check(abs((float)$a['Counted']-7.0)<1e-9 && abs((float)$a['Counted USD']-8.75)<1e-9,'variation aggregates repeated article across all BATCH detail rows');
 $b=array_values(array_filter($v['rows'],function($r){return $r['Item Code']==='ZZBATCH_B';}))[0];check(abs((float)$b['Counted']-6.8)<1e-9,'variation includes second article');
 check(!isset($a['Type']) && count(array_filter(array_keys($a),function($s){return strpos($s,'Unit Price')!==false;}))===1,'variation has one Unit Price and no Type');
 check(ceil((float)$b['Variation'])===-8.0,'negative variation rounds toward positive infinity');
 $before=$m->report('variation');$ins=$m->inspect('ZZBATCH_MULTI26');
 $m->audit('ZZBATCH_MULTI26','ZZBATCH_WH','ZZBATCH_LOC','No','QA mismatch',99,'qa-audit',$ins['revision']);
 check($before===$m->report('variation'),'negative audit does not alter variation');
 check($progress===$m->report('progress')['totals'],'negative audit does not alter progress');
 check((int)query("SELECT COUNT(*) n FROM pits_batch_audits WHERE batch='ZZBATCH_MULTI26' AND result='No'")->row()->n===1,'audit result saved separately with snapshot');
 $id=$ins['rows'][0]['Id'];$edit=$m->editable($id);
 $m->correct($id,'4.2','ZZBATCH_A','QA correction',$edit['revision'],99,'qa-correction');
 check((int)query("SELECT COUNT(*) n FROM pits_batch_corrections WHERE detail_id=?",array($id))->row()->n===1,'correction writes before/after history');
 try{$m->correct($id,'9','ZZBATCH_A','stale change',$edit['revision'],99,'qa-stale');throw new RuntimeException('stale edit accepted');}catch(UnexpectedValueException $e){check(true,'stale correction rejected');}
 try{$m->audit('ZZBATCH_MULTI26','ZZBATCH_WH','ZZBATCH_LOC','Yes','stale',99,'qa-stale-audit',$ins['revision']);throw new RuntimeException('stale audit accepted');}catch(UnexpectedValueException $e){check(true,'audit requires the content actually inspected');}
 try{$m->capture('ZZBATCH_MULTI26','MISSING',99,'qa-location');throw new RuntimeException('invalid location accepted');}catch(DomainException $e){check(true,'unknown location rejected');}
 $c=(int)query("SELECT COUNT(*) n FROM hus WHERE batch='ZZBATCH_RACE26' AND status='Used'")->row()->n;
 check($c===3 && (int)query("SELECT COUNT(*) n FROM pits_batch_captures WHERE batch='ZZBATCH_RACE26'")->row()->n===1,'concurrent capture has ONE header and THREE details');
 check((int)query("SELECT COUNT(*) n FROM pits_batch_events WHERE batch='ZZBATCH_RACE26' AND outcome='committed'")->row()->n===1,'concurrent capture has ONE committed event');
 check(true,'core integration suite complete');exit;
}
throw new RuntimeException('Unknown test mode');
