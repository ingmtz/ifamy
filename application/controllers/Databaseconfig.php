<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Databaseconfig extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('databaseconfig_model');
	}
	public function index(){
		$this->load->view('databaseconfig_view');
	}
	function cleandatabase(){
		$this->databaseconfig_model->cleandatabase();
		redirect(base_url().'login/logout');
	}
	function cleanmaindata(){
		$this->databaseconfig_model->cleanmaindata();
		redirect(base_url().'login/logout');
	}
	function listdatabases(){
		$html='';
		$files = glob('backups/*.gz');
		usort($files, function($a, $b) {
		    return filemtime($a) < filemtime($b);
		});
		foreach($files as $archivo){
		    $temp = explode(".", $archivo);
		    if (!is_dir($archivo) && isset($temp[1])){
		    	$labelsize='bytes';
		    	$filesize=filesize($archivo);
		    	if($filesize>=1024){
		    		$filesize=$filesize/1024;
		    		$labelsize='KB';
		    	}
		    	if($filesize>=1024 && $labelsize=='KB'){
		    		$filesize=$filesize/1024;
		    		$labelsize='MB';
		    	}
		    	$filesize=round($filesize, 2);
		    $html.='<tr>';
			$html.='	<td><span class="label border-left-primary label-striped">'.str_replace('.sql.gz', '', basename($archivo)).'</span></td>';
			$html.='	<td>'.date('F d Y, H:i:s', filemtime($archivo)).'</td>';
			$html.='	<td>'.$filesize.' '.$labelsize.'</td>';
			$html.='	<td>';
			$html.='		<a href="#" class="restore" data-name="'.basename($archivo).'"><i class="icon-database-refresh"></i> '.$this->lang->line('Restore').'</a>';
			$html.='	</td>';
			$html.='	<td>';
			$html.='		<a href="#" class="remove" data-name="'.basename($archivo).'" style="color:red;"><i class="icon-database-remove"></i> '.$this->lang->line('Remove').'</a>';
			$html.='	</td>';
			$html.='</tr>';
		    }
		}
		echo $html;
	}
	function createbackup(){
		error_reporting(E_ALL);
		// Set script max execution time
		set_time_limit(900); // 15 minutes
		$name = isset($_POST['name']) ? $_POST['name'] : 'test';
		$this->load->library('Backup_Database');
		$backupDatabase = new Backup_Database();
		$result = $backupDatabase->backupTables($name, '*', 'backups') ? 'OK' : 'KO';
		echo $result;
	}
	function restorebackup(){
		error_reporting(E_ALL);
		// Set script max execution time
		set_time_limit(900); // 15 minutes
		if(isset($_POST['name'])){
			$name = $_POST['name'];
			$this->load->library('Restore_Database');

			$restoreDatabase = new Restore_Database();
			$result = $restoreDatabase->restoreDb($name, 'backups') ? 'OK' : 'KO';
			//redirect(base_url().'login/logout');
		}else{
			//redirect(base_url().'databaseconfig');
		}
	}
	function removebackup(){
		unlink('backups/'.$_POST['name']);
	}
	public function newdatabase(){
		$this->load->view('newdatabase_view');
	}
}