<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
/** Explicit BATCH entry points; legacy TAG/Openbox endpoints are not routed in this variant. */
class Batch extends CI_Controller {
    private $user;
    public function __construct() {
        parent::__construct();date_default_timezone_set('America/Mexico_City');
        $this->load->model('pits_batch_model','batch_model');
        $id=$this->session->userdata('batch_user_id');
        $this->user=$id?$this->db->select('Id,name,username,idprofile')->where(array('Id'=>$id,'active'=>1))->get('users')->row_array():null;
        $this->output->set_header('Cache-Control: no-store');
        $this->output->set_header('X-Content-Type-Options: nosniff');
        $this->output->set_header('X-Frame-Options: SAMEORIGIN');
    }
    private function json($data,$code=200) { $this->output->set_status_header($code)->set_content_type('application/json')->set_output(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE)); }
    private function requireUser($roles=array(1,2,3)) {
        if(!$this->user) { $this->json(array('ok'=>false,'message'=>'Please sign in / Inicia sesión.'),401);return false; }
        if(!in_array((int)$this->user['idprofile'],$roles,true)) { $this->json(array('ok'=>false,'message'=>'Access denied / Acceso denegado.'),403);return false; }
        return true;
    }
    private function post() {
        if($this->input->method()!=='post') { $this->json(array('ok'=>false,'message'=>'POST required.'),405);return false; } return true;
    }
    private function field($key,$max=200) {
        $v=$this->input->post($key);if(!is_string($v)) throw new DomainException('Missing field: '.$key);
        $v=trim($v);if($v==='' || mb_strlen($v)>$max || preg_match('/[\x00-\x1f]/',$v)) throw new DomainException('Invalid field: '.$key);
        return $v;
    }
    private function operation($fn) {
        $cid=bin2hex(random_bytes(12));
        // Release PHP session lock: concurrency must be enforced by DB, not hidden by a browser session.
        if(session_status()===PHP_SESSION_ACTIVE) session_write_close();
        try { $this->json(array('ok'=>true,'data'=>$fn($cid),'correlation'=>$cid)); }
        catch(DomainException $e) { $this->json(array('ok'=>false,'message'=>$e->getMessage(),'correlation'=>$cid),422); }
        catch(UnexpectedValueException $e) { $this->json(array('ok'=>false,'message'=>$e->getMessage(),'correlation'=>$cid),409); }
        catch(Throwable $e) { log_message('error','BATCH '.$cid.' '.$e->getMessage()); $this->json(array('ok'=>false,'message'=>'Operation not confirmed. Check its status before retrying / Operación no confirmada; consulta el estado antes de reintentar.','correlation'=>$cid),500); }
    }
    public function index() {
        if(!$this->user) { redirect('login');return; }
        redirect((int)$this->user['idprofile']===2?'capture':'reports/variation');
    }
    public function login() {
        if($this->user) { $this->index();return; }
        $error='';
        if($this->input->method()==='post') {
            $username=$this->input->post('username');$password=$this->input->post('password');
            $u=is_string($username)?$this->db->where(array('username'=>trim($username),'active'=>1))->get('users')->row_array():null;
            $valid=false;
            if($u && is_string($password)) {
                $info=password_get_info($u['password']);
                $valid=!empty($info['algo'])?password_verify($password,$u['password']):hash_equals((string)$u['password'],$password);
            }
            if($valid && in_array((int)$u['idprofile'],array(1,2,3),true)) {
                $this->session->sess_regenerate(true);$this->session->set_userdata('batch_user_id',(int)$u['Id']);
                redirect((int)$u['idprofile']===2?'capture':'reports/variation');return;
            }
            $error='Incorrect username or password / Usuario o contraseña incorrectos.';
        }
        $this->load->view('batch_login',array('error'=>$error));
    }
    public function logout() { if(!$this->post())return;$this->session->sess_destroy();redirect('login'); }
    public function screen($page='capture') {
        if(!$this->user) { redirect('login');return; }
        $roles=$page==='capture'?array(1,2,3):array(1,3);
        if($page==='import' || $page==='logs' || $page==='history') $roles=array(3);
        if(!$this->requireUser($roles))return;
        $this->load->view('batch_app',array('page'=>$page,'user'=>$this->user));
    }
    public function lookup() {
        if(!$this->requireUser() || !$this->post())return;
        $this->operation(function($cid){ return $this->batch_model->inspect($this->field('batch',100)); });
    }
    public function checklocation() {
        if(!$this->requireUser() || !$this->post())return;
        $this->operation(function($cid){
            $rows=$this->db->select('wh,location')->where('location',$this->field('location',200))->get('locations')->result_array();
            $wh=array_unique(array_column($rows,'wh'));
            if(!$rows) throw new DomainException('Location not found / Locación no encontrada.');
            return array('location'=>$rows[0]['location'],'warehouse'=>implode(' / ',$wh));
        });
    }
    public function capture() {
        if(!$this->requireUser() || !$this->post())return;
        $this->operation(function($cid){ return $this->batch_model->capture($this->field('batch',100),$this->field('location',200),(int)$this->user['Id'],$cid); });
    }
    public function audit() {
        if(!$this->requireUser(array(1,3)) || !$this->post())return;
        $this->operation(function($cid){
            $result=$this->field('result',3);if(!in_array($result,array('Yes','No'),true))throw new DomainException('Choose Yes or No.');
            $notes=$this->input->post('observations');if(!is_string($notes) || mb_strlen($notes)>4000)throw new DomainException('Invalid observations.');
            return $this->batch_model->audit($this->field('batch',100),$this->field('warehouse',200),$this->field('location',200),$result,$notes,(int)$this->user['Id'],$cid,$this->field('revision',64));
        });
    }
    public function editable() {
        if(!$this->requireUser(array(3)) || !$this->post())return;
        $this->operation(function($cid){ return $this->batch_model->editable($this->field('id',20)); });
    }
    public function correct() {
        if(!$this->requireUser(array(3)) || !$this->post())return;
        $this->operation(function($cid){
            $qty=$this->field('qty',40);if(!is_numeric($qty) || !is_finite((float)$qty) || (float)$qty<0 || (float)$qty>1e14)throw new DomainException('Invalid quantity.');
            return $this->batch_model->correct($this->field('id',20),$qty,$this->field('itemcode',50),$this->field('reason',500),$this->field('revision',64),(int)$this->user['Id'],$cid);
        });
    }
    public function report($kind='variation') {
        $roles=in_array($kind,array('logs','history'),true)?array(3):array(1,3);
        if(!$this->requireUser($roles))return;
        $this->operation(function($cid)use($kind){return $this->batch_model->report($kind);});
    }
    public function export($kind='variation') {
        $roles=in_array($kind,array('logs','history'),true)?array(3):array(1,3);
        if(!$this->requireUser($roles))return;
        try {
            $report=$this->batch_model->report($kind);
            $this->load->library('excel');$book=new PHPExcel();$sheet=$book->getActiveSheet();$sheet->setTitle(substr('BATCH '.$kind,0,31));
            $keys=$report['rows']?array_keys($report['rows'][0]):($report['totals']?array_keys($report['totals']):array('No records'));
            foreach($keys as $i=>$key) $sheet->setCellValueExplicitByColumnAndRow($i,1,$key,PHPExcel_Cell_DataType::TYPE_STRING);
            $rows=$report['rows'];if($report['totals'])$rows[]=$report['totals'];
            foreach($rows as $n=>$row) foreach($keys as $i=>$key) {
                $v=isset($row[$key])?$row[$key]:'';
                if($report['round'] && in_array($key,$report['numeric'],true) && is_numeric($v)){$rounded=ceil((float)$v);$v=$rounded==0?'0':(string)$rounded;}
                // Explicit strings preserve leading zeros, identifiers and neutralize Excel formula injection.
                $sheet->setCellValueExplicitByColumnAndRow($i,$n+2,(string)$v,PHPExcel_Cell_DataType::TYPE_STRING);
            }
            if(isset($report['units']) && $report['units']) {
                $detail=$book->createSheet();$detail->setTitle('Batches');$keys=array_keys($report['units'][0]);
                foreach($keys as $i=>$key)$detail->setCellValueExplicitByColumnAndRow($i,1,$key,PHPExcel_Cell_DataType::TYPE_STRING);
                foreach($report['units'] as $n=>$row)foreach($keys as $i=>$key)$detail->setCellValueExplicitByColumnAndRow($i,$n+2,(string)$row[$key],PHPExcel_Cell_DataType::TYPE_STRING);
            }
            $this->output->set_header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');header('Content-Disposition: attachment; filename="PITS-BATCH-'.$kind.'.xlsx"');
            $writer=new PHPExcel_Writer_Excel2007($book);$writer->save('php://output');
        } catch(Throwable $e) { log_message('error','BATCH export '.$e->getMessage());$this->json(array('ok'=>false,'message'=>'Export failed.'),500); }
    }
    public function importfile() {
        if(!$this->requireUser(array(3)) || !$this->post())return;
        $this->operation(function($cid){
            $kind=$this->field('kind',20);
            if(!in_array($kind,array('inventory','locations','rawtype'),true))throw new DomainException('Invalid import type.');
            if(!isset($_FILES['file']) || $_FILES['file']['error']!==UPLOAD_ERR_OK || $_FILES['file']['size']>20*1024*1024 || strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION))!=='xlsx')throw new DomainException('Choose an XLSX file, maximum 20 MB.');
            $this->load->library('batch_import');$records=$this->batch_import->parse($_FILES['file']['tmp_name'],$kind);
            return $this->batch_model->importRows($kind,$records,(int)$this->user['Id'],$cid);
        });
    }
    public function missing() { show_404(); }
}
