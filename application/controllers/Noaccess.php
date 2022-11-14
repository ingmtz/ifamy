<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Noaccess extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('session');
	}
	public function index(){
		$data['sidebar']=$this->load->view('sidebar_view',array(),true);
        $data['header']=$this->load->view('header_view',array(),true);
		$this->load->view('noaccess_view', $data);
	}
}