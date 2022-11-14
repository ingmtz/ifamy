<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scannermanagement extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('scannermanagement_model');
		$this->load->library('datatable');
	}
	function loadscanners(){
		$query="select a.Id, a.serial, a.brand, a.notes, a.assignmenttime, a.assignmentstatus, b.name as userassignment, a.area 
		from scanners a 
		left join users b on a.assigneduser=b.Id
		where a.active=1";
		$primaryKey = 'Id';

		$columns = array(
		    array( 'db' => 'serial', 'dt' => 0 ),
		    array( 'db' => 'brand',  'dt' => 1 ),
		    array( 'db' => 'notes',   'dt' => 2 ),
		    //array( 'db' => 'warehouse',   'dt' => 3 ),
		    //array( 'db' => 'location',   'dt' => 4 ),
		    array( 'db' => 'area',   'dt' => 3 ),
		    array( 'db' => 'userassignment',     'dt' => 4 , 
				 'formatter' => function($d, $row){
                	if ($row['assignmentstatus'] == 0) {
                		return "None";
                	}else{
                		return $d;
                	}
                }),
		    array( 'db' => 'assignmenttime',     'dt' => 5 , 
				 'formatter' => function($d, $row){
                	if ($row['assignmentstatus'] == 0) {
                		return "None";
                	}else{
                		return $d;
                	}
                }),
		    array(
		        'db'        => 'Id',
		        'dt'        => 6,
		        'formatter' => function( $d, $row ) {
		        	$html = '<a href="'.base_url().'scannermanagement/scanneredit/'.$d.'" style="margin-right:10px;" title="Edit scanner"><i class="fa-2x fa fa-edit" style="cursor:pointer;"></i></a>';
		        	if ($row['assignmentstatus'] == 0) {
	                    $html .= '<a style="margin-right:10px;" href="#" class="assign-scanner" data-id="'.$d.'" title="Assign scanner"> <i class="fa-2x fa fa-check-circle"></i></a>';
	                }else{
	                	$html .= '<a style="margin-right:10px;" href="#"  class="unassign-scanner" data-id="'.$d.'" title="Unassign scanner"> <i class="fa-2x fa fa-times-circle"></i></a>';
	                }
		        	$html .= '<a style="margin-right:10px;" href="#"  class="history-scanner" data-id="'.$d.'" title="Log"> <i class="fa-2x fa fa-history"></i></a>';
		            $html .= '<a style="margin-right:10px;" href="#" class="remove-scanner" data-id="'.$d.'" title="Delete scanner"><i class="fa-2x fa fa-remove" style="cursor:pointer;"></i></a>';

		            return $html;
		        }
		    ),
		    array( 'db' => 'assignmentstatus',     'dt' => 7 )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	function scanners(){
		$data['users']=$this->scannermanagement_model->getUsers();
		$data['areas']=$this->scannermanagement_model->getAreas();
		$this->load->view('scannermanagementscanners_view', $data);
	}
	function newscanner(){
		$this->load->view('scannermanagementnewscanner_view');
	}
	function validatescanner(){
		$id=0;
		if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		$result=$this->scannermanagement_model->validatescanner(trim($_POST['serial']), $id);
		//print_r($result);
		echo !isset($result->Id) ? '{ "valid": true}' : '{ "valid": false}';
	}
	function validateuser(){
		if($_POST['assigneduser']=='0'){
			echo '{ "valid": true}';
		}else{
			$id=0;
			if(isset($_POST['id'])){
				$id=$_POST['id'];
			}
			$result=$this->scannermanagement_model->validateuser(trim($_POST['assigneduser']), $id);
			echo !isset($result->Id) ? '{ "valid": true}' : '{ "valid": false}';
		}
	}
	function savescanner(){
		$_POST['creationuser']=$this->session->userdata('ifatrimuserid');
		$_POST['active']=1;
		$this->scannermanagement_model->savescanner($_POST);
		redirect(base_url(). 'scannermanagement/scanners');
	}
	function scanneredit($id){
		$data['scanner']=$this->scannermanagement_model->getscanner($id);
		$this->load->view('scannermanagementscanneredit_view', $data);
	}
	function saveeditscanner($id){
		$this->scannermanagement_model->saveeditscanner($_POST, $id);
		redirect(base_url(). 'scannermanagement/scanners');
	}
	function removescanner(){
		$this->scannermanagement_model->removescanner($_POST['id']);
	}
	function assignscanner($id){
		$tmparr=explode('|', $_POST['area']);
		$_POST['plant']=$tmparr[0];
		$_POST['area']=$tmparr[1];
		$_POST['assignator'] = $this->session->userdata('ifatrimuserid');
		$_POST['assignmentstatus'] = 1;
		$_POST['assignmenttime'] = $this->scannermanagement_model->saveeditscanner($_POST,$id);
		$_POST['unassignator'] = $this->scannermanagement_model->getunassignator($id);
		$_POST['unassignmenttime'] = $this->scannermanagement_model->getunassignmenttime($id);
		$_POST['idscanner'] = $id;
		$this->scannermanagement_model->setrecordscanner($_POST);
	}
	function unassignscanner($id){
		$data['assigneduser'] = 0;
		$data['plant'] = '';
		$data['area'] = '';
		$assignedUser = $this->scannermanagement_model->getassigneduser($id);
		$data['assignmentstatus'] = 0;
		$data['unassignator'] = $this->session->userdata('ifatrimuserid');
		$data['assignmenttime'] = $this->scannermanagement_model->saveeditscanner($data,$id);
		$data['unassignmenttime'] = $this->scannermanagement_model->getunassignmenttime($id);
		$data['assignator'] = $this->scannermanagement_model->getassignator($id);
		$data['assigneduser'] = $assignedUser;
		$data['idscanner'] = $id;
		$this->scannermanagement_model->setrecordscanner($data);
	}
	function historyscanner($id){
		$historyscanner = $this->scannermanagement_model->gethistoryscanner($id);
		$html = '';
		foreach ($historyscanner as $key => $value) {
			$html .= '<tr>';
			$html .= '<td>'.$value->assignment.'</td>';
			$html .= '<td>'.$value->assigneduser.'</td>';
			$html .= '<td>'.$value->assignator.'</td>';
			$html .= '<td>'.$value->datetime.'</td>';
			$html .= '<td>'.$value->notes.'</td>';
			$html .= '</tr>';
		}
		echo $html;
	}
	/*public function getitemcode(){
		$result=$this->scannermanagement_model->getitemcode($_POST['itemcode']);
		echo json_encode($result);
	}*/
}	