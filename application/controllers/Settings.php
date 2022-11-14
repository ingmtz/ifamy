<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('settings_model');
		$this->load->library('datatable');
	}
	function index(){
		$data['settings']=$this->settings_model->getsettings();
		$this->load->view('settings_view', $data);
	}
	function updatesettings(){
		$params=array();
		parse_str($_POST['data'], $params);
		$this->settings_model->updatesettings($params);
		$this->session->set_userdata('inventorytype', $this->config->item('title').' '.$params['inventorytype']);
	}
}