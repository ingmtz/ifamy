<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
/** TAGS-only adapter. Source tables retained; no TAG source participates in TAGS reports. */
class Pits_tags_model extends CI_Model {
    private function q($sql, $args=array()) {
        $q=$this->db->query($sql,$args);
        if($q===false) throw new RuntimeException('Database operation failed');
        return $q;
    }
    private function now() { return date('Y-m-d H:i:s'); }
    private function rows($sql,$args=array()) { return $this->q($sql,$args)->result_array(); }
    public function event($action,$outcome,$tag,$uid,$cid,$detail) {
        $this->q('INSERT INTO pits_tag_events(tag,user_id,action,outcome,correlation,detail,created_at) VALUES(?,?,?,?,?,?,?)',
            array($tag,$uid,$action,$outcome,$cid,json_encode($detail,JSON_UNESCAPED_UNICODE),$this->now()));
    }
    private function begin($exclusive=false) {
        if(!$this->db->trans_begin()) throw new RuntimeException('Cannot start transaction');
        $this->q('SELECT id FROM pits_inventory_lock WHERE id=1 '.($exclusive?'FOR UPDATE':'LOCK IN SHARE MODE'));
    }
    private function commit() {
        if(!$this->db->trans_status() || !$this->db->trans_commit()) throw new RuntimeException('Cannot confirm transaction');
    }
    public function details($tag) {
        return $this->rows("SELECT h.tag AS TAG,h.itemcode AS `Item Code`,COALESCE(NULLIF(h.description,''),m.description,'') AS Description,h.Id,
            h.qty AS Quantity,COALESCE(m.cost_price,0) AS `Unit Price USD`,h.qty*COALESCE(m.cost_price,0) AS `Amount USD`,
            h.warehouse AS WAREHOUSE,h.area AS AREA,COALESCE(h.area_description,'') AS `Area Description`,h.location AS Location,
            COALESCE(h.status,'Pending') AS Status,h.uom AS UOM
            FROM tags h LEFT JOIN (SELECT itemcode,MAX(description) description,MAX(cost_price) cost_price FROM masterdata GROUP BY itemcode) m ON m.itemcode=h.itemcode
            WHERE h.tag=? ORDER BY h.Id",array($tag));
    }
    public function inspect($tag) {
        $rows=$this->details($tag);
        if(!$rows) throw new DomainException('TAG not found / TAG no encontrado.');
        $header=$this->q('SELECT * FROM pits_tag_captures WHERE tag=?',array($tag))->row_array();
        return array('rows'=>$rows,'capture'=>$header?:null,'revision'=>hash('sha256',json_encode($rows)));
    }
    public function capture($tag,$location,$itemcode,$qty,$uid,$cid) {
        try {
            $this->begin();
            $locs=$this->rows('SELECT DISTINCT wh FROM locations WHERE location=?',array($location));
            if(!$locs) throw new DomainException('Location not found / Locación no encontrada.');
            // Indexed row lock precedes state check. Concurrent requests serialize on the SAME TAG.
            $items=$this->rows('SELECT Id,tag,status,qty,warehouse FROM tags WHERE tag=? ORDER BY Id FOR UPDATE',array($tag));
            if(!$items) throw new DomainException('TAG not found / TAG no encontrado.');
            $tag=$items[0]['tag']; // Persist the canonical imported identifier, not scanner casing.
            $warehouses=array_values(array_unique(array_column($locs,'wh')));
            if(count($warehouses)>1) {
                $matching=array_values(array_intersect($warehouses,array_unique(array_column($items,'warehouse'))));
                if(count($matching)!==1)throw new DomainException('Location is ambiguous for this TAG; verify imported warehouse/location data.');
                $warehouse=$matching[0];
            } else $warehouse=$warehouses[0];
            if(mb_strlen($location)>50)throw new DomainException('Captured location exceeds the supported 50 characters.');

            if(count($items)!==1)throw new DomainException('Duplicate TAG identity in source; administrator review required.');
            if(!is_numeric($qty) || !is_finite((float)$qty) || (float)$qty<=0 || (float)$qty>1e14)throw new DomainException('Quantity must be greater than zero.');
            if(!$this->q('SELECT Id FROM masterdata WHERE itemcode=?',array($itemcode))->num_rows())throw new DomainException('Item Code not found.');
            foreach($items as $item) {
                if($item['status']==='Used') throw new UnexpectedValueException('TAG already captured / TAG ya capturado.');
                if($item['status']!==null && $item['status']!=='' && $item['status']!=='Pending' && $item['status']!=='Blank') throw new DomainException('TAG has an ineligible status.');
            }
            if($this->q('SELECT tag FROM pits_tag_captures WHERE tag=?',array($tag))->num_rows()) throw new UnexpectedValueException('TAG already captured / TAG ya capturado.');
            $this->q('INSERT INTO pits_tag_captures(tag,location,warehouse,user_id,captured_at,correlation) VALUES(?,?,?,?,?,?)',array($tag,$location,$warehouse,$uid,$this->now(),$cid));
            $this->q("UPDATE tags SET status='Used',location=?,iduser=?,datetimecaptured=?,itemcode=?,qty=?,description=NULL,warehouse=? WHERE tag=?",array($location,$uid,$this->now(),$itemcode,$qty,$warehouse,$tag));
            if($this->db->affected_rows()!==count($items)) throw new RuntimeException('Incomplete TAG update');
            $this->event('capture','committed',$tag,$uid,$cid,array('partidas'=>count($items),'location'=>$location));
            $this->commit();
            return array('tag'=>$tag,'partidas'=>count($items),'message'=>'TAG saved / TAG guardado.','correlation'=>$cid);
        } catch(Throwable $e) {
            $this->db->trans_rollback();
            try { $this->event('capture',$e instanceof UnexpectedValueException?'duplicate':'rejected',$tag,$uid,$cid,array('reason'=>$e instanceof LogicException?$e->getMessage():'Persistence failed')); }
            catch(Throwable $logError) { log_message('error','TAGS '.$cid.' capture rejected; database log unavailable'); }
            throw $e;
        }
    }
    public function audit($tag,$warehouse,$location,$result,$notes,$uid,$cid,$expected) {
        try {
            $this->begin();
            $this->q('SELECT Id FROM tags WHERE tag=? ORDER BY Id FOR UPDATE',array($tag));
            $data=$this->inspect($tag);
            if(!hash_equals($data['revision'],$expected))throw new UnexpectedValueException('TAG content changed; inspect again before auditing.');
            if(!$this->q('SELECT Id FROM locations WHERE location=? AND wh=?',array($location,$warehouse))->num_rows()) throw new DomainException('Storage/location not found.');
            $this->q('INSERT INTO pits_tag_audits(tag,warehouse,location,result,observations,user_id,created_at,snapshot) VALUES(?,?,?,?,?,?,?,?)',
                array($tag,$warehouse,$location,$result,$notes,$uid,$this->now(),json_encode($data['rows'],JSON_UNESCAPED_UNICODE)));
            $this->event('audit','committed',$tag,$uid,$cid,array('result'=>$result));
            $this->commit();
            return array('message'=>'Audit recorded. Capture was NOT changed / Auditoría guardada sin modificar captura.');
        } catch(Throwable $e) { $this->db->trans_rollback(); throw $e; }
    }
    public function correct($id,$qty,$itemcode,$reason,$expected,$uid,$cid) {
        try {
            $this->begin();
            $before=$this->q('SELECT * FROM tags WHERE Id=? FOR UPDATE',array($id))->row_array();
            if(!$before || $before['status']!=='Used') throw new DomainException('Captured detail not found.');
            if(!hash_equals(hash('sha256',json_encode($before)),$expected)) throw new UnexpectedValueException('Record changed; reload before editing.');
            if(!$this->q('SELECT Id FROM masterdata WHERE itemcode=?',array($itemcode))->num_rows()) throw new DomainException('Item Code not found in masterdata.');
            $this->q('UPDATE tags SET qty=?,itemcode=?,description=NULL WHERE Id=?',array($qty,$itemcode,$id));
            $after=$this->q('SELECT * FROM tags WHERE Id=?',array($id))->row_array();
            $this->q('INSERT INTO pits_tag_corrections(tag,detail_id,user_id,reason,before_value,after_value,created_at) VALUES(?,?,?,?,?,?,?)',
                array($before['tag'],$id,$uid,$reason,json_encode($before),json_encode($after),$this->now()));
            $this->q('UPDATE pits_tag_captures SET revision=revision+1 WHERE tag=?',array($before['tag']));
            $this->event('correction','committed',$before['tag'],$uid,$cid,array('detail_id'=>$id,'reason'=>$reason));
            $this->commit();
            return array('message'=>'Correction saved with history / Corrección guardada con historial.');
        } catch(Throwable $e) { $this->db->trans_rollback(); throw $e; }
    }
    public function editable($id) {
        $row=$this->q('SELECT * FROM tags WHERE Id=?',array($id))->row_array();
        if(!$row) throw new DomainException('Detail not found.');
        return array('row'=>$row,'revision'=>hash('sha256',json_encode($row)));
    }
    private function variation() {
        return $this->rows("SELECT i.itemcode AS `Item Code`,COALESCE(m.description,'') AS Description,
            COALESCE(p.onhand,0) AS `On Hand`,COALESCE(m.cost_price,0) AS `Unit Price USD`,
            COALESCE(p.onhand,0)*COALESCE(m.cost_price,0) AS `On Hand USD`,COALESCE(c.counted,0) AS Counted,
            COALESCE(c.counted,0)*COALESCE(m.cost_price,0) AS `Counted USD`,
            COALESCE(c.counted,0)-COALESCE(p.onhand,0) AS Variation,
            (COALESCE(c.counted,0)-COALESCE(p.onhand,0))*COALESCE(m.cost_price,0) AS `Variation USD`,
            ABS(COALESCE(c.counted,0)-COALESCE(p.onhand,0)) AS `Absolute Variation`,
            ABS((COALESCE(c.counted,0)-COALESCE(p.onhand,0))*COALESCE(m.cost_price,0)) AS `Absolute Variation USD`
            FROM (SELECT itemcode FROM masterdata UNION SELECT itemcode FROM perpetuo UNION SELECT itemcode FROM tags WHERE status='Used') i
            LEFT JOIN (SELECT itemcode,MAX(description) description,MAX(cost_price) cost_price FROM masterdata GROUP BY itemcode) m ON m.itemcode=i.itemcode
            LEFT JOIN (SELECT itemcode,SUM(onhand) onhand FROM perpetuo GROUP BY itemcode) p ON p.itemcode=i.itemcode
            LEFT JOIN (SELECT itemcode,SUM(qty) counted FROM tags WHERE status='Used' GROUP BY itemcode) c ON c.itemcode=i.itemcode
            WHERE i.itemcode IS NOT NULL ORDER BY i.itemcode");
    }
    public function report($kind) {
        if($kind==='variation') {
            $rows=$this->variation();$totals=array('Item Code'=>'TOTAL','Description'=>'');
            $numeric=array('On Hand','On Hand USD','Counted','Counted USD','Variation','Variation USD','Absolute Variation','Absolute Variation USD');
            foreach($numeric as $k) $totals[$k]=0;
            foreach($rows as $row) foreach($numeric as $k) $totals[$k]+=(float)$row[$k];
            return array('rows'=>$rows,'totals'=>$totals,'round'=>true,'numeric'=>array_merge($numeric,array('Unit Price USD')));
        }
        if($kind==='progress') {
            $units=$this->rows("SELECT tag TAG,GROUP_CONCAT(DISTINCT itemcode ORDER BY itemcode SEPARATOR ' / ') `Item Code`,
              GROUP_CONCAT(DISTINCT COALESCE(description,'') ORDER BY description SEPARATOR ' / ') Description,
              GROUP_CONCAT(DISTINCT warehouse ORDER BY warehouse SEPARATOR ' / ') WAREHOUSE,
              GROUP_CONCAT(DISTINCT area ORDER BY area SEPARATOR ' / ') AREA,
              GROUP_CONCAT(DISTINCT COALESCE(area_description,'') ORDER BY area_description SEPARATOR ' / ') `Area Description`,
              MIN(CASE WHEN status='Used' THEN 1 ELSE 0 END) captured
              FROM tags WHERE tag IS NOT NULL AND TRIM(tag)<>'' AND COALESCE(status,'')<>'Voided' GROUP BY tag");
            $groups=array();$tot=array('WAREHOUSE'=>'TOTAL','AREA'=>'','Area Description'=>'','TAGs'=>0,'Captured'=>0,'Pending'=>0);
            foreach($units as $u) {
                $key=json_encode(array($u['WAREHOUSE'],$u['AREA'],$u['Area Description']));
                if(!isset($groups[$key])) $groups[$key]=array('WAREHOUSE'=>$u['WAREHOUSE'],'AREA'=>$u['AREA'],'Area Description'=>$u['Area Description'],'TAGs'=>0,'Captured'=>0,'Pending'=>0);
                foreach(array('TAGs'=>1,'Captured'=>(int)$u['captured'],'Pending'=>1-(int)$u['captured']) as $k=>$v) { $groups[$key][$k]+=$v;$tot[$k]+=$v; }
            }
            foreach($units as &$unit){$unit['Status']=(int)$unit['captured']?'Captured':'Pending';unset($unit['captured']);}unset($unit);
            return array('rows'=>array_values($groups),'units'=>$units,'totals'=>$tot,'round'=>false,'numeric'=>array());
        }
        if($kind==='total') $rows=$this->rows("SELECT h.tag TAG,h.itemcode `Item Code`,COALESCE(NULLIF(h.description,''),m.description,'') Description,h.Id,h.qty Quantity,
            COALESCE(m.cost_price,0) `Unit Price USD`,h.qty*COALESCE(m.cost_price,0) `Amount USD`,h.warehouse WAREHOUSE,h.area AREA,
            COALESCE(h.area_description,'') `Area Description`,h.location Location,h.datetimecaptured `Captured At`,u.name `Captured By`
            FROM tags h LEFT JOIN (SELECT itemcode,MAX(description) description,MAX(cost_price) cost_price FROM masterdata GROUP BY itemcode) m ON m.itemcode=h.itemcode
            LEFT JOIN users u ON u.Id=h.iduser WHERE h.status='Used' ORDER BY h.tag,h.Id");
        elseif($kind==='audit') $rows=$this->rows('SELECT a.id,a.tag TAG,a.warehouse WAREHOUSE,a.location Location,a.result Result,a.observations Observations,u.name Auditor,a.created_at Date,a.snapshot Content FROM pits_tag_audits a LEFT JOIN users u ON u.Id=a.user_id ORDER BY a.id DESC');
        elseif($kind==='history') $rows=$this->rows('SELECT c.id,c.tag TAG,c.detail_id Detail,u.name Administrator,c.reason Reason,c.before_value BeforeValue,c.after_value AfterValue,c.created_at Date FROM pits_tag_corrections c LEFT JOIN users u ON u.Id=c.user_id ORDER BY c.id DESC');
        elseif($kind==='logs') $rows=$this->rows('SELECT e.id,e.tag TAG,u.name User,e.action Action,e.outcome Result,e.correlation Correlation,e.detail Detail,e.created_at Date FROM pits_tag_events e LEFT JOIN users u ON u.Id=e.user_id ORDER BY e.id DESC');
        elseif($kind==='typeraw') {
            $map=$this->rows("SELECT material,GROUP_CONCAT(DISTINCT type_raw ORDER BY type_raw SEPARATOR ' / ') type_raw FROM rawtype GROUP BY material");
            $types=array_column($map,'type_raw','material');$group=array();
            foreach($this->variation() as $r) {
                $t=isset($types[$r['Item Code']])?$types[$r['Item Code']]:'Unclassified';
                if(!isset($group[$t])) $group[$t]=array('Type Raw'=>$t,'On Hand USD'=>0,'Counted USD'=>0,'Variation USD'=>0);
                foreach(array('On Hand USD','Counted USD','Variation USD') as $k) $group[$t][$k]+=(float)$r[$k];
            }
            $rows=array_values($group);
        } else throw new DomainException('Report not found.');
        return array('rows'=>$rows,'totals'=>null,'round'=>false,'numeric'=>array());
    }
    public function importRows($kind,$records,$uid,$cid) {
        try {
            $this->begin(true);
            if($kind==='inventory') {
                $incoming=array_column($records,'material');foreach($this->rows("SELECT DISTINCT itemcode FROM tags WHERE status='Used'") as $c)if(!in_array($c['itemcode'],$incoming,true))throw new DomainException('Import omits captured item '.$c['itemcode']);
                $this->q('DELETE FROM rawdata');$this->q('DELETE FROM masterdata');$this->q('DELETE FROM perpetuo');
                $master=array();$perpetuo=array();
                foreach($records as $r) {
                    $this->insert('rawdata',array_intersect_key($r,array_flip(array('material','material_description','batch','qty','um','location','wh','cost_price','area'))));
                    $master[$r['material']]=array('itemcode'=>$r['material'],'description'=>$r['material_description'],'cost_price'=>$r['cost_price'],'uom'=>$r['um']);
                    if(!isset($perpetuo[$r['material']])) $perpetuo[$r['material']]=0;
                    $perpetuo[$r['material']]+=(float)$r['qty'];
                }
                foreach($master as $r) $this->insert('masterdata',$r);
                foreach($perpetuo as $item=>$qty) $this->insert('perpetuo',array('itemcode'=>$item,'onhand'=>$qty));
            } elseif($kind==='locations' || $kind==='rawtype') {
                $table=$kind==='locations'?'locations':'rawtype';$this->q('DELETE FROM '.$table);
                foreach($records as $r) $this->insert($table,$r);
            } else throw new DomainException('Invalid import type.');
            $this->event('import','committed',null,$uid,$cid,array('kind'=>$kind,'rows'=>count($records)));
            $this->commit();
            return array('message'=>count($records).' rows imported atomically / filas importadas. Existing captures preserved.');
        } catch(Throwable $e) { $this->db->trans_rollback(); throw $e; }
    }
    public function createRange($start,$end,$warehouse,$area,$uid,$cid) {
        if(!ctype_digit($start)||!ctype_digit($end)||strlen($start)>15||strlen($end)>15||strlen($start)!==strlen($end)||(int)$end<(int)$start||(int)$end-(int)$start>9999)throw new DomainException('Invalid TAG range (maximum 10000, equal width).');
        try {$this->begin(true);
            if(!$this->q('SELECT Id FROM locations WHERE wh=? AND area=?',array($warehouse,$area))->num_rows())throw new DomainException('Warehouse/area not found.');
            for($i=(int)$start;$i<=(int)$end;$i++){
                $tag=str_pad((string)$i,strlen($start),'0',STR_PAD_LEFT);
                if($this->q('SELECT Id FROM tags WHERE tag=?',array($tag))->num_rows())throw new DomainException('TAG already exists: '.$tag);
                $this->insert('tags',array('tag'=>$tag,'warehouse'=>$warehouse,'area'=>$area,'status'=>'Blank','printcounter'=>0,'auditcounter'=>0));
            }
            $this->event('range','committed',null,$uid,$cid,array('start'=>$start,'end'=>$end));$this->commit();return array('message'=>'TAG range created.');
        }catch(Throwable $e){$this->db->trans_rollback();throw $e;}
    }
    private function insert($table,$data) {
        if(!$this->db->insert($table,$data)) throw new RuntimeException('Import insert failed');
    }
}
