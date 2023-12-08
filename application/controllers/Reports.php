<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."/third_party/Spout/Autoloader/autoload.php";
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
//https://github.com/box/spout

class Reports extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('reports_model');
		$this->load->library(array('datatable','excel'));
	}
	/*public function totalcapture(){
		$data['warehouse']=$this->reports_model->getwarehouse();
		//$data['totalcapture']=$this->reports_model->totalcapture();
		$this->load->view('reporttotalcapture_view',$data);
	}*/
	public function totalcapture(){
		$data['mode']='2';//capture or report
		$data['locations']=$this->reports_model->getalllocations();
		$data['warehouse']=$this->reports_model->getwarehouse();
		$data['counters']=$this->reports_model->getcounters();
		$data['auditors']=$this->reports_model->getauditors();
		$data['config']=$this->reports_model->getconfig();
		$data['title']=$this->lang->line('Total capture report');
		$this->load->view('capture_view',$data);
	}
	function loaddataauditreport(){
		$query="select a.*, b.description, b.uom, b.type, d.name, b.cost_price, e.name as counter, f.name as auditor, g.area as areadesc, case when a.toaudit=1 or a.openbox='Ok' then 'Yes' else 'No' end as toaudit2
					from tags a 
                    left join masterdata b on a.itemcode=b.itemcode
                    left join users d on a.iduser=d.Id
                    left join users e on a.idcounter=e.Id
                    left join users f on a.iduseraudit=f.Id
                    left join areas g on g.Id=a.area
                    where (not a.iduseraudit is null) and a.iduseraudit>0";
        $primaryKey = 'Id';

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
		    array( 'db' => 'toaudit2',  'dt' => 10 ),
		    array( 'db' => 'auditcounter',  'dt' => 11 ),
		    array( 'db' => 'description',  'dt' => 12 ),
		    array( 'db' => 'uom',  'dt' => 13 ),
		    //array( 'db' => 'type',  'dt' => 9 ),
		    array( 'db' => 'name',  'dt' => 14 ),
		    array( 'db' => 'counter',  'dt' => 15 ),
		    array( 'db' => 'auditor',  'dt' => 16 ),
		    array( 'db' => 'reasontoedit',  'dt' => 17 )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		//print_r($data);
		$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	function exporttotalcapture(){
		//set_time_limit(1800);//30 minutos de límite
		set_time_limit(0);
    	ini_set('memory_limit', '-1');
    	if(!isset($_POST['mode'])){
    		echo 'Invalid request';
    	}
		$data=$this->reports_model->getexporttotalcapture($_POST['mode']);
		$objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Novarumsystems.com")
                        ->setLastModifiedBy("Novarumsystems.com")
                        ->setTitle('totalcapture')
                        ->setSubject('totalcapture')
                        ->setDescription('totalcapture')
                        ->setKeywords("Novarumsystems.com")
                        ->setCategory("Novarumsystems.com");
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('totalcapture');

        $sheet->setCellValue("A1", 'Tag');
        $sheet->setCellValue("B1", 'Plant');
        $sheet->setCellValue("C1", 'Area');
        $sheet->setCellValue("D1", 'Rack');
        $sheet->setCellValue("E1", 'Location');
        $sheet->setCellValue("F1", 'Item code');
        $sheet->setCellValue("G1", 'Type');
        $sheet->setCellValue("H1", 'Status');
        $sheet->setCellValue("I1", 'Quantity');
        $sheet->setCellValue("J1", 'Open box');
        $sheet->setCellValue("K1", 'Unit price');
        $sheet->setCellValue("L1", 'Unit extended');
        $sheet->setCellValue("M1", 'Audit counter');
        $sheet->setCellValue("N1", 'Item description');
        $sheet->setCellValue("O1", 'UOM');
        $sheet->setCellValue("P1", 'User');
        $sheet->setCellValue("Q1", 'Team Leader');
        $sheet->setCellValue("R1", 'Auditor');
        $sheet->setCellValue("S1", 'Comments');
        $sheet->setCellValue("T1", 'Lot');
        $sheet->setCellValue("U1", 'Packing date');
        $sheet->setCellValue("V1", 'UOM');

        for ($i=1; $i < 22; $i++) {
        	$colString = PHPExcel_Cell::stringFromColumnIndex($i);
        	$sheet->getColumnDimension($colString)->setAutoSize(true);
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('99CCFF');

		$styleArray = array(
		    'font' => array(
		        'bold' => true
		    )
		);
		$sheet->getStyle('A1:V1')->applyFromArray($styleArray);

		// Datos============>
		$contador=1;
		foreach ($data as $key => $value) {
            $contador++;
            //$sheet->setCellValue("A$contador", '');
            $sheet->setCellValueExplicit("A$contador", $value->tag,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("B$contador", $value->plant,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("C$contador", $value->areadesc,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("D$contador", $value->warehouse,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("E$contador", $value->location,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("F$contador", $value->itemcode,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("G$contador", $value->type,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("H$contador", $value->status,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("I$contador", number_format($value->qty, 2, '.', ','),  PHPExcel_Cell_DataType::TYPE_STRING);
	        //$sheet->setCellValue('H'.$contador, $value->qty);

	        $sheet->setCellValueExplicit("J$contador", $value->openbox,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("K$contador", '$'.number_format($value->cost_price, 2, '.', ','),  PHPExcel_Cell_DataType::TYPE_STRING);
	        //$sheet->setCellValue('J'.$contador, $value->cost_price);

	        $sheet->setCellValueExplicit("L$contador", '$'.number_format($value->cost_price*$value->qty, 2, '.', ','),  PHPExcel_Cell_DataType::TYPE_STRING);
	        //$sheet->setCellValue('K'.$contador, '=J'.$contador.'*G'.$contador);

	        $sheet->setCellValueExplicit("M$contador", $value->auditcounter,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("N$contador", $value->description,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("O$contador", $this->config->item('client')=='CVG' ? $value->uom2 : $value->uom,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("P$contador", $value->name,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("Q$contador", $value->counter,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("R$contador", $value->auditor,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("S$contador", $value->reasontoedit,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("T$contador", $value->lot,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("U$contador", $value->packingdate,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("V$contador", $this->config->item('client')=='CVG' ? $value->uom2 : $value->uom,  PHPExcel_Cell_DataType::TYPE_STRING);
        }

        /*
        $objPHPExcel->getActiveSheet()->getStyle('G2:G'.$contador)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('J2:J'.$contador)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('K2:K'.$contador)->getNumberFormat()->setFormatCode("#,##0.00");
        */
        //-----------------------------------> Color
		// Save Excel 2007 file
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.'totalcapture'.'.xlsx"');
		header('Cache-Control: max-age=0');

		/*header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="totalcapture.xls"');
		header('Cache-Control: max-age=0');*/

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');//Excel5	Excel2007
		//$objWriter->setIncludeCharts(TRUE);
		$objWriter->save('php://output');
		exit;
	}
    function loaddatatotalcapture($mode){
    	set_time_limit(0);
    	ini_set('memory_limit', '-1');
    	//, b.uom ==> Ahora se muestra la que se captura
		$query="select a.*, b.description, b.type, d.name, b.cost_price, e.name as counter, f.name as auditor, a.area as areadesc, case when a.toaudit=1 or a.openbox='Ok' then 'Yes' else 'No' end as toaudit2, b.uom as uom2
					from tags a 
                    left join masterdata b on a.itemcode=b.itemcode
                    left join users d on a.iduser=d.Id
                    left join users e on a.idcounter=e.Id
                    left join users f on a.iduseraudit=f.Id";
        if($mode=='1'){
        	$query.=" where a.iduser=".$this->session->userdata('ifatrimuserid')." and a.area='".$this->session->userdata('ifatrimarea')."' and a.plant='".$this->session->userdata('ifatrimplant')."'";
        }
        $primaryKey = 'Id';

		$columns = array(
			array(
		        'db'        => 'Id',
		        'dt'        => 0,
		        'formatter' => function( $d, $row ) {
					$str = '';
					if($this->session->userdata('ifatrimidprofile')==3){
						$str.='<i class="fa fa-2x fa-eye" style="cursor:pointer;"></i>';
					}
		        	if($this->session->userdata('ifatrimidprofile')==1 || $this->session->userdata('ifatrimidprofile')==3){
		        		$str.='<i class="fa fa-2x fa-edit" style="cursor:pointer;"></i><i class="fa fa-2x fa-remove" style="cursor:pointer;"></i>';
		        	}
		        	return $str;
		        }
		    ),
		    array( 'db' => 'tag', 'dt' => 1 ),
		    array( 'db' => 'areadesc',  'dt' => 2 ),
		    array( 'db' => 'warehouse',  'dt' => 3 ),
		    array( 'db' => 'location',  'dt' => 4 ),
		    array( 'db' => 'itemcode',  'dt' => 5 ),
		    array( 'db' => 'type',  'dt' => 6 ),
		    array( 'db' => 'status',  'dt' => 7 ),
		    array(
		        'db'        => 'qty',
		        'dt'        => 8,
		        'formatter' => function( $d, $row ) {
		        	return ''.number_format($d, 2, '.', ',');//$
		        }
		    ),
		    array( 'db' => 'openbox',  'dt' => 9 ),
		    array(
		        'db'        => 'cost_price',
		        'dt'        => 10,
		        'formatter' => function( $d, $row ) {
		        	return '$'.number_format($d, 2, '.', ',');
		        }
		    ),
		    array(
		        'db'        => 'cost_price',
		        'dt'        => 11,
		        'formatter' => function( $d, $row ) {
		        	return '$'.number_format($d*$row['qty'], 2, '.', ',');
		        }
		    ),
		    //array( 'db' => 'toaudit2',  'dt' => 12 ),
		    array( 'db' => 'auditcounter',  'dt' => 12 ),
		    array( 'db' => 'description',  'dt' => 13 ),
		    array(
		        'db'        => 'uom',
		        'dt'        => 14,
		        'formatter' => function( $d, $row ) {
		        	return $this->config->item('client')=='CVG' ? $row['uom2'] : $row['uom'];
		        }
		    ),
		    array( 'db' => 'uom2',  'dt' => 14 ),
		    array( 'db' => 'name',  'dt' => 15 ),
		    array( 'db' => 'counter',  'dt' => 16 ),
		    array( 'db' => 'auditor',  'dt' => 17 ),
		    array( 'db' => 'reasontoedit',  'dt' => 18 ),
		    // Campos especiales Atlantic Prime=======================>
		    array( 'db' => 'lot',  'dt' => 19 ),
		    //array( 'db' => 'packingdate',  'dt' => 20 ),
		    array(
		        'db'        => 'packingdate',
		        'dt'        => 20,
		        'formatter' => function( $d, $row ) {
		        	$packingdate=$d;
		        	if(strlen($d)==6){
		        		$anio=substr($d, 0, 2);
		        		$mes=substr($d, 2, 2);
		        		$dia=substr($d, 4, 2);
		        		$packingdate='20'.$anio.'-'.$mes.'-'.$dia;
		        	}
		        	return $packingdate;
		        }
		    ),
		    array(
		        'db'        => 'uom',
		        'dt'        => 21,
		        'formatter' => function( $d, $row ) {
		        	return $this->config->item('client')=='CVG' ? $row['uom'] : $row['uom2'];
		        }
		    ),
		    /*
		    array( 'db' => 'precounted',  'dt' => 19 ),
		    array(
		        'db'        => 'fechaempaque',
		        'dt'        => 20,
		        'formatter' => function( $d, $row ) {
		        	return $d=='0000-00-00' ? '' : $d;
		        }
		    ),
		    array(
		        'db'        => 'fecharecibo',
		        'dt'        => 21,
		        'formatter' => function( $d, $row ) {
		        	return $d=='0000-00-00' ? '' : $d;
		        }
		    ),
		    array(
		        'db'        => 'fecharecibo',
		        'dt'        => 22,
		        'formatter' => function( $d, $row ) {
		        	return $d=='0000-00-00' ? '' : $d;
		        }
		    )*/		    
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		//print_r($data);
		$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	public function getlocations(){
		$result=$this->reports_model->getlocations($_POST['wh']);
		echo json_encode($result);
	}
	
	public function progress($type='capture'){
		$config=$this->reports_model->getConfig();
		$range=range(intval(substr($config->starttime,0,2)), intval(substr($config->endtime,0,2)));
		if(substr($config->starttime,2)!=':00'){
			$range[0]=$range[0].substr($config->starttime,2);
		}
		if(substr($config->endtime,2)!=':00'){
			$range[sizeof($range)-1]=$range[sizeof($range)-1].substr($config->endtime,2);
		}
		//Remove hour(s) from break
		if($config->break=='1'){
			$rangebreak=range(intval(substr($config->startbreak,0,2)), intval(substr($config->endbreak,0,2)));
			if(in_array($rangebreak[0], $range)){
				unset($rangebreak[0]);
			}
			$range=array_diff($range, $rangebreak);
		}
		$data['type']=$type;
		$data['range']=$range;
		$data['break']=$config->break;
		$data['startbreak']=intval(substr($config->startbreak,0,2));
		$data['endbreak']=intval(substr($config->endbreak,0,2));
		$data['plants']=$this->reports_model->getplants();
		$this->load->view('reportprogress_view',$data);
	}
	/*public function getprogressdata(){
		$everyminutes=$_POST['every'];
		unset($_POST['every']);
		$result=new stdClass();
		$result->progress=$this->reports_model->getprogressdata($_POST, $everyminutes);
		$result->totaltags=$this->reports_model->gettotaltags();
		echo json_encode($result);
	}*/
	public function getprogressdata($type){
		$result=$this->reports_model->getprogressdata($type, $_POST);
		echo json_encode($result);
	}
	//Audit progress report
	function auditprogress(){
		$config=$this->reports_model->getConfig();
		$range=range(intval(substr($config->starttime,0,2)), intval(substr($config->endtime,0,2)));
		if(substr($config->starttime,2)!=':00'){
			$range[0]=$range[0].substr($config->starttime,2);
		}
		if(substr($config->endtime,2)!=':00'){
			$range[sizeof($range)-1]=$range[sizeof($range)-1].substr($config->endtime,2);
		}
		//Remove hour(s) from break
		if($config->break=='1'){
			$rangebreak=range(intval(substr($config->startbreak,0,2)), intval(substr($config->endbreak,0,2)));
			if(in_array($rangebreak[0], $range)){
				unset($rangebreak[0]);
			}
			$range=array_diff($range, $rangebreak);
		}
		$data['range']=$range;
		$data['break']=$config->break;
		$data['startbreak']=intval(substr($config->startbreak,0,2));
		$data['endbreak']=intval(substr($config->endbreak,0,2));
		$data['plants']=$this->reports_model->getplants();
		$this->load->view('reportauditprogress_view',$data);
	}
	function getauditprogressdata(){
		$result=$this->reports_model->getauditprogressdata($_POST['plant']);
		echo json_encode($result);
	}
	//===========>
	public function variation(){
		$data['totals']=$this->reports_model->getvariationreporttotals();
		$data['nimd']=sizeof($this->reports_model->getNoInMasterdata());
		$this->load->view('reportvariation_view',$data);
	}
	public function loadvariationdata(){
		$moneysign='$';
		$decimals=2;
		if($this->config->item('client')=='AtlanticPrime'){
			$moneysign='';
			$decimals=2;
			$query="select a.itemcode, a.description,
					case when b.onhand is null then 0 else b.onhand end as onhand,
					a.cost_price, (case when b.onhand is null then 0 else b.onhand end * a.cost_price) as extonhand,
					case when temp.total is null then 0 else temp.total end as counted,
					(case when temp.total is null then 0 else temp.total end * a.cost_price) as extcounted,
					(case when temp.total is null then 0 else temp.total end-case when b.onhand is null then 0 else b.onhand end) as variationqty,
					abs(case when temp.total is null then 0 else temp.total end-case when b.onhand is null then 0 else b.onhand end) as variationqtyabs,
					((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end)*case when a.cost_price is null then 0 else a.cost_price end) as extvariation,
					abs((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end)*case when a.cost_price is null then 0 else a.cost_price end) as extvariationabs
					
					from masterdata a
					left join perpetuo b on a.itemcode=b.itemcode
					left join (
						select a.itemcode, sum(qty) as total
						from tags a
						where not a.itemcode is null
						group by a.itemcode
						) as temp on temp.itemcode=a.itemcode";

			$primaryKey = 'itemcode';//'Id';

			$columns = array(
				array( 'db' => 'itemcode', 'dt' => 0 ),
				array( 'db' => 'description', 'dt' => 1 ),
				array(
					'db'        => 'cost_price',
					'dt'        => 2,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'onhand',
					'dt'        => 3,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'extonhand',
					'dt'        => 4,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						//$d = floor($d * 100) / 100;
						return $moneysign.number_format($d, 0, '.', ',');
					}
				),
				array(
					'db'        => 'counted',
					'dt'        => 5,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return '<a href="#" class="detail" data-item="'.$row['itemcode'].'">'.number_format($d, $decimals, '.', ',').'</a>';
					}
				),
				array(
					'db'        => 'extcounted',
					'dt'        => 6,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return $moneysign.number_format($d, 0, '.', ',');
					}
				),
				array(
					'db'        => 'variationqty',
					'dt'        => 7,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'extvariation',
					'dt'        => 8,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return ($d<0 ? '-' : '').$moneysign.number_format(abs($d), 0, '.', ',');
						//return $moneysign.number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'variationqtyabs',
					'dt'        => 9,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'extvariationabs',
					'dt'        => 10,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return $moneysign.number_format($d, $decimals, '.', ',');
						//return $moneysign.number_format($d, $decimals, '.', ',');
					}
				)
			);
		}else{
			$query="select a.itemcode, a.type, a.description, case when b.onhand is null then 0 else b.onhand end as onhand, 
				a.cost_price, (case when b.onhand is null then 0 else b.onhand end * a.cost_price) as extonhand, 
				case when temp.total is null then 0 else temp.total end as counted,
				(case when temp.total is null then 0 else temp.total end * a.cost_price) as extcounted,
				((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end)*case when a.cost_price is null then 0 else a.cost_price end) as extvariation
				from masterdata a
				left join perpetuo b on a.itemcode=b.itemcode
				left join (
										select a.itemcode, sum(qty) as total
										from tags a
										where not a.itemcode is null
										group by a.itemcode
										) as temp on temp.itemcode=a.itemcode";

			$primaryKey = 'itemcode';//'Id';

			$columns = array(
				array( 'db' => 'itemcode', 'dt' => 0 ),
				array( 'db' => 'type', 'dt' => 1 ),
				array( 'db' => 'description',  'dt' => 2 ),
				array(
					'db'        => 'onhand',
					'dt'        => 3,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'cost_price',
					'dt'        => 4,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return $moneysign.number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'extonhand',
					'dt'        => 5,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return $moneysign.number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'counted',
					'dt'        => 6,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return '<a href="#" class="detail" data-item="'.$row['itemcode'].'">'.number_format($d, $decimals, '.', ',').'</a>';
					}
				),
				array(
					'db'        => 'cost_price',
					'dt'        => 7,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return $moneysign.number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'extcounted',
					'dt'        => 8,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return $moneysign.number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'counted',
					'dt'        => 9,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return number_format($d-$row['onhand'], $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'cost_price',
					'dt'        => 10,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return $moneysign.number_format($d, $decimals, '.', ',');
					}
				),
				array(
					'db'        => 'extvariation',
					'dt'        => 11,
					'formatter' => function( $d, $row ) use (&$decimals, &$moneysign){
						return ($d<0 ? '-' : '').$moneysign.number_format(abs($d), $decimals, '.', ',');
						//return $moneysign.number_format($d, $decimals, '.', ',');
					}
				)
			);
		}
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		//print_r($data);
		$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	public function getdetailvariation(){
		$result=$this->reports_model->getdetailvariation($_POST['itemcode']);
		sleep(1);
		echo json_encode($result);
	}

	//------>
	public function variationarea(){
		$this->load->view('reportvariationarea_view');
	}
	public function getdatareportvariationarea(){
		$variationarea = $this->reports_model->getvariationarea();
		$data = array();
		foreach($variationarea as $key => $value){
			$parent = array('id' => $value->Id, 'name' => $value->plant);
			if(!in_array($parent, $data)){
				$data[] = $parent;
			}

			//$data[] = array('id' => $value->area, 'name' => $value->area, 'color' => $this->randcolor());
			$data[] = array('parent' => $value->Id, 'name' => $value->area.' ($'.number_format($value->extvariation, 2).')', 'value' => $value->extvariation);
			
		}

		foreach($data as $key => $value){
			if(isset($value['id'])){
				$data[$key]['color'] = $this->randcolor();
			}
		}
		echo json_encode($data);
	}
	function randcolor() {
		return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
	}
	//=======>
	//------>
	function variationbywarehouse(){
		$data['totals']=$this->reports_model->getvariationreporttotals();
		$this->load->view('reportvariationbywarehouse_view',$data);
	}
	//=======>
	public function unknownitems(){
		$this->load->view('unknowitems_view');
	}
	public function loadunknowitems(){
		$query="select * from masterdata a";
		$primaryKey = 'Id';

		$columns = array(
		    array( 'db' => 'itemcode', 'dt' => 0 ),
		    array( 'db' => 'description',  'dt' => 1 ),
		    array( 'db' => 'uom',   'dt' => 2 ),
		    array( 'db' => 'cost_price',     'dt' => 3 ),
		    array( 'db' => 'item_group',     'dt' => 4 ),
		    array( 'db' => 'type',     'dt' => 5 ),
		    //array( 'db' => 'name',     'dt' => 6 ),
		    array(
		        'db'        => 'Id',
		        'dt'        => 6,
		        'formatter' => function( $d, $row ) {
		            return '<a href="'.base_url().'itemcodes/itemedit/'.$d.'"><i class="fa fa-edit" style="cursor:pointer;"></i></a>';
		        }
		    )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		//$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	public function importfiles(){
		$data['config']=$this->reports_model->getConfig();
		$this->load->view('importfiles_view', $data);
	}
	public function fileupload(){
		$ds          = DIRECTORY_SEPARATOR;  //1
		$storeFolder = 'uploads';   //2
		if(!is_dir($storeFolder)){
	  		mkdir($storeFolder);          	
	    }
		if (!empty($_FILES)) {
		    $tempFile = $_FILES['file']['tmp_name'];
		    $targetPath = getcwd (). $ds. $storeFolder . $ds;
		    $targetFile =  $targetPath. $_FILES['file']['name'];
		    move_uploaded_file($tempFile,$targetFile);
		}
	}
	public function import(){//$filetype
		set_time_limit(0);
		ini_set('memory_limit','-1');
		$ds          = DIRECTORY_SEPARATOR;
		$storeFolder = 'uploads';
		$file = getcwd().$ds.$storeFolder.$ds.$_POST['filename'];
		$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
        $reader->open($file);

		$validations[] = array(
				'name' => 'Perpetuo',
				0 => 'Item Code',
				1 => 'On Hand');

		$validations[] = array(
				'name' => 'masterdata',
				0 => 'Item',
				4 => 'Description',
				7 => 'Standard',
				8 => 'Unit',
				9 => 'Type');

			$locationsfile=array(
					'name' => 'Locations',
					0 => 'PLANT',
					1 => 'AREA',
					2 => 'WAREHOUSE',
					3 => 'LOCATION'
				);
		//===================================================>
		$config='';
		if($this->config->item('client')=='CVG'){
			$config=$this->reports_model->getConfig();
			if($config->inventorytype=='SEATS'){
				$locationsfile=array(
					'name' => 'Locations',
					0 => 'PLANT',
					1 => 'AREA',
					2 => 'WAREHOUSE',
					3 => 'LOCATION',
					4 => 'DIMM'
				);
			}
		}else{
			$locationsfile=array(
				'name' => 'Locations',
				0 => 'PLANT',
				1 => 'AREA',
				2 => 'WAREHOUSE',
				3 => 'LOCATION',
				4 => 'AbsEntry'
			);
		}
		//===================================================>
		$validations[] = $locationsfile;

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

	                //Identificar cual de los 3 tipos de excel es el que ha sido subido==================>
	                if($key==1){
	                	foreach ($validations as $position => $title) {
	                		$valid = true;

	                		foreach ($title as $key => $value) {if(is_numeric($key)){
	                			if (isset($row[$key])) {
		                    		if ($row[$key] != $value) {
			                       		$valid = false;
			                       		break;
			                        }
		                    	}else{
		                    		$valid = false;
			                       	break;
		                    	}
	                		}}
	                		
	                		/*foreach ($row as $column => $cellvalue) {
		                    	if (isset($title[$column])) {
		                    		if ($cellvalue != $title[$column]) {
			                       		$valid = false;
			                       		break;
			                        }
		                    	}
		                    }*/
		                    if ($valid) {
			                    $fileName = $title['name'];
			                    break;
		                    }
		                }
	                //Identificar cual de los 3 tipos de excel es
                	}else if($key >= 2){
                		switch ($fileName) {
                			case 'Perpetuo':
                				$datatoinsert[] = array(
									'itemcode' => trim(strtoupper($this->issetor($row[0]))),
									'onhand' => trim($this->issetor($row[1]))
									);
                				break;
                			case 'masterdata':
                				//&& $this->issetor($row[4])!='' Permitir las descripciones en blanco
                				if($this->issetor($row[0])!='' && $this->issetor($row[7])!=='' && $this->issetor($row[8])!=''){

									$cost_price= (float) $this->issetor($row[7]);
									$datatoinsert[] = array(
										'itemcode' => trim(strtoupper($this->issetor($row[0]))),
										'description' => trim($this->issetor($row[4])),
										'uom' => trim(strtoupper($this->issetor($row[8]))),
										'cost_price' => trim($cost_price),
										'type' => strtoupper($this->issetor($row[9]))
										);
								}else{
									$ommitedrows=true;
								}
                				break;
                			case 'Locations':
                				//===================================================>
								if($this->config->item('client')=='CVG'){
									if($config->inventorytype=='SEATS'){
										if($this->issetor($row[0])!='' && $this->issetor($row[1])!='' && $this->issetor($row[2])!='' && $this->issetor($row[3])!='' && $this->issetor($row[4])!=''){

											$datatoinsert[] = array(
												'plant' => trim($this->issetor($row[0])),
												'area' => trim($this->issetor($row[1])),
												'wh' 	=> trim($this->issetor($row[2])),
												'location'=> trim($this->issetor($row[3])),
												'plataforma'=> trim($this->issetor($row[4]))
												);
										}else{
											$ommitedrows=true;
										}
		                				break;
									}else{
										if($this->issetor($row[0])!='' && $this->issetor($row[1])!='' && $this->issetor($row[2])!='' && $this->issetor($row[3])!=''){

											$datatoinsert[] = array(
												'plant' => trim($this->issetor($row[0])),
												'area' => trim($this->issetor($row[1])),
												'wh' 	=> trim($this->issetor($row[2])),
												'location'=> trim($this->issetor($row[3])),
												'plataforma'=> trim($this->issetor($row[4]))
												);
										}else{
											$ommitedrows=true;
										}
		                				break;
									}
								}else{
								//===================================================>
	                				if($this->issetor($row[0])!='' && $this->issetor($row[1])!='' && $this->issetor($row[2])!='' && $this->issetor($row[3])!='' && $this->issetor($row[4])!=''){

										$datatoinsert[] = array(
											'plant' => trim($this->issetor($row[0])),
											'area' => trim($this->issetor($row[1])),
											'wh' 	=> trim($this->issetor($row[2])),
											'location'=> trim($this->issetor($row[3])),
											'plataforma'=> trim($this->issetor($row[4]))
											);
									}else{
										$ommitedrows=true;
									}
	                				break;
	                			}
                		}
	                }
                }
            }
        }

        $reader->close();
        unlink($file);

        $result = '<span>';

        //Truncar e insertar los datos===========>
        if ($fileName == 'Perpetuo') {
        	$this->reports_model->truncateperpetuo();
        	$this->reports_model->saveimportdata($datatoinsert);
        }else if($fileName == 'masterdata'){
        	$this->reports_model->truncatemasterdata();
        	$this->reports_model->saveimportdatamasterdata($datatoinsert);
        }else if($fileName == 'Locations'){
        	if(!$ommitedrows){
	        	$this->reports_model->truncatelocations();
	        	$this->reports_model->insertLocation($datatoinsert);
	        }
        }
        //======================================>
		if($fileName == ''){
			$result.='Unknown file. Check ALL required field names.';
			/*if($filetype=='masterdata'){
				$result.='[A] -> "Item", [E] -> "Description", [I] -> "Unit", [H] -> "Standard", [F] -> "Group", [J] -> "Type" columns are expected for masterdata file in sheet named "data" (without quotes).';
			}elseif($filetype=='Perpetuo'){
				$result.='[A] -> "Item Code", [B] -> "Description", [D] -> "Type", [G] -> "On Hand" columns are expected for Perpetuo file in sheet named "data" (without quotes).';
			}else{
				$result.='[A] -> "WH", [B] -> "Planta", [C] -> "Area", [D] -> "Location" columns are expected for locations file in sheet named "data" (without quotes).';
			}*/
		}else{
			if($fileName=='masterdata'){
				/*if($zerocost>0){
					$result.='File imported '.$fileName.' but there are <a href="'.base_url().'reports/validationzeroprice">items with price "zero".</a>';
				}else{*/
					$result.='File imported '.$fileName.'.';
				//}
			}elseif($fileName=='Perpetuo'){
				$total=$this->reports_model->checkPerpetuoVSMaster()->total;
				if($total>0){
					$result.='File imported '.$fileName.' but Item Master <a href="'.base_url().'reports/validationnoinperpetuo">has items that do not exist in Perpetuo.</a>';
				}
			}elseif($fileName=='Locations'){
				if(!$ommitedrows){
					$result.='File imported '.$fileName.'.';
				}else{
					$result.='File not imported due to records in blank..';
				}
			}
		}
		if($ommitedrows && ($fileName=='masterdata' or $fileName=='Perpetuo')){
			$result.=' Some records were not imported BUT some fields are in blank. Please check your import file.';
		}
		$result.='</span>';
		echo $result;
    }
	//Validation functions=============>
	public function validations(){
		$data['validationstep']=$this->reports_model->getStep();
		$this->load->view('validations_view', $data);
	}
	public function validationzeroprice(){
		$data=$this->reports_model->getZeroPrice();
		$objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Novarumsystems.com")
                        ->setLastModifiedBy("Novarumsystems.com")
                        ->setTitle("ZeroPriceItemMaster")
                        ->setSubject("ZeroPriceItemMaster")
                        ->setDescription("ZeroPriceItemMaster")
                        ->setKeywords("Novarumsystems.com")
                        ->setCategory("Novarumsystems.com");
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle("ZeroPriceItemMaster");

        $sheet->setCellValue("A1", 'Itemcode');

        //--------------> Ancho de las columnas
        $sheet->getColumnDimension('A')->setAutoSize(true);
        //-----------------------------------> Color
        $objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE8E5E5');

        $contador=1;
        foreach ($data as $key => $value) {
            $contador++;
            $sheet->setCellValueExplicit("A$contador", $value->itemcode,PHPExcel_Cell_DataType::TYPE_STRING);
            //$sheet->setCellValue("A$contador", $value->itemcode);
        }
                    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"ZeroPriceItemMaster.xlsx\"");
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
	}
	public function validationnoinperpetuo(){
		$data=$this->reports_model->getNoInPerpetuo();
		$objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Novarumsystems.com")
                        ->setLastModifiedBy("Novarumsystems.com")
                        ->setTitle("NoInPerpetuo")
                        ->setSubject("NoInPerpetuo")
                        ->setDescription("NoInPerpetuo")
                        ->setKeywords("Novarumsystems.com")
                        ->setCategory("Novarumsystems.com");
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle("NoInPerpetuo");

        $sheet->setCellValue("A1", 'Itemcode');

        //--------------> Ancho de las columnas
        $sheet->getColumnDimension('A')->setAutoSize(true);
        //-----------------------------------> Color
        $objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE8E5E5');

        $contador=1;
        foreach ($data as $key => $value) {
            $contador++;
            //$sheet->setCellValue("A$contador", $value->itemcode);
            $sheet->setCellValueExplicit("A$contador", $value->itemcode,PHPExcel_Cell_DataType::TYPE_STRING);
        }
                    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"NoInPerpetuo.xlsx\"");
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
	}
	public function noInMasterData(){
		$data=$this->reports_model->getNoInMasterdata();
		$objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Novarumsystems.com")
                        ->setLastModifiedBy("Novarumsystems.com")
                        ->setTitle("NoInPerpetuo")
                        ->setSubject("NoInPerpetuo")
                        ->setDescription("NoInPerpetuo")
                        ->setKeywords("Novarumsystems.com")
                        ->setCategory("Novarumsystems.com");
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle("NoInMasterdata");

        $sheet->setCellValue("A1", 'Itemcode');
        $sheet->setCellValue("B1", 'On hand');

        //--------------> Ancho de las columnas
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        //-----------------------------------> Color
        $objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE8E5E5');

        $contador=1;
        foreach ($data as $key => $value) {
            $contador++;
            $sheet->setCellValueExplicit("A$contador", $value->itemcode,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue("B$contador", $value->onhand);
        }
                    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"NoInMasterdata.xlsx\"");
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
	}
	public function setStep(){
		$this->reports_model->setStep($_POST);
	}
	public function getSchedule(){
		$result=$this->reports_model->getConfig();
		echo json_encode($result);
	}
	public function setSchedule(){
		$data=array();
		echo $_POST['data'].'=====>';
		parse_str($_POST['data'], $data);
		if(!isset($data['break'])){
			$data['break']=0;
		}else{
			$data['break']=$data['break'];
			unset($data['break']);
		}
		print_r($data);
		$this->reports_model->setStep($data);
	}
	function info(){
		$this->load->view('info_view');
	}
	function devices(){
		$this->load->view('devices_view');
	}
	function loaddevices(){
		$query="select a.Id, a.name as devicename, a.lastsync, b.name as username from Devices a left join users b on a.lastuser=b.Id";

		$primaryKey = 'Id';

		$columns = array(
		    array( 'db' => 'devicename', 'dt' => 0 ),
		    array( 'db' => 'lastsync',  'dt' => 1 ),
		    array( 'db' => 'username',  'dt' => 2 )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		//print_r($data);
		$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	function locationreport(){
		$this->load->view('locationreport_view');
	}
	function loadlocationsreport(){
		$query="select a.warehouse, a.location, a.itemcode, sum(a.qty) as qty
				from tags a
				group by a.warehouse, a.location, a.itemcode";
		$primaryKey = 'warehouse';

		$columns = array(
		    array( 'db' => 'warehouse', 'dt' => 0 ),
		    array( 'db' => 'location',  'dt' => 1 ),
		    array( 'db' => 'itemcode',   'dt' => 2 ),
		    array( 'db' => 'qty',     'dt' => 3 )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		//$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	function auditreport(){
		$this->load->view('reportaudit_view');
	}
	function exportauditdetail(){
		$data=$this->reports_model->getauditprogressdata(0);
		$objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Novarumsystems.com")
                        ->setLastModifiedBy("Novarumsystems.com")
                        ->setTitle("AuditReportDetail")
                        ->setSubject("AuditReportDetail")
                        ->setDescription("AuditReportDetail")
                        ->setKeywords("Novarumsystems.com")
                        ->setCategory("Novarumsystems.com");
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle("AuditReportDetail");

        $sheet->setCellValue("A1", 'Area');
        $sheet->setCellValue("B1", 'Total');
        $sheet->setCellValue("C1", 'Audited');

        //--------------> Ancho de las columnas
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        //-----------------------------------> Color
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE8E5E5');

        $contador=1;
        foreach ($data as $key => $value) {
            $contador++;
            $sheet->setCellValueExplicit("A$contador", $value->area,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("B$contador", $value->total,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("C$contador", $value->audited,PHPExcel_Cell_DataType::TYPE_STRING);
            //$sheet->setCellValue("A$contador", $value->itemcode);
        }
                    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"AuditReportDetail.xlsx\"");
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
	}
	//===================> import locations file
	function importlocations(){
		$this->load->view('importlocations_view');
	}
	//==========================>
	function scannedbyuser(){
		$data['users']=$this->reports_model->getScannedbyuser();
		$this->load->view('reportscannedbyuser_view', $data);
	}
	function auditbyuser(){
		$data['users']=$this->reports_model->getAuditbyuser();
		$this->load->view('reportauditbyuser_view', $data);
	}
	function issetor(&$var) {
	    return isset($var) ? $var : '';
	}
}