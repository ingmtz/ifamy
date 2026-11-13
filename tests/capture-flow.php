<?php
// No framework bootstrap or real database: run actual controller branches with a fake DB.
define('BASEPATH', __DIR__);
class MY_Controller {}
require __DIR__.'/../application/controllers/Capture.php';
class FakeSession { function userdata($key) { return 1; } }
class FakeDB {
 public $db_debug=true, $used=false, $missing=false, $badLocation=false, $fail=false, $race=false, $writes=array(), $table;
 function where($key,$value) { return $this; }
 function get($table) { $this->table=$table; return $this; }
 function row() { if ($this->table==='locations') return $this->badLocation ? null : (object)array('location'=>'R1'); return $this->missing ? null : (object)array('tag'=>'HU1','status'=>$this->used?'Used':'Available'); }
 function update($table,$values) { if (!$this->fail && !$this->race) { $this->writes[]=$values; $this->used=true; } return !$this->fail; }
 function affected_rows() { return $this->race ? 0 : 1; }
}
$checks=0;
function check($condition,$label) { global $checks; ++$checks; if (!$condition) throw new Exception($label); }
$runtime=sys_get_temp_dir().'/pits-tests-'.getmypid(); mkdir($runtime); chdir($runtime);
foreach (array('HU'=>'hu','Batch'=>'batch') as $mode=>$key) {
 $method='checkCode'.$mode;
 $call=function($post,$db=null) use ($method) { $db=$db ?: new FakeDB; $c=(new ReflectionClass('Capture'))->newInstanceWithoutConstructor(); $c->db=$db; $c->session=new FakeSession; $_POST=$post; ob_start(); $c->$method(); $raw=ob_get_clean(); $data=json_decode($raw,true); check(is_array($data),'Every request must return JSON'); return array($data,$db); };
 $base=array('location'=>'R1',$key=>'HU1','openbox'=>'','quantity'=>'');
 list($r,$db)=$call($base); check($r['status'] && !$r['isComplete'] && !$db->writes,'HU/batch validation must not save');
 list($r,$db)=$call(array_merge($base,array('openbox'=>'0'))); check($r['isComplete'] && count($db->writes)===1 && !isset($db->writes[0]['qty']),'Closed box preserves original qty');
 list($r)=$call(array_merge($base,array('openbox'=>'0')),$db); check(!$r['status'] && count($db->writes)===1,'Repeat must not save');
 list($r,$db)=$call(array_merge($base,array('openbox'=>'1'))); check($r['status'] && !$r['isComplete'] && !$db->writes,'Open box confirmation must wait');
 foreach(array('1','7.5') as $qty) { list($r,$db)=$call(array_merge($base,array('openbox'=>'1','quantity'=>$qty))); check($r['isComplete'] && $db->writes[0]['qty']==(float)$qty,'Actual operator qty saved'); }
 foreach(array('0','-1','abc','1e309') as $qty) { list($r,$db)=$call(array_merge($base,array('openbox'=>'1','quantity'=>$qty))); check(!$r['status'] && !$db->writes,'Invalid qty rejected'); }
 foreach(array(array('openbox'=>'0','quantity'=>'0'),array('openbox'=>'2'),array('openbox'=>'','quantity'=>'5')) as $bad) { list($r,$db)=$call(array_merge($base,$bad)); check(!$r['status'] && !$db->writes,'Invalid state rejected'); }
 foreach(array('missing','badLocation','used','fail','race') as $flag) { $db=new FakeDB; $db->$flag=true; list($r,$db)=$call(array_merge($base,array('openbox'=>'0')),$db); check(!$r['status'] && !$db->writes && $db->db_debug,'Invalid persistence outcome must not report success'); }
 list($r,$db)=$call(array()); check(!$r['status'] && !$db->writes,'Missing fields return JSON');
}
foreach(glob($runtime.'/*') as $file) unlink($file); chdir(sys_get_temp_dir()); rmdir($runtime);
echo "$checks controller checks passed\n";
