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
        $sheet->setCellValue("B1", 'Hu');
        $sheet->setCellValue("C1", 'Plant');
        $sheet->setCellValue("D1", 'Area');
        $sheet->setCellValue("E1", 'Rack');
        $sheet->setCellValue("F1", 'Location');
        $sheet->setCellValue("G1", 'Item code');
        $sheet->setCellValue("H1", 'Type');
        $sheet->setCellValue("I1", 'Status');
        $sheet->setCellValue("J1", 'Quantity');
        $sheet->setCellValue("K1", 'Open box');
        $sheet->setCellValue("L1", 'Unit price');
        $sheet->setCellValue("M1", 'Unit extended');
        $sheet->setCellValue("N1", 'Audit counter');
        $sheet->setCellValue("O1", 'Item description');
        $sheet->setCellValue("P1", 'UOM');
        $sheet->setCellValue("Q1", 'User');
        $sheet->setCellValue("R1", 'Team Leader');
        $sheet->setCellValue("S1", 'Auditor');
        $sheet->setCellValue("T1", 'Comments');
        $sheet->setCellValue("U1", 'Lot');
        $sheet->setCellValue("V1", 'Packing date');
        $sheet->setCellValue("W1", 'UOM');

        for ($i=1; $i < 22; $i++) {
        	$colString = PHPExcel_Cell::stringFromColumnIndex($i);
        	$sheet->getColumnDimension($colString)->setAutoSize(true);
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:W1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('99CCFF');

		$styleArray = array(
		    'font' => array(
		        'bold' => true
		    )
		);
		$sheet->getStyle('A1:W1')->applyFromArray($styleArray);

		// Datos============>
		$contador=1;
		foreach ($data as $key => $value) {
            $contador++;
            //$sheet->setCellValue("A$contador", '');
            $sheet->setCellValueExplicit("A$contador", $value->tag,  PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("B$contador", $value->hu,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("C$contador", $value->plant,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("D$contador", $value->areadesc,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("E$contador", $value->warehouse,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("F$contador", $value->location,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("G$contador", $value->itemcode,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("H$contador", $value->type,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("I$contador", $value->status,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("J$contador", number_format($value->qty, 2, '.', ','),  PHPExcel_Cell_DataType::TYPE_STRING);
	        //$sheet->setCellValue('H'.$contador, $value->qty);

	        $sheet->setCellValueExplicit("K$contador", $value->openbox,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("L$contador", '$'.number_format($value->cost_price, 2, '.', ','),  PHPExcel_Cell_DataType::TYPE_STRING);
	        //$sheet->setCellValue('J'.$contador, $value->cost_price);

	        $sheet->setCellValueExplicit("M$contador", '$'.number_format($value->cost_price*$value->qty, 2, '.', ','),  PHPExcel_Cell_DataType::TYPE_STRING);
	        //$sheet->setCellValue('K'.$contador, '=J'.$contador.'*G'.$contador);

	        $sheet->setCellValueExplicit("N$contador", $value->auditcounter,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("O$contador", $value->description,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("P$contador", $this->config->item('client')=='CVG' ? $value->uom2 : $value->uom,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("Q$contador", $value->name,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("R$contador", $value->counter,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("S$contador", $value->auditor,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("T$contador", $value->reasontoedit,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("U$contador", $value->lot,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("V$contador", $value->packingdate,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("W$contador", $value->uom,  PHPExcel_Cell_DataType::TYPE_STRING);
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
		        		// $str.='<i class="fa fa-2x fa-edit" style="cursor:pointer;"></i><i class="fa fa-2x fa-remove" style="cursor:pointer;"></i>';
		        	}
		        	return $str;
		        }
		    ),
		    array( 'db' => 'tag', 'dt' => 1 ),
		    array( 'db' => 'hu', 'dt' => 2 ),
		    array( 'db' => 'areadesc',  'dt' => 3 ),
		    array( 'db' => 'warehouse',  'dt' => 4 ),
		    array( 'db' => 'location',  'dt' => 5 ),
		    array( 'db' => 'itemcode',  'dt' => 6 ),
		    array( 'db' => 'type',  'dt' => 7 ),
		    array( 'db' => 'status',  'dt' => 8 ),
		    array(
		        'db'        => 'qty',
		        'dt'        => 9,
		        'formatter' => function( $d, $row ) {
		        	return ''.number_format($d, 2, '.', ',');//$
		        }
		    ),
		    array( 'db' => 'openbox',  'dt' => 10 ),
		    array(
		        'db'        => 'cost_price',
		        'dt'        => 11,
		        'formatter' => function( $d, $row ) {
		        	return '$'.number_format($d, 2, '.', ',');
		        }
		    ),
		    array(
		        'db'        => 'cost_price',
		        'dt'        => 12,
		        'formatter' => function( $d, $row ) {
		        	return '$'.number_format($d*$row['qty'], 2, '.', ',');
		        }
		    ),
		    //array( 'db' => 'toaudit2',  'dt' => 12 ),
		    array( 'db' => 'auditcounter',  'dt' => 13 ),
		    array( 'db' => 'description',  'dt' => 14 ),
		    array(
		        'db'        => 'uom',
		        'dt'        => 15,
		        'formatter' => function( $d, $row ) {
		        	return $this->config->item('client')=='CVG' ? $row['uom2'] : $row['uom'];
		        }
		    ),
		    array( 'db' => 'uom2',  'dt' => 16 ),
		    array( 'db' => 'name',  'dt' => 17 ),
		    array( 'db' => 'counter',  'dt' => 18 ),
		    array( 'db' => 'auditor',  'dt' => 19 ),
		    array( 'db' => 'reasontoedit',  'dt' => 20 ),
		    // Campos especiales Atlantic Prime=======================>
		    array( 'db' => 'lot',  'dt' => 21 ),
		    //array( 'db' => 'packingdate',  'dt' => 20 ),
		    array(
		        'db'        => 'packingdate',
		        'dt'        => 22,
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
		        'dt'        => 23,
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
	
	public function getprogressdata($type){
		$result=$this->reports_model->getprogressdata($type, $_POST);
		echo json_encode($result);
	}

	public function getprogressdetail($type){
		$result=$this->reports_model->getprogressdetail($type, $_POST);
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
		$plant = "";
		$area = "";
		if(isset($_GET['plant'])){
			$plant = $_GET['plant'];
		}

		if(isset($_GET['area'])){
			$area = $_GET['area'];
		}

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
									where not a.itemcode is null and status = 'Used'
									group by a.itemcode
									) as temp on temp.itemcode=a.itemcode";

		// $query="select c.tag, c.warehouse, c.location, a.itemcode, a.description, p.onhand, 
		// 		a.cost_price, (p.onhand * a.cost_price) as extonhand, 
		// 		case when c.status is null then 0 else c.qty end as counted,
		// 		(case when c.status is null then 0 else c.qty end * a.cost_price) as extcounted,
		// 		((case when c.status is null then 0 else c.qty end - p.onhand)*case when a.cost_price is null then 0 else a.cost_price end) as extvariation,
		// 		(case when c.status is null then 0 else c.qty end - p.onhand) as variationqty,
		// 		abs((case when c.status is null then 0 else c.qty end - p.onhand)) as variationqtyabs,
		// 		abs(((case when c.status is null then 0 else c.qty end - p.onhand)*case when a.cost_price is null then 0 else a.cost_price end)) as extvariationabs
		// 		from tags c
		// 		left join masterdata a  on c.itemcode=a.itemcode
		// 		left join perpetuo p on c.tag =p.itemcode
		// 		where c.status is null or c.status <> 'Voided'";

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

	public function exportvariationdata(){
		set_time_limit(0);
    	ini_set('memory_limit', '-1');
		$data=$this->reports_model->getexportvariationdata($_POST['mode']);
		$objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Novarumsystems.com")
                        ->setLastModifiedBy("Novarumsystems.com")
                        ->setTitle('variationdata')
                        ->setSubject('variationdata')
                        ->setDescription('variationdata')
                        ->setKeywords("Novarumsystems.com")
                        ->setCategory("Novarumsystems.com");
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('variationdata');

        $sheet->setCellValue("A1", 'Item code');
        $sheet->setCellValue("B1", 'Type');
        $sheet->setCellValue("C1", 'Description');
        $sheet->setCellValue("D1", 'Quantity');
        $sheet->setCellValue("E1", 'Unit Price USD');
        $sheet->setCellValue("F1", 'Extended USD');
        $sheet->setCellValue("G1", 'Quantity');
        $sheet->setCellValue("H1", 'Unit Price USD');
        $sheet->setCellValue("I1", 'Extended USD');
        $sheet->setCellValue("J1", 'Quantity');
        $sheet->setCellValue("K1", 'Unit Price USD');
        $sheet->setCellValue("L1", 'Extended USD');

        for ($i=1; $i < 12; $i++) {
        	$colString = PHPExcel_Cell::stringFromColumnIndex($i);
        	$sheet->getColumnDimension($colString)->setAutoSize(true);
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('99CCFF');

		$styleArray = array(
		    'font' => array(
		        'bold' => true
		    )
		);
		$sheet->getStyle('A1:L1')->applyFromArray($styleArray);

		// Datos============>
		$contador=1;
		foreach ($data as $key => $value) {
            $contador++;
            //$sheet->setCellValue("A$contador", '');
            $sheet->setCellValueExplicit("A$contador", $value->itemcode,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("B$contador", $value->type,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("C$contador", $value->description,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("D$contador", $value->onhand,  PHPExcel_Cell_DataType::TYPE_NUMERIC);
	        $sheet->setCellValueExplicit("E$contador", $value->cost_price,  PHPExcel_Cell_DataType::TYPE_NUMERIC);
	        $sheet->setCellValueExplicit("F$contador", $value->extonhand,  PHPExcel_Cell_DataType::TYPE_NUMERIC);
	        $sheet->setCellValueExplicit("G$contador", $value->counted,  PHPExcel_Cell_DataType::TYPE_NUMERIC);
	        $sheet->setCellValueExplicit("H$contador", $value->cost_price,  PHPExcel_Cell_DataType::TYPE_NUMERIC);
	        $sheet->setCellValueExplicit("I$contador", $value->extcounted,  PHPExcel_Cell_DataType::TYPE_NUMERIC);

	        $sheet->setCellValueExplicit("J$contador", $value->counted-$value->onhand,  PHPExcel_Cell_DataType::TYPE_NUMERIC);
	        $sheet->setCellValueExplicit("K$contador", $value->cost_price,  PHPExcel_Cell_DataType::TYPE_NUMERIC);

	        $sheet->setCellValueExplicit("L$contador", $value->extvariation,  PHPExcel_Cell_DataType::TYPE_NUMERIC);
        }

		
        //-----------------------------------> Color
		// Save Excel 2007 file
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.'variationdata'.'.xlsx"');
		header('Cache-Control: max-age=0');

		/*header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="totalcapture.xls"');
		header('Cache-Control: max-age=0');*/

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');//Excel5	Excel2007
		//$objWriter->setIncludeCharts(TRUE);
		$objWriter->save('php://output');
		exit;
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

			$variation = ($value->extvariation < 0) ? '('.number_format(abs($value->extvariation)).')' : number_format(abs($value->extvariation));

			//$data[] = array('id' => $value->area, 'name' => $value->area, 'color' => $this->randcolor());
			$data[] = array('parent' => $value->Id, 'name' => $value->area.' <br/> '.$variation, 'value' => $value->extvariation);
			
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

	function gettotalsvariation(){

		$totals=$this->reports_model->getvariationreporttotals($_POST['plant'], $_POST['area']);
		$header = "";
		$footer = "";

		$moneysign='$';
		$decimals=2;
		if($this->config->item('client')=='AtlanticPrime'){
			$moneysign='';
			$decimals=2;
		}
		if($this->config->item('client') != 'AtlanticPrime'){
			$extonhand = 1;
			if($totals->extonhand>0){
				$extonhand = $totals->extonhand;
			}
			$percent = ($totals->extcounted / $extonhand) * 100;
			if($percent > 100){
				$percent = 100;
			}
			
			$header .='<th colspan="2" style="font-size: 20px; font-weight:bold;">Progress: ' . number_format($percent, $decimals, '.', ',').'%</th> 
			<th colspan="3" style="background-color:rgba(59, 27, 103, 0.13);">'.$this->lang->line('On hand').'</th>
			<th style="text-align:right;background-color:rgba(59, 27, 103, 0.13);"><b>'.$moneysign.number_format($totals->extonhand, $decimals, '.', ',').'</b></th>
			<th colspan="2" style="background-color:beige;">'.$this->lang->line('Counted').'</th>
			<th style="text-align:right;background-color:beige;"><b>'.$moneysign.number_format($totals->extcounted, $decimals, '.', ',').'</b></th>
			<th colspan="2" style="background-color:aliceblue;">'.$this->lang->line('Variation').'</th>
			<th style="text-align:right;background-color:'.(($totals->extvariation < 0) ? 'red' : 'aliceblue').';color:'.(($totals->extvariation < 0) ? 'white' : 'black').'"><b>'.($totals->extvariation < 0 ? '-' : '') . $moneysign . number_format(abs($totals->extvariation), $decimals, '.', ',').'</b></th>';

			$footer .= '<th colspan="3">'.$this->lang->line('Totals').'</th>
			<th style="background-color:aliceblue;text-align:right;"><b>'.number_format($totals->onhand, $decimals, '.', ',').'</b></th>
			<th></th>
			<th style="background-color: #EAFF5F;text-align:right;"><b>'.$moneysign.number_format($totals->extonhand, $decimals, '.', ',').'</b></th>
			<th style="background-color:aliceblue;text-align:right;"><b>'.number_format($totals->counted, $decimals, '.', ',').'</b></th>
			<th></th>
			<th style="background-color: #EAFF5F;text-align:right;"><b>'.$moneysign.number_format($totals->extcounted, $decimals, '.', ',').'</b></th>
			<th></th>
			<th></th>
			<th style="text-align:right;background-color:'.(($totals->extvariation < 0) ? 'red' : 'aliceblue').';color:'.(($totals->extvariation < 0) ? 'white' : 'black').'"><b>'.($totals->extvariation < 0 ? '-' : '') . $moneysign . number_format(abs($totals->extvariation), $decimals, '.', ',').'</b></th>';
		}else{
			$header .= '<th></th>
			<th></th>
			<th></th>
			<th style="background-color:rgba(59, 27, 103, 0.13);">'.$this->lang->line('On hand').'</th>
			<th style="text-align:right;background-color:rgba(59, 27, 103, 0.13);"><b>'.$moneysign.''.number_format($totals->extonhand, $decimals, '.', ',').'</b></th>
			<th style="background-color:beige;">'.$this->lang->line('Counted').'</th>
			<th style="text-align:right;background-color:beige;"><b>'.$moneysign.number_format($totals->extcounted, $decimals, '.', ',').'</b></th>
			<th style="background-color:aliceblue;">'.$this->lang->line('Variation').'</th>
			<th style="background-color: '.(($totals->extvariation < 0) ? 'red' : '#EAFF5F').';text-align:right;"><b>'.(($totals->extvariation) < 0 ? '-' : '') . $moneysign . number_format(abs($totals->extvariation), $decimals, '.', ',').'</b></th>
			<th style="background-color:aliceblue;"><b>'.number_format($totals->variationqtyabs, $decimals, '.', ',').'</b></th>
			<th style="background-color:aliceblue;"><b>'.number_format($totals->extvariationabs, $decimals, '.', ',').'</b></th>';

			$footer .= '<th colspan="3">'.$this->lang->line('Totals').'</th>
			<th style="background-color:aliceblue;text-align:right;"><b>'.number_format($totals->onhand, 2, '.', ',') .'</b></th>
			<th style="background-color: #EAFF5F;text-align:right;"><b>'.$moneysign.number_format($totals->extonhand, $decimals, '.', ',') .'</b></th>
			<th style="background-color:aliceblue;text-align:right;"><b>'.number_format($totals->counted, 2, '.', ',').'</b></th>
			<th style="background-color: #EAFF5F;text-align:right;"><b>'.$moneysign.number_format($totals->extcounted, $decimals, '.', ',').'</b></th>
			<th style="background-color:aliceblue;text-align:right;">
				<b>'.($totals->variationqty < 0 ? '-' : '') . $moneysign . number_format(abs($totals->variationqty), 2, '.', ',').'</b>
			</th>
			<th style="background-color:  '.(($totals->extvariation < 0) ? 'red' : '#EAFF5F').';text-align:right;"><b>'.(($totals->extvariation) < 0 ? '-' : '') . $moneysign . number_format(abs($totals->extvariation), $decimals, '.', ',').'</b></th>
			<th style="background-color:aliceblue;"><b>'.number_format($totals->variationqtyabs, $decimals, '.', ',').'</b></th>
			<th style="background-color:aliceblue;"><b>'.number_format($totals->extvariationabs, $decimals, '.', ',').'</b></th>';
		}

		echo json_encode(array('header' => $header, 'footer' => $footer));
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
	public function fileuploadlocations(){
		$ds          = DIRECTORY_SEPARATOR;
		$storeFolder = 'uploads';
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

    	$datatoinsert=array();
        foreach ($reader->getSheetIterator() as $sheet) {
        	if ($sheet->isActive()) {
	            foreach ($sheet->getRowIterator() as $key => $row) {
	                if($key >= 2){
						$datatoinsert[] = array(
							'hu' => trim($this->issetor($row[2])),
							'material' => trim($this->issetor($row[3])),
							'material_description' => trim($this->issetor($row[4])),
							'batch' => trim($this->issetor($row[5])),
							'qty' => trim($this->issetor($row[6])),
							'um' => trim($this->issetor($row[7])),
							'location' => trim($this->issetor($row[17])),
							'wh' => trim($this->issetor($row[16])),
							'cost_price' => trim($this->issetor($row[19])),
							'area' => trim($this->issetor($row[21])),
							);
	                }
                }
            }
        }

        $reader->close();
        unlink($file);

        $result = '<span>';

        //Truncar e insertar los datos===========>
		$this->reports_model->truncaterawdata();
		$this->reports_model->saveimportrawdata($datatoinsert);

		// // LLenar las tablas:
		// // 1. locations
		// $locations = $this->db->query("SELECT DISTINCT wh, location FROM rawdata")->result();
		// $this->reports_model->truncatelocations();
		// $locationsfile=array();
		// foreach ($locations as $key => $location) {
		// 	$locationsfile[]= array(
		// 		'plant' => 'Unknown',
		// 		'area' => 'Unknown',
		// 		'wh' => $location->wh,
		// 		'location' => $location->location,
		// 		'plataforma' => 'Unknown',
		// 	);
		// }
		// $this->reports_model->insertLocation($locationsfile);
		
		// //2.- masterdata
		$masterdata = $this->db->query("SELECT DISTINCT material, material_description, um, cost_price FROM rawdata")->result();
		$this->reports_model->truncatemasterdata();
		$masterdatafile=array();
		foreach ($masterdata as $key => $value) {
			$masterdatafile[]= array(
				'itemcode' => $value->material,
				'description' => $value->material_description,
				'cost_price' => $value->cost_price,
				'uom' => $value->um
			);
		}
		$this->reports_model->saveimportdatamasterdata($masterdatafile);

		// //3.- perpetuo
		$perpetuo = $this->db->query("select material, sum(qty) as qty from rawdata group by material")->result();
		$this->reports_model->truncateperpetuo();
		$perpetuofile=array();
		foreach ($perpetuo as $key => $value) {
			$perpetuofile[]= array(
				'itemcode' => $value->material, // $value->hu, why??
				'onhand' => $value->qty
			);
		}
		$this->reports_model->saveimportdata($perpetuofile);

		// //4.- hus
		$hus = $this->db->query("select hu, material, wh, qty, um, area, batch from rawdata where trim(hu)<>''")->result();
		//$this->reports_model->truncatetags();
		//$this->reports_model->truncatehus();

		// a) Copy hus already counted that are not in rawdata to hus_history table
		$this->db->query("INSERT INTO hus_history (tag, plant, area, warehouse, datetimecaptured, itemcode, status, qty, location, iduser, reasontoedit, idcounter, idauditor, printcounter, toaudit, resultaudit, iduseraudit, datetimeaudit, openbox, auditcounter, precounted, fechaempaque, fecharecibo, fechacaducidad, lot, packingdate, uom, batch)
		SELECT tag, plant, area, warehouse, datetimecaptured, itemcode, status, qty, location, iduser, reasontoedit, idcounter, idauditor, printcounter, toaudit, resultaudit, iduseraudit, datetimeaudit, openbox, auditcounter, precounted, fechaempaque, fecharecibo, fechacaducidad, lot, packingdate, uom, batch
		FROM hus
		WHERE not status is null AND tag NOT IN (SELECT DISTINCT hu FROM rawdata WHERE TRIM(hu)<>'' )");

		// b) Delete all hus except those which have been counted and are in rawdata
		$this->db->query("DELETE FROM hus WHERE status is null OR tag NOT IN (SELECT DISTINCT hu FROM rawdata WHERE TRIM(hu)<>'' )");

		$husfile=array();
		foreach ($hus as $key => $value) {
			// Add only if not exists
			$exists = $this->db->query("SELECT COUNT(*) as count FROM hus WHERE tag = ?", array($value->hu))->row()->count;
			if($exists > 0){
				continue;
			}
			$husfile[]= array(
				'tag' => $value->hu,
				'warehouse' => $value->wh,
				'qty' => $value->qty,
				'plant' => 'Unknown',
				'area' => $value->area,
				'printcounter' => 0,
				'auditcounter' => 0,
				'itemcode' => $value->material,
				'uom' => $value->um,
				'batch' => $value->batch
			);
		}
		//print_r($husfile);
		$this->reports_model->saveimporthus($husfile);

        //======================================>
		$result.='File imported '.count($datatoinsert).' records successfully.';
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
	public function importlocationsfile(){
		set_time_limit(0);
		ini_set('memory_limit','-1');
		$ds          = DIRECTORY_SEPARATOR;
		$storeFolder = 'uploads';
		$file = getcwd().$ds.$storeFolder.$ds.$_POST['filename'];
		$reader = ReaderFactory::create(Type::XLSX);
        $reader->open($file);

    	$datatoinsert=array();
        foreach ($reader->getSheetIterator() as $sheet) {
        	if ($sheet->isActive()) {
	            foreach ($sheet->getRowIterator() as $key => $row) {
	                if($key >= 2){
						$datatoinsert[] = array(
							'plant' => trim($this->issetor($row[0])),
							'area' => trim($this->issetor($row[1])),
							'wh' => trim($this->issetor($row[2])),
							'location' => trim($this->issetor($row[3])),
							'plataforma' => trim($this->issetor($row[4])),
						);
	                }
                }
            }
        }

        $reader->close();
        unlink($file);

        $result = '<span>';

        //Truncate and insert data
		$this->reports_model->truncatelocations();
		$this->reports_model->insertLocation($datatoinsert);

        $result.='Locations file imported '.count($datatoinsert).' records successfully.';
		$result.='</span>';
		echo $result;
    }
	
	public function fileuploadrawtype(){
		$ds          = DIRECTORY_SEPARATOR;
		$storeFolder = 'uploads';
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
	
	public function importrawtype(){
		set_time_limit(0);
		ini_set('memory_limit','-1');
		$ds          = DIRECTORY_SEPARATOR;
		$storeFolder = 'uploads';
		$file = getcwd().$ds.$storeFolder.$ds.$_POST['filename'];
		$reader = ReaderFactory::create(Type::XLSX);
        $reader->open($file);

    	$datatoinsert=array();
        foreach ($reader->getSheetIterator() as $sheet) {
        	if ($sheet->isActive()) {
	            foreach ($sheet->getRowIterator() as $key => $row) {
	                if($key >= 2){
						$datatoinsert[] = array(
							'material' => trim($this->issetor($row[0])),
							'material2' => trim($this->issetor($row[1])),
							'type_raw' => trim($this->issetor($row[2])),
							'general' => trim($this->issetor($row[3])),
						);
	                }
                }
            }
        }

        $reader->close();
        unlink($file);

        $result = '<span>';

        //Truncate and insert data
		$this->reports_model->truncaterawtype();
		$this->reports_model->insertRawType($datatoinsert);

        $result.='Raw type file imported '.count($datatoinsert).' records successfully.';
		$result.='</span>';
		echo $result;
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

	function hunotfound(){
		$this->load->view('hunotfound_view');
	}

	function loadhunotfound(){
		$query="select a.Id, a.hu, a.tag, a.warehouse, b.name, a.datetimecaptured from hu_not_found a
			left join users b on a.iduser=b.Id";
		$primaryKey = 'Id';

		$columns = array(
		    array( 'db' => 'hu', 'dt' => 0 ),
		    array( 'db' => 'tag', 'dt' => 1 ),
		    array( 'db' => 'name', 'dt' => 2 ),
		    array( 'db' => 'datetimecaptured', 'dt' => 3 ),
		    array( 'db' => 'warehouse', 'dt' => 4 ),
		    array( 'db' => 'location',  'dt' => 5 ),
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		//$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}

	function loadhusremoved(){
		$query="SELECT Id, tag, plant, area, warehouse, datetimecaptured, itemcode, status, qty, location, iduser, reasontoedit, idcounter, idauditor, printcounter, toaudit, resultaudit, iduseraudit, datetimeaudit, openbox, auditcounter, precounted, fechaempaque, fecharecibo, fechacaducidad, lot, packingdate, uom FROM hus_history ";
		$primaryKey = 'Id';

		$columns = array(
			array( 'db' => 'tag', 'dt' => 0 ),
			array( 'db' => 'plant', 'dt' => 1 ),
			array( 'db' => 'area', 'dt' => 2 ),
			array( 'db' => 'warehouse', 'dt' => 3 ),
			array( 'db' => 'datetimecaptured', 'dt' => 4 ),
			array( 'db' => 'itemcode', 'dt' => 5 ),
			array( 'db' => 'status', 'dt' => 6 ),
			array( 'db' => 'qty', 'dt' => 7 ),
			array( 'db' => 'location', 'dt' => 8 ),
			array( 'db' => 'iduser', 'dt' => 9 ),
			array( 'db' => 'reasontoedit', 'dt' => 10 ),
			array( 'db' => 'idcounter', 'dt' => 11 ),
			array( 'db' => 'idauditor', 'dt' => 12 ),
			array( 'db' => 'printcounter', 'dt' => 13 ),
			array( 'db' => 'toaudit', 'dt' => 14 ),
			array( 'db' => 'resultaudit', 'dt' => 15 ),
			array( 'db' => 'iduseraudit', 'dt' => 16 ),
			array( 'db' => 'datetimeaudit', 'dt' => 17 ),
			array( 'db' => 'openbox', 'dt' => 18 ),
			array( 'db' => 'auditcounter', 'dt' => 19 ),
			array( 'db' => 'precounted', 'dt' => 20 ),
			array( 'db' => 'fechaempaque', 'dt' => 21 ),
			array( 'db' => 'fecharecibo', 'dt' => 22 ),
			array( 'db' => 'fechacaducidad', 'dt' => 23 ),
			array( 'db' => 'lot', 'dt' => 24 ),
			array( 'db' => 'packingdate', 'dt' => 25 ),
			array( 'db' => 'uom', 'dt' => 26 )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		//$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}

	function issetor(&$var) {
	    return isset($var) ? $var : '';
	}


	function fixhu_v1(){
		// 1.- Loop todos los tags de la tabla debug:
		$rows = $this->db->query("select TRIM(REPLACE(UPPER(tag), 'WTEM', '')) as tag, hu from debug_capture dc 
		where dc.hu <> ''
		order by Id")->result();

		// 2.- Update tags table
		foreach ($rows as $key => $value) {
			$this->db->query("UPDATE tags SET hu = ? WHERE TRIM(REPLACE(UPPER(tag), 'WTEM', '')) = ?", array($value->hu, $value->tag));
		}
	}

	function fixhu(){
		// 1.- Clear hus from tags table
		$this->db->query("UPDATE tags SET hu = ''");
		// 1.- Loop all complete scans from debug_capture table
		$rows = $this->db->query("select REPLACE(TRIM(REPLACE(UPPER(tag), 'WTEM', '')), 'WETM','') as tag, hu 
			from debug_capture dc 
			where dc.hu <> '' and dc.location <> '' and dc.qty <> '' and dc.openbox <> '' and dc.item <> '' and dc.tag <> ''
			order by Id")->result();

		// 2.- Update hus table
		foreach ($rows as $key => $value) {
			$this->db->query("UPDATE tags SET hu = ? WHERE tag = ?", array($value->hu, $value->tag));
		}
	}

	public function typerawvariation(){
		$data['typerawdata'] = $this->reports_model->gettyperawvariation();
		$data['title'] = 'Type Raw Variation Report';
		$this->load->view('typerawvariation_view', $data);
	}



}  