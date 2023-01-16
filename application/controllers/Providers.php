<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Providers extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('providers_model');
		$this->load->library('datatable');
	}
	function index(){
		$data['providers']=$this->providers_model->getproviders();
		$this->load->view('providers_view', $data);
	}
	function newprovider(){
		$this->load->view('newproviders_view');
	}
	function save(){


		set_time_limit(0);
		ini_set('memory_limit','-1');

		
		$data=array();
		parse_str($_POST['data'], $data);

		$idprovider = $this->providers_model->saveprovider($data);

		if(isset($_POST['filename'])){

			$ds          = DIRECTORY_SEPARATOR;
			$storeFolder = 'uploads';
			$file = getcwd().$ds.$storeFolder.$ds.$_POST['filename'];
			$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
			$reader->open($file);

			$validations = array(
				0 => 'System',
				1 => 'Provider');
			
			$valid = true;
			$fileName = "";
			$ommitedrows=false;
			$datatoinsert=array();
			foreach ($reader->getSheetIterator() as $sheet) {
				if ($sheet->isActive()) {
					foreach ($sheet->getRowIterator() as $key => $row) {
						if (!$valid) {
							break;
						}

						if($key==1){
							foreach ($validations as $key => $value) {
								if(is_numeric($key)){
									if (isset($row[$key])) {
										if ($row[$key] != $value) {
											$valid = false;
											break;
										}
									}else{
										$valid = false;
										break;
									}
								}
							}
							
						}else if($key >= 2){
							
							$datatoinsert = array(
								'systemitemcode' => trim(strtoupper($this->issetor($row[0]))),
								'provideritemcode' => trim($this->issetor($row[1])),
								'idprovider' => $idprovider
							);

							$this->providers_model->saveproviderequivalency($data);
								
						}
					}
				}
			}

			$reader->close();
			unlink($file);
		}

	}

	function provideredit($idprovider){
		$data['provider'] = $this->providers_model->getprovider($idprovider);
		$this->load->view('provideredit_view', $data);
	}
	function update(){


		set_time_limit(0);
		ini_set('memory_limit','-1');

		
		$data=array();
		parse_str($_POST['data'], $data);

		$this->providers_model->updateprovider($_POST['idprovider'], $data);

		if(isset($_POST['filename'])){
			$this->providers_model->deleteproviderequivalencies($_POST['idprovider']);
			$ds          = DIRECTORY_SEPARATOR;
			$storeFolder = 'uploads';
			$file = getcwd().$ds.$storeFolder.$ds.$_POST['filename'];
			$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
			$reader->open($file);

			$validations = array(
				0 => 'System',
				1 => 'Provider');
			
			$valid = true;
			$fileName = "";
			$ommitedrows=false;
			$datatoinsert=array();
			foreach ($reader->getSheetIterator() as $sheet) {
				if ($sheet->isActive()) {
					foreach ($sheet->getRowIterator() as $key => $row) {
						if (!$valid) {
							break;
						}

						if($key==1){
							foreach ($validations as $key => $value) {
								if(is_numeric($key)){
									if (isset($row[$key])) {
										if ($row[$key] != $value) {
											$valid = false;
											break;
										}
									}else{
										$valid = false;
										break;
									}
								}
							}
							
						}else if($key >= 2){
							
							$datatoinsert = array(
								'systemitemcode' => trim(strtoupper($this->issetor($row[0]))),
								'provideritemcode' => trim($this->issetor($row[1])),
								'idprovider' => $_POST['idprovider']
							);

							$this->providers_model->saveproviderequivalency($data);
								
						}
					}
				}
			}

			$reader->close();
			unlink($file);
		}

	}

	function delete(){
		$data = array("active" => 0);
		$this->providers_model->updateprovider($_POST['idprovider'], $data);
	}
}