<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Capture extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('capture_model');
		$this->load->library(array('datatable','excel'));
	}

	public function mobile(){
		$data['wh'] = $this->session->userdata('ifatrimplant');
		$this->load->view('capture_mobile_view', $data);
	}

	public function checklocation(){
		$result=$this->db->where('location', $_POST['location'])->get('locations')->row(); //->where('wh', $_POST['wh'])
		if($result){
			echo json_encode(array('status'=>true));
		}else{
			echo json_encode(array('status'=>false, 'message'=>'Location not found'));
		}
	}

	public function checkhu(){
		$result=$this->capture_model->checkTag($_POST['tag']);
		if($result){
			if($result->status == 'Used'){
				echo json_encode(array('status'=>false, 'message'=>'HU already scanned'));
				return;
			}
			// Check if the wh (storage location) is correct
			if($result->warehouse != $this->session->userdata('ifatrimplant')){
				echo json_encode(array('status'=>false, 'message'=>'HU not in this storage location'));
				return;
			}

			// Update the tag
			$this->db->where('tag', $_POST['tag'])->update('tags', 
				array('status'=> 'Used', 'datetimecaptured'=> date('Y-m-d H:i:s'), 'iduser'=> $this->session->userdata('ifatrimuserid'), 'warehouse'=> $this->session->userdata('ifatrimplant'), 'location'=> $_POST['location'], 'openbox'=> $_POST['openbox']));

			echo json_encode(array('status'=>true));
		}else{
			// Save hu not found
			$this->db->insert('hu_not_found', array('hu'=> $_POST['tag'], 'datetimecaptured'=> date('Y-m-d H:i:s'), 'iduser'=> $this->session->userdata('ifatrimuserid'), 'warehouse'=> $this->session->userdata('ifatrimplant'), 'location'=> $_POST['location'], 'openbox'=> $_POST['openbox']));

			echo json_encode(array('status'=>false, 'message'=>'HU not found'));
		}
	}


	public function index(){
		$data['mode']='1';//capture or report
		$data['locations']=$this->capture_model->getalllocations();
		$data['validationstep']=$this->capture_model->getStep();
		$data['warehouse']=$this->capture_model->getwarehouseByArea($this->session->userdata('ifatrimplant'), $this->session->userdata('ifatrimarea'));
		$data['counters']=$this->capture_model->getcounters();
		$data['auditors']=$this->capture_model->getauditors();
		$data['config']=$this->capture_model->getConfig();
		$data['title']=$this->lang->line('Capture');
		$this->load->view('capture_view',$data);
	}
	public function gettag(){
		$result=$this->capture_model->gettag($_POST['tag']);
		echo json_encode($result);
	}
	public function getitemcode(){
		$result=$this->capture_model->getitemcode($_POST['itemcode']);
		echo json_encode($result);
	}
	public function getlocations(){
		$result=$this->capture_model->getlocations($_POST['wh']);
		echo json_encode($result);
	}
	public function updatecapture(){
		$_POST['iduser']=$this->session->userdata('ifatrimuserid');
		$_POST['itemcode']=strtoupper($_POST['itemcode']);
		if($_POST['status']=='Blank' || $_POST['status']=='Voided'){
			$_POST['itemcode']='';
			$_POST['qty']='';
			$_POST['warehouse']='';
			$_POST['location']='';
		}
		$this->capture_model->updatecapture($_POST);
		$result=$this->capture_model->gettag($_POST['tag']);
		echo json_encode($result);
	}
	public function resetcapture(){
		$this->capture_model->resetcapture($_POST['id']);
	}
	/*function loaddata(){
        $query="select a.*, b.description, b.uom, b.type, d.name, b.cost_price, e.name as counter, f.name as auditor, g.area as areadesc
					from tags a 
                    left join masterdata b on a.itemcode=b.itemcode
                    left join users d on a.iduser=d.Id
                    left join users e on a.idcounter=e.Id
                    left join areas g on g.Id=a.area
                    left join users f on a.iduseraudit=f.Id where a.iduser=".$this->session->userdata('ifatrimuserid')." and g.area='".$this->session->userdata('ifatrimarea')."'";
        $primaryKey = 'Id';
        //echo $query;exit(0);

		$columns = array(
		    array( 'db' => 'tag', 'dt' => 0 ),
		    array( 'db' => 'areadesc',  'dt' => 1 ),
		    array( 'db' => 'warehouse',  'dt' => 2 ),
		    array( 'db' => 'location',  'dt' => 3 ),
		    array( 'db' => 'itemcode',  'dt' => 4 ),
		    array( 'db' => 'status',  'dt' => 5 ),
		    array(
		        'db'        => 'qty',
		        'dt'        => 6,
		        'formatter' => function( $d, $row ) {
		        	return ''.number_format($d, 2, '.', ',');//$
		        }
		    ),
		    array( 'db' => 'openbox',  'dt' => 7 ),
		    array(
		        'db'        => 'cost_price',
		        'dt'        => 8,
		        'formatter' => function( $d, $row ) {
		        	return '$'.number_format($d, 2, '.', ',');
		        }
		    ),
		    array(
		        'db'        => 'cost_price',
		        'dt'        => 9,
		        'formatter' => function( $d, $row ) {
		        	return '$'.number_format($d*$row['qty'], 2, '.', ',');
		        }
		    ),
		    array( 'db' => 'description',  'dt' => 10 ),
		    array( 'db' => 'uom',  'dt' => 11 ),
		    //array( 'db' => 'type',  'dt' => 9 ),
		    array( 'db' => 'name',  'dt' => 12 ),
		    array( 'db' => 'counter',  'dt' => 13 ),
		    array( 'db' => 'auditor',  'dt' => 14 ),
		    array( 'db' => 'reasontoedit',  'dt' => 15 ),
		    array( 'db' => 'precounted',  'dt' => 16 ),
		    array(
		        'db'        => 'Id',
		        'dt'        => 17,
		        'formatter' => function( $d, $row ) {
		        	$str='';
		        	if($this->session->userdata('ifatrimidprofile')==1 || $this->session->userdata('ifatrimidprofile')==3){
		        		$str='<i class="fa fa-edit" style="cursor:pointer;"></i><i class="fa fa-remove" style="cursor:pointer;"></i>';
		        	}
		        	return $str;
		        }
		    )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		//print_r($data);
		$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}*/

	public function audit(){
		$data['wh'] = $this->session->userdata('ifatrimplant');
		$this->load->view('capture_audit_view', $data);
	}

	public function checkhuaudit(){
		$result=$this->capture_model->checkTag($_POST['tag']);
		if($result){
			if($result->status != 'Used'){
				echo json_encode(array('status'=>false, 'message'=>'HU has not been scanned yet'));
				return;
			}
			echo json_encode(array('status'=>true, 'data'=> $result));
		}else{
			echo json_encode(array('status'=>false, 'message'=>'HU not found'));
		}
	}

	public function checkitemcode(){
		$result=$this->capture_model->checkItemCode($_POST['itemcode']);
		if($result){
			echo json_encode(array('status'=>true));
		}else{
			echo json_encode(array('status'=>false, 'message'=>'Itemcode not found'));
		}
	}

	public function saveAudit(){
		$dataAudited = array(
			"tag" => $_POST['hu']['tag'],
			"itemcode" => $_POST['hu']['itemcode'],
			"qty" => $_POST['hu']['qty'],
			"location" => $_POST['hu']['location'],
			"uom" => $_POST['hu']['uom'],
			"reasontoedit" => $_POST['correct'] == "true" ? "" : $_POST['reason'],
			"resultaudit" => $_POST['correct'] == "true" ? "Correct" : "Incorrect",
			"openbox" => $_POST['hu']['openbox'],
			"iduseraudit" => $this->session->userdata('ifatrimuserid'),
			"auditcounter" => $_POST['hu']['auditcounter'] + 1
		);
		$this->capture_model->saveAudit($dataAudited);
		echo json_encode(array('status'=>true));
	}

}