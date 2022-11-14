<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Areas extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('areas_model');
	}
	public function index(){
		$data['areas']=$this->areas_model->getareas();
		$this->load->view('areas_view', $data);
	}
	public function newarea(){
		$this->load->view('newarea_view');
	}
	public function savearea(){
		$this->areas_model->savearea($_POST);
		redirect(base_url(). 'areas');
	}
	public function areaedit($id){
		$data['area']=$this->areas_model->getarea($id);
		$this->load->view('areaedit_view', $data);
	}
	public function saveedit($id){
		$this->areas_model->saveedit($_POST, $id);
		redirect(base_url(). 'areas');
	}
	public function delete(){
		$this->areas_model->delete($_POST['id']);
	}
	/*public function validateusername(){
		$id=0;
		if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		$result=$this->areas_model->validateusername(trim($_POST['username']), $id);
		//print_r($result);
		echo (sizeof($result)==0) ? '{ "valid": true}' : '{ "valid": false}';
	}*/
}