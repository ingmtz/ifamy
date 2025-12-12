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

	public function checkCode(){
		$response = array(
			'status' => false,
			'message' => '',
			'isComplete' => false,
			'tag' => isset($_POST['tag']) ? $_POST['tag'] : '',
			'itemcode' => isset($_POST['itemcode']) ? $_POST['itemcode'] : '',
			'openbox' => isset($_POST['openbox']) ? $_POST['openbox'] : '',
			'quantity' => isset($_POST['quantity']) ? $_POST['quantity'] : '',
			'location' => isset($_POST['location']) ? $_POST['location'] : '',
		);

		// Validate tag
		if($_POST['tag'] != '' && $_POST['itemcode'] == '' && $_POST['openbox'] == '' && $_POST['quantity'] == '' && $_POST['location'] == ''){
			$tag = $this->db->where('tag', $_POST['tag'])->get('tags')->row();
			if(!$tag){
				$response['status']=false;
				$response['message']='Tag not found';
				$response['tag']='';
				echo json_encode($response);
				return;
			}
			// Check if tag already used
			if($tag->status == 'Used'){
				$response['status']=false;
				$response['message']='Tag already scanned';
				$response['tag']='';
				echo json_encode($response);
				return;
			}
			$response['status']=true;
			$response['message']= 'Tag correct, capture HU, Batch or Part Number';
			echo json_encode($response);
			return;
		}

		// Validate part number
		if($_POST['tag'] != '' && $_POST['itemcode'] != '' && $_POST['openbox'] == '' && $_POST['location'] == ''){ //&& $_POST['quantity'] == '' 
			// Check itemcode exists
			$itemcode=$this->capture_model->getitemcode($_POST['itemcode']);
			if(!$itemcode){
				// Maybe it's a batch number?
				$batchcode=$this->db->where('batch', $_POST['itemcode'])->get('hus')->row();
				if($batchcode){
					$response['status']=true;
					$response['itemcode']= $batchcode->itemcode;
					$response['quantity']= $batchcode->qty;
					$response['message']= 'Batch number correct, capture open box option';
					echo json_encode($response);
					return;
				}

				$response['status']=false;
				$response['message']='Part number not found';
				$response['itemcode']='';
				echo json_encode($response);
				return;
			}
			$response['status']=true;
			$response['message']= 'Part number correct, capture open box option';
			echo json_encode($response);
			return;
		}

		// Validate openbox
		if($_POST['tag'] != '' && $_POST['itemcode'] != '' && $_POST['openbox'] != '' && $_POST['quantity'] == '' && $_POST['location'] == ''){
			if($_POST['openbox'] == ''){
				$response['status']=false;
				$response['message']='Please select open box option';
				$response['openbox']='';
				echo json_encode($response);
				return;
			}
			if($_POST['openbox'] == '1'){
				// Open box selected, capture quantity
				$response['status']=true;
				$response['message']= 'Open box option selected, capture quantity';
				echo json_encode($response);
				return;
			} else{
				// Not open box, ask for quantity
				$response['status']=true;
				$response['message']= 'Open box option not selected, capture quantity';
				echo json_encode($response);
				return;
			}
		}

		// Validate quantity
		if($_POST['tag'] != '' && $_POST['itemcode'] != '' && $_POST['quantity'] != '' && $_POST['location'] == ''){//&& $_POST['openbox'] == '1' 
			if(!is_numeric($_POST['quantity']) || floatval($_POST['quantity']) <= 0){
				$response['status']=false;
				$response['message']='Quantity must be a positive number';
				$response['quantity']='';
				echo json_encode($response);
				return;
			}
			// Ask for location
			$response['status']=true;
			$response['message']= 'Quantity captured, capture location';
			echo json_encode($response);
			return;
		}

		// Validate location
		if($_POST['tag'] != '' && $_POST['itemcode'] != '' && $_POST['quantity'] != '' && $_POST['openbox'] != ''){
			// Check location exists
			if($_POST['location'] == ''){
				$response['status']=true;
				$response['message']='Please capture location';
				$response['location']='';
				echo json_encode($response);
				return;
			}
			$loc=$this->db->where('location', $_POST['location'])->get('locations')->row();
			if(!$loc){
				$response['status']=false;
				$response['message']='Location not found';
				$response['location']='';
				echo json_encode($response);
				return;
			}
			// Complete capture
			$this->db->where('tag', $_POST['tag'])->update('tags', 
				array(
					'status'=> 'Used', 
					'itemcode'=> strtoupper($_POST['itemcode']), 
					'qty'=> floatval($_POST['quantity']), 
					'iduser'=> $this->session->userdata('ifatrimuserid'), 
					'datetimecaptured'=> date('Y-m-d H:i:s'), 
					'warehouse'=> $this->session->userdata('ifatrimplant'), 
					'location'=> $_POST['location'], 
					'openbox'=> $_POST['openbox']
				)
			);
			$response['status']=true;
			$response['message']='Tag captured successfully';
			$response['isComplete']=true;
			echo json_encode($response);
		}
	}

	public function checkhu_OLD(){
		$type='hu';
		$result=$this->capture_model->checkHU($_POST['tag']);
		if(!$result){
			$type='tag';
			$result=$this->capture_model->checkTag($_POST['tag']);
		}
		if($result){
			if($type == 'hu' && $result->status == 'Used'){
				echo json_encode(array('status'=>false, 'message'=> strtoupper($type) . ' already scanned'));
				return;
			}

			if($type == 'tag' && $result->status != null){
				echo json_encode(array('status'=>false, 'message'=> strtoupper($type) . ' already scanned'));
				return;
			}
			// Check if the wh (storage location) is correct
			// if($result->warehouse != $this->session->userdata('ifatrimplant')){
			// 	echo json_encode(array('status'=>false, 'message'=> strtoupper($type) . ' not in this storage location'));
			// 	return;
			// }

			if($type == 'tag'){
				if($_POST['partnumber'] != '' && $_POST['quantity'] != ''){
					// Check is a positive number
					if(!is_numeric($_POST['quantity']) || floatval($_POST['quantity']) <= 0){
						echo json_encode(array('status'=>false, 'message'=>'Quantity must be a positive number', 'isTag'=> true));
						return;
					}
					// Update the tag with partnumber and quantity
					$this->db->where('tag', $_POST['tag'])->update('tags', 
						array('status'=> 'Used', 'itemcode'=> strtoupper($_POST['partnumber']), 'qty'=> floatval($_POST['quantity']), 'iduser'=> $this->session->userdata('ifatrimuserid'), 'datetimecaptured'=> date('Y-m-d H:i:s'), 'warehouse'=> $this->session->userdata('ifatrimplant'), 'location'=> $_POST['location'], 'openbox'=> $_POST['openbox']));
					echo json_encode(array('status'=>true));
					return;
				}

				if($_POST['partnumber'] && $_POST['partnumber'] != ''){
					// Check itemcode exists
					$itemcode=$this->capture_model->getitemcode($_POST['partnumber']);
					if(!$itemcode){
						echo json_encode(array('status'=>false, 'message'=>'Part number not found', 'isTag'=> true));
						return;
					}
					echo json_encode(array('status'=>true, 'message'=> 'Part number correct, capture quantity', 'isTag'=> true));
					return;
				}

				echo json_encode(array('status'=>true, 'message'=> 'TAG found, capture part number', 'isTag'=> true));
				return;
			}

			// Update the hu
			if($type == 'hu'){
				$this->db->where('tag', $_POST['tag'])
						 ->or_where('batch', $_POST['tag'])
						 ->update('hus', 
							array('status'=> 'Used', 'datetimecaptured'=> date('Y-m-d H:i:s'), 'iduser'=> $this->session->userdata('ifatrimuserid'), 'warehouse'=> $this->session->userdata('ifatrimplant'), 'location'=> $_POST['location'], 'openbox'=> $_POST['openbox']));
				//echo $this->db->last_query();exit;

				echo json_encode(array('status'=>true));
			}
		}else{
			// Save hu not found
			$this->db->insert('hu_not_found', array('hu'=> $_POST['tag'], 'datetimecaptured'=> date('Y-m-d H:i:s'), 'iduser'=> $this->session->userdata('ifatrimuserid'), 'warehouse'=> $this->session->userdata('ifatrimplant'), 'location'=> $_POST['location'], 'openbox'=> $_POST['openbox']));

			echo json_encode(array('status'=>false, 'message'=>'HU or TAG not found'));
		}
	}

	// Medica Capture
	public function medica(){
		$data['wh'] = $this->session->userdata('ifatrimplant');
		$this->load->view('capture_medica_view', $data);
	}

	public function checkCodeMedica(){
		$response = array(
			'status' => false,
			'message' => '',
			'isComplete' => false,
			'location' => isset($_POST['location']) ? $_POST['location'] : '',
			'hu' => isset($_POST['hu']) ? $_POST['hu'] : '',
			'openbox' => isset($_POST['openbox']) ? $_POST['openbox'] : '',
			'quantity' => isset($_POST['quantity']) ? $_POST['quantity'] : ''
		);

		// Validate location
		if($_POST['location'] != '' && $_POST['hu'] == '' && $_POST['openbox'] == '' && $_POST['quantity'] == ''){
			// Check location exists
			$loc=$this->db->where('location', $_POST['location'])->get('locations')->row();
			if(!$loc){
				$response['status']=false;
				$response['message']='Location not found';
				$response['location']='';
				echo json_encode($response);
				return;
			}
			$response['status']=true;
			$response['message']= 'Location correct, capture HU';
			echo json_encode($response);
			return;
		}

		// Validate HU
		if($_POST['location'] != '' && $_POST['hu'] != '' && $_POST['openbox'] == '' && $_POST['quantity'] == ''){
			$hu = $this->db->where('tag', $_POST['hu'])->get('hus')->row();
			if(!$hu){
				$response['status']=false;
				$response['message']='HU not found';
				$response['hu']='';
				echo json_encode($response);
				return;
			}
			// Check if HU already used
			if($hu->status == 'Used'){
				$response['status']=false;
				$response['message']='HU already scanned';
				$response['hu']='';
				echo json_encode($response);
				return;
			}
			$response['status']=true;
			$response['message']= 'HU correct, capture open box option';
			echo json_encode($response);
			return;
		}

		// Validate openbox
		if($_POST['location'] != '' && $_POST['hu'] != '' && $_POST['openbox'] != '' && $_POST['quantity'] == ''){
			if($_POST['openbox'] == ''){
				$response['status']=false;
				$response['message']='Please select open box option';
				$response['openbox']='';
				echo json_encode($response);
				return;
			}
			if($_POST['openbox'] == '1'){
				// Open box selected, capture quantity
				$response['status']=true;
				$response['message']= 'Open box option selected, capture quantity';
				echo json_encode($response);
				return;
			}else{
				// Not open box, complete capture
				// Update the hu
				$this->db->where('tag', $_POST['hu'])
						 ->update('hus', 
							array(
								'status'=> 'Used', 
								'datetimecaptured'=> date('Y-m-d H:i:s'), 
								'iduser'=> $this->session->userdata('ifatrimuserid'), 'warehouse'=> $this->session->userdata('ifatrimplant'), 'location'=> $_POST['location'], 
								'openbox'=> $_POST['openbox']));

				$response['status']=true;
				$response['message']='HU captured successfully';
				$response['isComplete']=true;
				echo json_encode($response);
				return;
			}
		}

		// Validate quantity
		if($_POST['location'] != '' && $_POST['hu'] != '' && $_POST['openbox'] == '1' && $_POST['quantity'] != ''){
			if(!is_numeric($_POST['quantity']) || floatval($_POST['quantity']) <= 0){
				$response['status']=false;
				$response['message']='Quantity must be a positive number';
				$response['quantity']='';
				echo json_encode($response);
				return;
			}

			// Update the hu
			$this->db->where('tag', $_POST['hu'])
					 ->update('hus', 
						array(
							'status'=> 'Used', 
							'qty'=> floatval($_POST['quantity']),
							'datetimecaptured'=> date('Y-m-d H:i:s'), 
							'iduser'=> $this->session->userdata('ifatrimuserid'), 'warehouse'=> $this->session->userdata('ifatrimplant'), 'location'=> $_POST['location'], 
							'openbox'=> $_POST['openbox']));

			$response['status']=true;
			$response['message']='HU captured successfully';
			$response['isComplete']=true;
			echo json_encode($response);
			return;
		}
	}

	// End Medica Capture

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