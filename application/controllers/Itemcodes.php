<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class itemcodes extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('itemcodes_model');
		$this->load->library('datatable');
	}
	public function index(){
		$this->load->view('itemcodes_view');
	}
	public function newitem(){
		$this->load->view('newitem_view');
	}
	public function saveitem(){
		//$_POST['iduser']=$this->session->userdata('ifatrimuserid');
		$this->itemcodes_model->saveitem($_POST);
		redirect(base_url(). 'itemcodes');
	}
	public function getitemcode(){
		$result=$this->itemcodes_model->getitemcode($_POST['itemcode']);
		echo json_encode($result);
	}
	public function itemedit($id){
		$data['item']=$this->itemcodes_model->getitemcode($id);
		$this->load->view('itemedit_view', $data);
	}
	public function saveedit($id){
		$this->itemcodes_model->saveedit($_POST, $id);
		redirect(base_url(). 'itemcodes');
	}
	public function validateitemcode(){
		$id=0;
		if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		$result=$this->itemcodes_model->validateitemcode(trim($_POST['itemcode']), $id);
		//print_r($result);
		echo (!isset($result->Id)) ? '{ "valid": true}' : '{ "valid": false}';
	}
	public function loaditems(){
		$query="select * from masterdata";
		$primaryKey = 'Id';

		$columns = array(
		    array( 'db' => 'itemcode', 'dt' => 0 ),
		    array( 'db' => 'description',  'dt' => 1 ),
		    array( 'db' => 'uom',   'dt' => 2 ),
		    array(
		        'db'        => 'cost_price',
		        'dt'        => 3,
		        'formatter' => function( $d, $row ) {
		            return '$'.number_format($d, 2, '.', ',');
		        }
		    ),
		    array( 'db' => 'item_group',     'dt' => 4 ),
		    array( 'db' => 'type',     'dt' => 5 ),
		    array(
		        'db'        => 'Id',
		        'dt'        => 6,
		        'formatter' => function( $d, $row ) {
		            return '<a href="'.base_url().'itemcodes/itemedit/'.$d.'"><i class="fa fa-edit" style="cursor:pointer;"></i></a> <a href="#" class="remove-item" data-id="'.$d.'"><i class="fa fa-remove" style="cursor:pointer;"></i></a>';
		        }
		    )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	public function removeitem(){
		$this->itemcodes_model->removeitem($_POST['id']);
	}
}	