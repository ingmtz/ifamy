<?php
// Execute only via CLI against the disposable HUS QA database. Never deployed to docroot.
if(PHP_SAPI!=='cli')exit(1);
$runtime=getenv('PITS_HUS_CONFIG');$secret=json_decode(file_get_contents($runtime),true);
if($secret['database']!=='pits_dev_hus_qa_20261121')throw new RuntimeException('QA-only test guard');
$code=getenv('PITS_HUS_CODE')?:'/var/www/novarum/PITS-HUS/';
define('BASEPATH',$code.'system/');define('APPPATH',$code.'application/');define('ENVIRONMENT','production');define('VIEWPATH',APPPATH.'views/');
function log_message($level,$message) { error_log($level.': '.$message); }
require BASEPATH.'core/Common.php';require BASEPATH.'database/DB.php';
require APPPATH.'config/database.php';
class CI_Model { public $db; }
require APPPATH.'models/Pits_hus_model.php';
$m=new Pits_hus_model();$m->db=DB($db['default'],true);
function query($sql,$args=array()) { global $m;$q=$m->db->query($sql,$args);if($q===false)throw new RuntimeException('Test SQL failed');return $q; }
function check($condition,$message){if(!$condition)throw new RuntimeException('FAIL: '.$message);echo 'PASS: '.$message."\n";}
function capture($hu){global $m;return $m->capture($hu,'ZZHUS_LOC',99,bin2hex(random_bytes(10)));}
$mode=$argv[1]??'basic';
if($mode==='seed') {
 foreach(array('pits_hu_events'=>'hu','pits_hu_audits'=>'hu','pits_hu_corrections'=>'hu','pits_hu_captures'=>'hu','hus'=>'tag') as $t=>$k)query('DELETE FROM '.$t.' WHERE '.$k." LIKE 'ZZHUS_%'");
 query("DELETE FROM masterdata WHERE itemcode LIKE 'ZZHUS_%'");query("DELETE FROM perpetuo WHERE itemcode LIKE 'ZZHUS_%'");query("DELETE FROM locations WHERE wh='ZZHUS_WH'");
 query("INSERT INTO locations(wh,location,area) VALUES('ZZHUS_WH','ZZHUS_LOC','ZZHUS_AREA')");
 query("INSERT INTO masterdata(itemcode,description,cost_price,uom) VALUES('ZZHUS_A','Fixture A',1.25,'EA'),('ZZHUS_B','Fixture B',2.5,'EA')");
 query("INSERT INTO perpetuo(itemcode,onhand) VALUES('ZZHUS_A',20),('ZZHUS_B',15)");
 foreach(array('ZZHUS_MULTI26','ZZHUS_RACE26','ZZHUS_FAIL26') as $hu) {
  query("INSERT INTO hus(tag,itemcode,qty,warehouse,area,description,area_description,uom) VALUES(?,'ZZHUS_A',1.2,'ZZHUS_WH','ZZHUS_AREA','Fixture A','Fixture area','EA'),(?,'ZZHUS_B',3.4,'ZZHUS_WH','ZZHUS_AREA','Fixture B','Fixture area','EA'),(?,'ZZHUS_A',2.3,'ZZHUS_WH','ZZHUS_AREA','Fixture A','Fixture area','EA')",array($hu,$hu,$hu));
 }
 check(true,'QA fixtures seeded');exit;
}
if($mode==='global') {
 query("INSERT INTO locations(wh,location) SELECT 'ZZHUS_OTHER','ZZHUS_ALT' WHERE NOT EXISTS(SELECT 1 FROM locations WHERE location='ZZHUS_ALT')");
 try{$m->capture('ZZHUS_WEB26','ZZHUS_ALT',99,'qa-global-duplicate');throw new RuntimeException('Alternate warehouse allowed recapture');}catch(UnexpectedValueException $e){check(true,'captured HU remains globally unique across other warehouse/location');}
 try{$m->capture('ZZHUS_UNKNOWN26','ZZHUS_ALT',99,'qa-unknown');throw new RuntimeException('Unknown HU captured');}catch(DomainException $e){check(true,'unknown HU cannot create a capture');}
 exit;
}
if($mode==='race') { try{capture('ZZHUS_RACE26');echo "RACE_WIN\n";}catch(UnexpectedValueException $e){echo "RACE_DUPLICATE\n";}exit; }
if($mode==='failure') {
 try{capture('ZZHUS_FAIL26');throw new RuntimeException('Failure injection was not triggered');}catch(RuntimeException $e){if(strpos($e->getMessage(),'not triggered')!==false)throw $e;}
 check((int)query("SELECT COUNT(*) n FROM hus WHERE tag='ZZHUS_FAIL26' AND status='Used'")->row()->n===0,'failure rolled back ALL detail rows');
 check((int)query("SELECT COUNT(*) n FROM pits_hu_captures WHERE hu='ZZHUS_FAIL26'")->row()->n===0,'failure rolled back unique header');
 check((int)query("SELECT COUNT(*) n FROM pits_hu_events WHERE hu='ZZHUS_FAIL26' AND outcome='rejected'")->row()->n===1,'failure log persisted AFTER rollback');exit;
}
if($mode==='basic') {
 $baseline=$m->report('progress')['totals'];
 $r=capture('ZZHUS_MULTI26');check($r['partidas']===3,'one scan captures three detail rows');
 check((int)query("SELECT COUNT(*) n FROM hus WHERE tag='ZZHUS_MULTI26' AND status='Used'")->row()->n===3,'all persisted detail rows marked captured');
 try{capture('ZZHUS_MULTI26');throw new RuntimeException('duplicate accepted');}catch(UnexpectedValueException $e){check(true,'second scan rejected');}
 $progress=$m->report('progress')['totals'];check($progress['Captured']===$baseline['Captured']+1 && $progress['HUs']===$baseline['HUs'],'progress counts ONE HU rather than THREE rows');
 $v=$m->report('variation');$a=array_values(array_filter($v['rows'],function($r){return $r['Item Code']==='ZZHUS_A';}))[0];
 check(abs((float)$a['Counted']-7.0)<1e-9 && abs((float)$a['Counted USD']-8.75)<1e-9,'variation aggregates repeated article across all HU detail rows');
 $b=array_values(array_filter($v['rows'],function($r){return $r['Item Code']==='ZZHUS_B';}))[0];check(abs((float)$b['Counted']-6.8)<1e-9,'variation includes second article');
 check(!isset($a['Type']) && count(array_filter(array_keys($a),function($s){return strpos($s,'Unit Price')!==false;}))===1,'variation has one Unit Price and no Type');
 check(ceil((float)$b['Variation'])===-8.0,'negative variation rounds toward positive infinity');
 $before=$m->report('variation');$ins=$m->inspect('ZZHUS_MULTI26');
 $m->audit('ZZHUS_MULTI26','ZZHUS_WH','ZZHUS_LOC','No','QA mismatch',99,'qa-audit',$ins['revision']);
 check($before===$m->report('variation'),'negative audit does not alter variation');
 check($progress===$m->report('progress')['totals'],'negative audit does not alter progress');
 check((int)query("SELECT COUNT(*) n FROM pits_hu_audits WHERE hu='ZZHUS_MULTI26' AND result='No'")->row()->n===1,'audit result saved separately with snapshot');
 $id=$ins['rows'][0]['Id'];$edit=$m->editable($id);
 $m->correct($id,'4.2','ZZHUS_A','QA correction',$edit['revision'],99,'qa-correction');
 check((int)query("SELECT COUNT(*) n FROM pits_hu_corrections WHERE detail_id=?",array($id))->row()->n===1,'correction writes before/after history');
 try{$m->correct($id,'9','ZZHUS_A','stale change',$edit['revision'],99,'qa-stale');throw new RuntimeException('stale edit accepted');}catch(UnexpectedValueException $e){check(true,'stale correction rejected');}
 try{$m->audit('ZZHUS_MULTI26','ZZHUS_WH','ZZHUS_LOC','Yes','stale',99,'qa-stale-audit',$ins['revision']);throw new RuntimeException('stale audit accepted');}catch(UnexpectedValueException $e){check(true,'audit requires the content actually inspected');}
 try{$m->capture('ZZHUS_MULTI26','MISSING',99,'qa-location');throw new RuntimeException('invalid location accepted');}catch(DomainException $e){check(true,'unknown location rejected');}
 $c=(int)query("SELECT COUNT(*) n FROM hus WHERE tag='ZZHUS_RACE26' AND status='Used'")->row()->n;
 check($c===3 && (int)query("SELECT COUNT(*) n FROM pits_hu_captures WHERE hu='ZZHUS_RACE26'")->row()->n===1,'concurrent capture has ONE header and THREE details');
 check((int)query("SELECT COUNT(*) n FROM pits_hu_events WHERE hu='ZZHUS_RACE26' AND outcome='committed'")->row()->n===1,'concurrent capture has ONE committed event');
 check(true,'core integration suite complete');exit;
}
if($mode==='import') {
 require APPPATH.'libraries/Hus_import.php';$parser=new Hus_import();
 $valid=$parser->parse('/var/www/.pits-private/hus/qa-inventory.xlsx','inventory');
 check(count($valid)===3 && $valid[0]['hu']===$valid[1]['hu'],'XLSX parser retains multirow HU');
 $before=query('SELECT COUNT(*) n FROM hus')->row()->n;
 try{$parser->parse('/var/www/.pits-private/hus/qa-invalid.xlsx','inventory');throw new RuntimeException('invalid XLSX accepted');}catch(DomainException $e){check(query('SELECT COUNT(*) n FROM hus')->row()->n===$before,'invalid XLSX rejected before database writes');}
 // Remove QA captures ONLY in disposable QA DB to exercise a clean initial import.
 query('DELETE FROM hus');query('DELETE FROM pits_hu_captures');
 $m->importRows('inventory',$valid,99,'qa-import');
 check((int)query('SELECT COUNT(*) n FROM hus')->row()->n===3,'valid XLSX import preserves all three rows');
 capture('ZZHUS_IMPORT26');
 $before=$m->report('total');$m->importRows('inventory',$valid,99,'qa-reimport');
 check($m->report('total')===$before,'reimport preserves captured detail and quantities');
 $invalid=$valid;$invalid[0]['hu']='OTHER';$invalid[1]['hu']='OTHER';
 try{$m->importRows('inventory',$invalid,99,'qa-missing');throw new RuntimeException('omitted capture accepted');}catch(DomainException $e){check($m->report('total')===$before,'import missing a captured HU rejected with rollback');}
 check((int)query("SELECT COUNT(*) n FROM hus WHERE tag='ZZHUS_IMPORT26' AND status='Used'")->row()->n===2,'imported multipartida HU captures completely');exit;
}
throw new RuntimeException('Unknown test mode');
