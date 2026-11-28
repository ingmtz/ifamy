<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
/** BATCH-only adapter. Source tables retained; no TAG source participates in BATCH reports. */
class Pits_batch_model extends CI_Model {
    private function q($sql, $args=array()) {
        $q=$this->db->query($sql,$args);
        if($q===false) throw new RuntimeException('Database operation failed');
        return $q;
    }
    private function now() { return date('Y-m-d H:i:s'); }
    private function rows($sql,$args=array()) { return $this->q($sql,$args)->result_array(); }
    public function event($action,$outcome,$batch,$uid,$cid,$detail) {
        $this->q('INSERT INTO pits_batch_events(batch,user_id,action,outcome,correlation,detail,created_at) VALUES(?,?,?,?,?,?,?)',
            array($batch,$uid,$action,$outcome,$cid,json_encode($detail,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),$this->now()));
    }
    private function begin($exclusive=false) {
        if(!$this->db->trans_begin()) throw new RuntimeException('Cannot start transaction');
        $this->q('SELECT id FROM pits_inventory_lock WHERE id=1 '.($exclusive?'FOR UPDATE':'LOCK IN SHARE MODE'));
    }
    private function commit() {
        if(!$this->db->trans_status() || !$this->db->trans_commit()) throw new RuntimeException('Cannot confirm transaction');
    }
    public function details($batch) {
        return $this->rows("SELECT h.batch AS BATCH,h.itemcode AS `Item Code`,COALESCE(NULLIF(h.description,''),m.description,'') AS Description,h.Id,
            h.qty AS Quantity,COALESCE(m.cost_price,0) AS `Unit Price USD`,h.qty*COALESCE(m.cost_price,0) AS `Amount USD`,
            h.warehouse AS WAREHOUSE,h.area AS AREA,COALESCE(h.area_description,'') AS `Area Description`,h.location AS Location,
            COALESCE(h.status,'Pending') AS Status,h.tag AS `Source HU`,h.uom AS UOM
            FROM hus h LEFT JOIN (SELECT itemcode,MAX(description) description,MAX(type) type,MAX(cost_price) cost_price FROM masterdata GROUP BY itemcode) m ON m.itemcode=h.itemcode
            WHERE h.batch=? ORDER BY h.Id",array($batch));
    }
    public function inspect($batch) {
        $rows=$this->details($batch);
        if(!$rows) throw new DomainException('BATCH not found / BATCH no encontrado.');
        $header=$this->q('SELECT * FROM pits_batch_captures WHERE batch=?',array($batch))->row_array();
        return array('rows'=>$rows,'capture'=>$header?:null,'revision'=>hash('sha256',json_encode($rows)));
    }
    public function capture($batch,$location,$uid,$cid) {
        try {
            $this->begin();
            $locs=$this->rows('SELECT DISTINCT wh FROM locations WHERE location=?',array($location));
            if(!$locs) throw new DomainException('Location not found / Locación no encontrada.');
            // Indexed row lock precedes state check. Concurrent requests serialize on the SAME BATCH.
            $items=$this->rows('SELECT Id,batch,status,qty,warehouse FROM hus WHERE batch=? ORDER BY Id FOR UPDATE',array($batch));
            if(!$items) throw new DomainException('BATCH not found / BATCH no encontrado.');
            $batch=$items[0]['batch']; // Persist the canonical imported identifier, not scanner casing.
            $warehouses=array_values(array_unique(array_column($locs,'wh')));
            if(count($warehouses)>1) {
                $matching=array_values(array_intersect($warehouses,array_unique(array_column($items,'warehouse'))));
                if(count($matching)!==1)throw new DomainException('Location is ambiguous for this BATCH; verify imported warehouse/location data.');
                $warehouse=$matching[0];
            } else $warehouse=$warehouses[0];
            if(mb_strlen($location)>50)throw new DomainException('Captured location exceeds the supported 50 characters.');

            foreach($items as $item) {
                if($item['status']==='Used') throw new UnexpectedValueException('BATCH already captured / BATCH ya capturado.');
                if($item['status']!==null && $item['status']!=='' && $item['status']!=='Pending') throw new DomainException('BATCH has an ineligible status.');
            }
            if($this->q('SELECT batch FROM pits_batch_captures WHERE batch=?',array($batch))->num_rows()) throw new UnexpectedValueException('BATCH already captured / BATCH ya capturado.');
            $this->q('INSERT INTO pits_batch_captures(batch,location,warehouse,user_id,captured_at,correlation) VALUES(?,?,?,?,?,?)',array($batch,$location,$warehouse,$uid,$this->now(),$cid));
            $this->q("UPDATE hus SET status='Used',location=?,iduser=?,datetimecaptured=? WHERE batch=?",array($location,$uid,$this->now(),$batch));
            if($this->db->affected_rows()!==count($items)) throw new RuntimeException('Incomplete BATCH update');
            $snapshot=$this->details($batch);
            if(count($snapshot)!==count($items))throw new RuntimeException('Incomplete capture snapshot');
            $this->event('capture','committed',$batch,$uid,$cid,array(
                'partidas'=>count($items),'location'=>$location,'warehouse'=>$warehouse,'rows'=>$snapshot
            ));
            $this->commit();
            return array('batch'=>$batch,'partidas'=>count($items),'message'=>'BATCH saved / BATCH guardado.','correlation'=>$cid);
        } catch(Throwable $e) {
            $this->db->trans_rollback();
            try { $this->event('capture',$e instanceof UnexpectedValueException?'duplicate':'rejected',$batch,$uid,$cid,array('location'=>$location,'reason'=>($e instanceof DomainException || $e instanceof UnexpectedValueException)?$e->getMessage():'Persistence failed')); }
            catch(Throwable $logError) { log_message('error','BATCH '.$cid.' capture rejected; database log unavailable'); }
            throw $e;
        }
    }
    public function audit($batch,$warehouse,$location,$result,$notes,$uid,$cid,$expected) {
        try {
            $this->begin();
            $this->q('SELECT Id FROM hus WHERE batch=? ORDER BY Id FOR UPDATE',array($batch));
            $data=$this->inspect($batch);
            if(!hash_equals($data['revision'],$expected))throw new UnexpectedValueException('BATCH content changed; inspect again before auditing.');
            if(!$this->q('SELECT Id FROM locations WHERE location=? AND wh=?',array($location,$warehouse))->num_rows()) throw new DomainException('Storage/location not found.');
            $this->q('INSERT INTO pits_batch_audits(batch,warehouse,location,result,observations,user_id,created_at,snapshot) VALUES(?,?,?,?,?,?,?,?)',
                array($batch,$warehouse,$location,$result,$notes,$uid,$this->now(),json_encode($data['rows'],JSON_UNESCAPED_UNICODE)));
            $this->event('audit','committed',$batch,$uid,$cid,array('result'=>$result));
            $this->commit();
            return array('message'=>'Audit recorded. Capture was NOT changed / Auditoría guardada sin modificar captura.');
        } catch(Throwable $e) { $this->db->trans_rollback(); throw $e; }
    }
    public function correct($id,$qty,$itemcode,$reason,$expected,$uid,$cid) {
        try {
            $this->begin();
            $before=$this->q('SELECT * FROM hus WHERE Id=? FOR UPDATE',array($id))->row_array();
            if(!$before || $before['status']!=='Used') throw new DomainException('Captured detail not found.');
            if(!hash_equals(hash('sha256',json_encode($before)),$expected)) throw new UnexpectedValueException('Record changed; reload before editing.');
            if(!$this->q('SELECT Id FROM masterdata WHERE itemcode=?',array($itemcode))->num_rows()) throw new DomainException('Item Code not found in masterdata.');
            $this->q('UPDATE hus SET qty=?,itemcode=?,description=NULL WHERE Id=?',array($qty,$itemcode,$id));
            $after=$this->q('SELECT * FROM hus WHERE Id=?',array($id))->row_array();
            $this->q('INSERT INTO pits_batch_corrections(batch,detail_id,user_id,reason,before_value,after_value,created_at) VALUES(?,?,?,?,?,?,?)',
                array($before['batch'],$id,$uid,$reason,json_encode($before),json_encode($after),$this->now()));
            $this->q('UPDATE pits_batch_captures SET revision=revision+1 WHERE batch=?',array($before['batch']));
            $this->event('correction','committed',$before['batch'],$uid,$cid,array('detail_id'=>$id,'reason'=>$reason));
            $this->commit();
            return array('message'=>'Correction saved with history / Corrección guardada con historial.');
        } catch(Throwable $e) { $this->db->trans_rollback(); throw $e; }
    }
    public function editable($id) {
        $row=$this->q('SELECT * FROM hus WHERE Id=?',array($id))->row_array();
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
            FROM (SELECT itemcode FROM masterdata UNION SELECT itemcode FROM perpetuo UNION SELECT itemcode FROM hus WHERE status='Used' AND batch IS NOT NULL AND TRIM(batch)<>'') i
            LEFT JOIN (SELECT itemcode,MAX(description) description,MAX(type) type,MAX(cost_price) cost_price FROM masterdata GROUP BY itemcode) m ON m.itemcode=i.itemcode
            LEFT JOIN (SELECT itemcode,SUM(onhand) onhand FROM perpetuo GROUP BY itemcode) p ON p.itemcode=i.itemcode
            LEFT JOIN (SELECT itemcode,SUM(qty) counted FROM hus WHERE status='Used' AND batch IS NOT NULL AND TRIM(batch)<>'' GROUP BY itemcode) c ON c.itemcode=i.itemcode
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
            $units=$this->rows("SELECT batch BATCH,GROUP_CONCAT(DISTINCT itemcode ORDER BY itemcode SEPARATOR ' / ') `Item Code`,
              GROUP_CONCAT(DISTINCT COALESCE(description,'') ORDER BY description SEPARATOR ' / ') Description,
              GROUP_CONCAT(DISTINCT warehouse ORDER BY warehouse SEPARATOR ' / ') WAREHOUSE,
              GROUP_CONCAT(DISTINCT area ORDER BY area SEPARATOR ' / ') AREA,
              GROUP_CONCAT(DISTINCT COALESCE(area_description,'') ORDER BY area_description SEPARATOR ' / ') `Area Description`,
              MIN(CASE WHEN status='Used' THEN 1 ELSE 0 END) captured
              FROM hus WHERE batch IS NOT NULL AND TRIM(batch)<>'' AND COALESCE(status,'')<>'Voided' GROUP BY batch");
            $groups=array();$tot=array('WAREHOUSE'=>'TOTAL','AREA'=>'','Area Description'=>'','Batches'=>0,'Captured'=>0,'Pending'=>0);
            foreach($units as $u) {
                $key=json_encode(array($u['WAREHOUSE'],$u['AREA'],$u['Area Description']));
                if(!isset($groups[$key])) $groups[$key]=array('WAREHOUSE'=>$u['WAREHOUSE'],'AREA'=>$u['AREA'],'Area Description'=>$u['Area Description'],'Batches'=>0,'Captured'=>0,'Pending'=>0);
                foreach(array('Batches'=>1,'Captured'=>(int)$u['captured'],'Pending'=>1-(int)$u['captured']) as $k=>$v) { $groups[$key][$k]+=$v;$tot[$k]+=$v; }
            }
            foreach($units as &$unit){$unit['Status']=(int)$unit['captured']?'Captured':'Pending';unset($unit['captured']);}unset($unit);
            return array('rows'=>array_values($groups),'units'=>$units,'totals'=>$tot,'round'=>false,'numeric'=>array());
        }
        if($kind==='total') $rows=$this->rows("SELECT h.batch BATCH,h.itemcode `Item Code`,COALESCE(NULLIF(h.description,''),m.description,'') Description,h.Id,h.qty Quantity,
            COALESCE(m.cost_price,0) `Unit Price USD`,h.qty*COALESCE(m.cost_price,0) `Amount USD`,h.warehouse WAREHOUSE,h.area AREA,
            COALESCE(h.area_description,'') `Area Description`,h.location Location,h.datetimecaptured `Captured At`,u.name `Captured By`
            FROM hus h LEFT JOIN (SELECT itemcode,MAX(description) description,MAX(type) type,MAX(cost_price) cost_price FROM masterdata GROUP BY itemcode) m ON m.itemcode=h.itemcode
            LEFT JOIN users u ON u.Id=h.iduser WHERE h.status='Used' AND h.batch IS NOT NULL AND TRIM(h.batch)<>'' ORDER BY h.batch,h.Id");
        elseif($kind==='audit') $rows=$this->rows('SELECT a.id,a.batch BATCH,a.warehouse WAREHOUSE,a.location Location,a.result Result,a.observations Observations,u.name Auditor,a.created_at Date,a.snapshot Content FROM pits_batch_audits a LEFT JOIN users u ON u.Id=a.user_id ORDER BY a.id DESC');
        elseif($kind==='history') {
            $rows=$this->rows('SELECT c.id,c.batch BATCH,c.detail_id Detail,u.name Administrator,c.reason Reason,c.before_value BeforeValue,c.after_value AfterValue,c.created_at Date FROM pits_batch_corrections c LEFT JOIN users u ON u.Id=c.user_id ORDER BY c.id DESC');
            foreach($rows as &$row) {
                foreach(array('BeforeValue','AfterValue') as $field) {
                    $value=json_decode($row[$field],true);
                    if(!is_array($value)) {
                        $row[$field]='Detalle histórico no disponible.';
                        continue;
                    }
                    unset($value['openbox']);
                    $row[$field]=json_encode($value,JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE);
                }
            }
            unset($row);
        }
        elseif($kind==='logs') {
            $rows=$this->rows('SELECT e.id,e.batch BATCH,u.name User,e.action Action,e.outcome Result,e.correlation Correlation,e.detail Detail,e.created_at Date FROM pits_batch_events e LEFT JOIN users u ON u.Id=e.user_id ORDER BY e.id DESC');
            foreach($rows as &$row) {
                if($row['Action']!=='capture' || $row['Result']!=='committed')continue;
                $detail=json_decode($row['Detail'],true);
                if(!isset($detail['rows'])) {
                    $detail['historical_note']='Captura histórica sin detalle original de partidas.';
                    $row['Detail']=json_encode($detail,JSON_UNESCAPED_UNICODE);
                }
            }
            unset($row);
        }
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
                // Imports refresh reference data; preserve ALL captured detail rows unchanged.
                $captured=array_column($this->rows("SELECT DISTINCT batch FROM hus WHERE status='Used' AND batch IS NOT NULL AND TRIM(batch)<>''"),'batch');
                $protected=array_fill_keys(array_map('strtolower',$captured),true);
                $this->validateCapturedBatches($protected,$records);
                $incomingItems=array_fill_keys(array_column($records,'material'),true);
                foreach($this->rows("SELECT DISTINCT itemcode FROM hus WHERE status='Used' AND TRIM(COALESCE(batch,''))<>''") as $detail)
                    if(!isset($incomingItems[$detail['itemcode']]))throw new DomainException('Import omits captured item '.$detail['itemcode'].'.');
                $this->q('DELETE FROM rawdata');$this->q('DELETE FROM masterdata');$this->q('DELETE FROM perpetuo');
                if($captured) $this->q('DELETE FROM hus WHERE batch NOT IN ('.implode(',',array_fill(0,count($captured),'?')).') OR batch IS NULL',$captured);
                else $this->q('DELETE FROM hus');
                $master=array();$perpetuo=array();
                foreach($records as $r) {
                    $this->insert('rawdata',array_intersect_key($r,array_flip(array('hu','material','material_description','batch','qty','um','location','wh','cost_price','area'))));
                    $master[$r['material']]=array('itemcode'=>$r['material'],'description'=>$r['material_description'],'cost_price'=>$r['cost_price'],'uom'=>$r['um']);
                    if(!isset($perpetuo[$r['material']])) $perpetuo[$r['material']]=0;
                    $perpetuo[$r['material']]+=(float)$r['qty'];
                    if($r['batch']!=='' && !isset($protected[strtolower($r['batch'])])) $this->insert('hus',array('tag'=>$r['hu'],'itemcode'=>$r['material'],'qty'=>$r['qty'],'warehouse'=>$r['wh'],'area'=>$r['area'],'description'=>$r['material_description'],'area_description'=>$r['area_description'],'uom'=>$r['um'],'batch'=>$r['batch'],'location'=>$r['location'],'plant'=>'Unknown','printcounter'=>0,'auditcounter'=>0));
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
    private function validateCapturedBatches($protected,$records) {
        if(!$protected)return;
        $signature=function($row) {
            return json_encode(array(
                (string)$row['hu'],(string)$row['material'],(float)$row['qty'],(string)$row['um']
            ),JSON_THROW_ON_ERROR);
        };
        $original=array();
        $source=$this->rows("SELECT r.batch,r.hu,r.material,r.qty,r.um FROM rawdata r
            INNER JOIN (SELECT DISTINCT batch FROM hus WHERE status='Used' AND TRIM(COALESCE(batch,''))<>'') c
            ON c.batch=r.batch");
        foreach($source as $row)$original[strtolower($row['batch'])][]=$signature($row);
        $incoming=array();
        foreach($records as $row) {
            $batch=strtolower($row['batch']);
            if(isset($protected[$batch]))$incoming[$batch][]=$signature($row);
        }
        foreach(array_keys($protected) as $batch) {
            if(!isset($incoming[$batch]))throw new DomainException('La carga omite el lote capturado '.$batch.'.');
            if(!isset($original[$batch]))throw new DomainException('No hay inventario original para validar el lote capturado '.$batch.'.');
            sort($incoming[$batch],SORT_STRING);
            sort($original[$batch],SORT_STRING);
            if($incoming[$batch]!==$original[$batch])
                throw new DomainException('La carga cambia las partidas del lote capturado '.$batch.'.');
        }
    }
    private function insert($table,$data) {
        if(!$this->db->insert($table,$data)) throw new RuntimeException('Import insert failed');
    }
}
