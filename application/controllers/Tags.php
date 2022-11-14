<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tags extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('tags_model');
		$this->load->library('datatable');
	}
	public function index(){
		$data['areas']=$this->tags_model->getareas();
		$this->load->view('tags_view', $data);
	}
	public function checktags(){
		//file_put_contents('check_'.uniqid().'.txt', print_r($_POST, true));//Revisar archivo
		$data=array();
		parse_str($_POST['data'], $data);

		$data['area']=explode('|', $data['area']);
		$data['area']=$data['area'][1];
		//Validate next tag
		$result=$this->tags_model->getmaxtag();
		$defaultstarttag=$this->tags_model->getconfig()->starttag;
		$nextstarttag=$defaultstarttag;//'00001';
		if($result->maxtag!=null){
			$nextstarttag=str_pad($result->maxtag+1, 5, "0", STR_PAD_LEFT);
		}
		if((int)$data['starttag']>(int)$nextstarttag){
			echo 'Next start tag must be: '.$nextstarttag;
			exit(0);
		}
		//===>
		$result=$this->tags_model->checktags($data);
		if(isset($result->nulls)){
			if($result->nulls==0){
				echo 'nulls';
			}else{
				echo 'no nulls';
			}
		}else{//Create range tags
			$finaltag=(int)$data['finaltag'];
			$starttag=(int)$data['starttag'];
			for ($i=$starttag; $i <=$finaltag ; $i++) {
				$params=array('tag'			=> str_pad($i, 5, "0", STR_PAD_LEFT),
							'area'			=> $data['area'],
							'warehouse'		=> $data['wh'],
							'plant'			=> $data['plant']);
				$this->tags_model->inserttag($params);
			}
			echo 'ok';
		}
	}
	function printtags(){
		$data=array();
		parse_str($_POST['data'], $data);

		/*$cadena='%BTW% /AF="\\\\newalpwbarten03\Sites\Saltillo_Trim\barcode_labels'."\\".'ifa_tag.btw"  /PRN="'.$data['printer'].'" /P /D="<Trigger File Name>" /C=1 /R=2';//.chr(13).chr(10)
        $cadena.='%END%'.chr(13).chr(10);*/
        $config=$this->tags_model->getconfig();
		$cadena='%BTW% /AF="'.$config->btwtagfile.'"  /PRN="'.$data['printer'].'" /P /D="<Trigger File Name>" /C=1 /R=3'.chr(13).chr(10);
        $cadena.='%END%'.chr(13).chr(10);

        for ($i=(int)$data['starttagprint']; $i <=(int)$data['finaltagprint']; $i++) {
        	$tag=str_pad($i, 5, "0", STR_PAD_LEFT);
			$result=$this->tags_model->gettag($tag);
			if(isset($result->warehouse)){
				$cadena.=$config->tagprefix.$tag.'|'.$result->warehouse.'|'.$result->areadesc.'|'.$result->printcounter.chr(13).chr(10);
				$this->tags_model->incrementcounter($tag);
			}
		}

		//echo $cadena.' - ';
		//$nombre_archivo = 'archivo/archivo.txt';
		//$nombre_archivo = '\\\\newalpwbarten03\Sites\Saltillo_Trim\barcode_labels\seriallabel_seq\_Labels\_IFA.dd';
		$nombre_archivo = $config->tagfilesprintpath.'_IFA'.uniqid().'.dd';

		//echo $nombre_archivo;
		//echo '<br/>';
		//$nombre_archivo='databases/testtag.txt';
		if($archivo = fopen($nombre_archivo, "a")){
	        if(fwrite($archivo, $cadena)){
	            //echo "Se ha ejecutado correctamente";
	            echo 'ok';
	        }else{
	            echo "Ocurrió un problema al crear el archivo";
	        }
	        fclose($archivo);
	    }
	}
	function pdftags(){
		$data=array();
		parse_str($_POST['data'], $data);
		//print_r($data);
		$config=$this->tags_model->getconfig();

		$this->load->library('Pdf_tags');
		$this->fpdf->SetAutoPageBreak(false);
		/*
        $this->fpdf->SetAutoPageBreak(true, 12);
        $this->fpdf->SetFillColor(255,255,255);
        $this->fpdf->SetTextColor(0,0,0);   
        $this->fpdf->SetFont('Helvetica','',8);
        */

        for ($i=(int)$data['starttagprint']; $i <=(int)$data['finaltagprint']; $i++) {
        	$tag=str_pad($i, 5, "0", STR_PAD_LEFT);
        	$tag=$config->tagprefix.$tag;
			//$result=$this->tags_model->gettag($tag);
			//=======================================>
			//$this->fpdf->Cell(15,4,$tag,0,0,'',true);
			$this->fpdf->Code128(5,15,$tag,40,10);
			$this->fpdf->SetXY(5,25);
			$this->fpdf->SetFont('Helvetica','',8);
			$this->fpdf->Write(5,'Folio: '.$tag);
			$this->fpdf->SetXY(5,38);
			$this->fpdf->Write(2, utf8_decode('Inventario Físico ').date('m').'-'.date('Y'));

			if($i<(int)$data['finaltagprint']) $this->fpdf->AddPage();
			//=======================================>
			//if(isset($result->warehouse)){
				//$cadena.='F'.$tag.'|'.$result->warehouse.'|'.$result->areadesc.'|'.$result->printcounter.chr(13).chr(10);
				$this->tags_model->incrementcounter($tag);
			//}
		}
		$this->fpdf->SetTitle('Folios '.$data['starttagprint'].'-'.$data['finaltagprint']);
        $this->fpdf->Output('Folios '.$data['starttagprint'].'-'.$data['finaltagprint'].'.pdf','I');
	}
	public function reasignatetags(){
		//file_put_contents('reasig_'.uniqid().'.txt', print_r($_POST, true));//Revisar archivo
		$data=array();
		parse_str($_POST['data'], $data);
		$arrtmp=explode('|', $data['area']);
		$finaltag=(int)$data['finaltag'];
		$starttag=(int)$data['starttag'];
		for ($i=$starttag; $i <=$finaltag ; $i++) {
			$result=$this->tags_model->checktag(str_pad($i, 5, "0", STR_PAD_LEFT));
			$data2=array('tag'		=> str_pad($i, 5, "0", STR_PAD_LEFT),
						'area'		=> $arrtmp[1],
						'warehouse'	=> $data['wh'],
						'plant'	=> $data['plant']);
			if($result->total=='0'){
				$this->tags_model->inserttag($data2);
			}else{
				$this->tags_model->updatetag($data2);
			}
		}
		echo 'ok';
	}
	public function capturetags(){
		$data=array();
		parse_str($_POST['data'], $data);
		$finaltag=$data['finaltagc'];
		$data['finaltag']=$data['finaltagc'];
		$data['starttag']=$data['starttagc'];
		$result=$this->tags_model->checktags($data);
		if(isset($result->nulls)){
			if($result->nulls==0){
				//Update tags!
				for ($i=$data['starttagc']; $i <=$finaltag ; $i++) {
					$data=array('tag'	=> str_pad($i, 5, "0", STR_PAD_LEFT),
								'status'	=> $data['status']);
					$this->tags_model->updatetag($data);
				}
				echo 'ok';
			}else{
				echo 'Tag captured already, Select a tag with NO status.';
			}
		}else{
			echo "Tags doesn't exists";
		}
	}
	public function loaddata(){
		$query="select a.Id, a.tag, a.warehouse, a.area, a.plant, a.printcounter, a.status, a.openbox from tags a";
		$primaryKey = 'Id';

		$columns = array(
		    array( 'db' => 'tag', 'dt' => 0 ),
		    array( 'db' => 'plant',  'dt' => 1 ),
		    array( 'db' => 'area',  'dt' => 2 ),
		    array( 'db' => 'warehouse',  'dt' => 3 ),
		    array( 'db' => 'printcounter',  'dt' => 4 ),
		    array( 'db' => 'status',  'dt' => 5 ),
		    array( 'db' => 'openbox',  'dt' => 6 )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	//locations functions
	public function locations(){
		$data['areas']=$this->tags_model->getareas();
		$this->load->view('locations_view', $data);
	}
	public function loaddatalocations(){
		$query="select a.* from locations a";
		$primaryKey = 'Id';

		$columns = array(
		    array( 'db' => 'plant', 'dt' => 0 ),
		    array( 'db' => 'area',  'dt' => 1 ),
		    array( 'db' => 'wh',  'dt' => 2 ),
		    array( 'db' => 'location',  'dt' => 3 ),
		    array( 'db' => 'plataforma',  'dt' => 4 ),
		    array(
		        'db'        => 'Id',
		        'dt'        => 5,
		        'formatter' => function( $d, $row ) {
		        	$html='<td class="text-center">
								<a href="#" class="print" title="Print" data-lbllocation="'.$row['location'].'">
								<i class="icon-printer"></i>
							</td>
							<td class="text-center">
								<a href="#" class="delete" title="Delete" data-id="'.$d.'">
								<i class="icon-cross"></i>
							</td>';
		        	return $html;
		        }
		    )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	function printlocations(){
		$config=$this->tags_model->getconfig();
		$cadena='%BTW% /AF="'.$config->btwlocationfile.'"  /PRN="'.$_POST['printer'].'" /P /D="<Trigger File Name>" /C=1 /R=3'.chr(13).chr(10);
        $cadena.='%END%'.chr(13).chr(10);

        $cadena.=$_POST['lbllocation'].chr(13).chr(10);

		//echo $cadena.' - ';
		//$nombre_archivo = '\\\\newalpwbarten03\Sites\Saltillo_Trim\barcode_labels\seriallabel_seq\_Labels\_IFA_location.dd';

		$nombre_archivo = $config->locationfilesprintpath.'_IFA_location.dd';


		//echo $nombre_archivo;
		//echo '<br/>';
		if($archivo = fopen($nombre_archivo, "a")){
	        if(fwrite($archivo, $cadena)){
	            //echo "Se ha ejecutado correctamente";
	            echo 'ok';
	        }else{
	            echo "Ocurrió un problema al crear el archivo";
	        }
	        fclose($archivo);
	    }
	}
	public function createlocations(){
		$data=array();
		parse_str($_POST['data'], $data);
		if($data['startlocation']=='0' || $data['endlocation']=='0'){
			$data=array('wh'		=> $data['wh'],
						'location'	=> $data['prefixlocation'],
						'plataforma'=> $data['platform'],
						'idarea'	=> $_POST['area'],
						'active'	=> 1);
			$this->tags_model->createlocations($data);
		}else{
			for ($i=$data['startlocation']; $i <=$data['endlocation']; $i++) {
				$data2=array('wh'		=> $data['wh'],
						'location'	=> $data['prefixlocation'].$i,
						'plataforma'=> $data['platform'],
						'idarea'	=> $_POST['area'],
						'active'	=> 1);
				$this->tags_model->createlocations($data2);
			}
		}
		echo 'ok';
	}
	public function clearlocations(){
		$this->tags_model->clearlocations();
	}
	function deletelocation(){
		$this->tags_model->deletelocation($_POST['id']);
	}
	function getwhbyarea(){
		echo json_encode($this->tags_model->getwhbyarea(explode('|', $_POST['area'])));
	}
	//Audit functions
	function audit(){
		$this->load->view('audit_view');
	}
	function loadtagstoaudit(){
		$query="select a.Id, a.tag, a.area, a.plant, a.printcounter, a.openbox, a.qty, d.cost_price*a.qty as extended, a.resultaudit, c.name, a.datetimeaudit, auditcounter, a.precounted from tags a
		left join masterdata d on d.itemcode=a.itemcode
		left join users c on a.iduseraudit=c.Id 
		";//where a.toaudit=1 or a.openbox='Ok'
		$primaryKey = 'Id';

		$columns = array(
		    array( 'db' => 'tag', 'dt' => 0 ),
		    array( 'db' => 'area',  'dt' => 1 ),
		    array( 'db' => 'plant',  'dt' => 2 ),
		    array( 'db' => 'printcounter',  'dt' => 3 ),
		    array( 'db' => 'openbox',  'dt' => 4 ),

		    array( 'db' => 'qty',  'dt' => 5 ),
		    array(
		        'db'        => 'extended',
		        'dt'        => 6,
		        'formatter' => function( $d, $row ) {
		        	return '$'.number_format($d, 2, '.', ',');
		        }
		    ),

		    array( 'db' => 'resultaudit',  'dt' => 7 ),
		    array( 'db' => 'name',  'dt' => 8 ),
		    array(
		        'db'        => 'datetimeaudit',
		        'dt'        => 9,
		        'formatter' => function($d, $row ) {
		        	$returnv='';
		        	$pos = strpos($d, '1900');
		        	if(strpos($d, '1900') === false){
		        		$returnv=$d;
		        	}
		        	return $returnv;
		        }
		    ),
		    array( 'db' => 'auditcounter',  'dt' => 10 ),
		    array( 'db' => 'precounted',  'dt' => 11 )
		);
		
		$data=$this->datatable->simple($_GET, $query, $primaryKey, $columns);
		$data=$this->utf8_encode_deep($data);
		echo json_encode($data);
	}
	function marktagstoaudit(){
		$data=array();
		parse_str($_POST['data'], $data);
		$finaltag=$data['finaltag'];
		//Update tags!
		for ($i=$data['starttag']; $i <=$finaltag ; $i++) {
			$data=array('tag'		=> str_pad($i, 5, "0", STR_PAD_LEFT),
						'toaudit'	=> 1);
			$this->tags_model->updatetag($data);
		}
		echo 'ok';
	}
	function clearaudit(){
		$this->tags_model->clearaudit();
	}
	function bycoma(){
		$arrtemp=explode(',', $_POST['text']);
		foreach ($arrtemp as $key => $value) {
			$value=trim($value);
			$data=array('tag'		=> str_pad($value, 5, "0", STR_PAD_LEFT),
						'toaudit'	=> 1);
			$this->tags_model->updatetag($data);
		}
	}
	//Routines=====>
	function routines(){
		$data=array();
		$name='file';
		switch ($_POST['type']) {
		    case 'routinesopenbox':
		    	$name='OpenBox';
		    	$data=$this->tags_model->getRoutineOpenBox();
		        break;
		    case 'routinesprecounted':
		    	$name='Precounted';
		    	$data=$this->tags_model->getRoutinePrecounted();
		        break;
		    case 'routinesgreaterthan':
		    	$name='Greater than '.$_POST['greaterthan'];
		        $data=$this->tags_model->greaterthan($_POST['greaterthan']);
		        break;
		    case 'routineslessthan':
		    	$name='Less than '.$_POST['lessthan'];
		        $data=$this->tags_model->lessthan($_POST['lessthan']);
		        break;
		    case 'routinesall':
		    	$name='All audit tags';
		        $data=$this->tags_model->routinesall($_POST['lessthan'], $_POST['greaterthan']);
		        break;
		}
		
		$objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Novarumsystems.com")
                        ->setLastModifiedBy("Novarumsystems.com")
                        ->setTitle($name)
                        ->setSubject($name)
                        ->setDescription($name)
                        ->setKeywords("Novarumsystems.com")
                        ->setCategory("Novarumsystems.com");
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle($name);

        $sheet->setCellValue("A1", 'Date/time');
        $sheet->setCellValue("B1", 'Tag');
        $sheet->setCellValue("C1", 'Area');
        $sheet->setCellValue("D1", 'Plant');
        if($this->config->item('client')=='CVG'){
        	$sheet->setCellValue("E1", 'Warehouse');
        }else{
        	$sheet->setCellValue("E1", 'Rack');
        }
        $sheet->setCellValue("F1", 'Location');
        $sheet->setCellValue("G1", 'Itemcode');
        $sheet->setCellValue("H1", 'Status');
        $sheet->setCellValue("I1", 'Qty');
        $sheet->setCellValue("J1", 'Item description');
        $sheet->setCellValue("K1", 'UOM');
        $sheet->setCellValue("L1", 'Type');
        $sheet->setCellValue("M1", 'Open box');
        $sheet->setCellValue("N1", 'Extended');
        $sheet->setCellValue("O1", 'Cost price');
        $sheet->setCellValue("P1", 'ExtendedByTag');
        if($this->config->item('client')=='AtlanticPrime'){
        	$sheet->setCellValue("Q1", 'Precounted');
        	$sheet->setCellValue("R1", 'Routine');
        }else{
        	$sheet->setCellValue("Q1", 'Routine');
        }

        //--------------> Ancho de las columnas
	        $sheet->getColumnDimension('A')->setAutoSize(true);
	        $sheet->getColumnDimension('B')->setAutoSize(true);
	        $sheet->getColumnDimension('C')->setAutoSize(true);
	        $sheet->getColumnDimension('D')->setAutoSize(true);
	        $sheet->getColumnDimension('E')->setAutoSize(true);
	        $sheet->getColumnDimension('F')->setAutoSize(true);
	        $sheet->getColumnDimension('G')->setAutoSize(true);
	        $sheet->getColumnDimension('H')->setAutoSize(true);
	        $sheet->getColumnDimension('I')->setAutoSize(true);
	        $sheet->getColumnDimension('J')->setAutoSize(true);
	        $sheet->getColumnDimension('K')->setAutoSize(true);
	        $sheet->getColumnDimension('L')->setAutoSize(true);
	        $sheet->getColumnDimension('M')->setAutoSize(true);
	        $sheet->getColumnDimension('N')->setAutoSize(true);
	        $sheet->getColumnDimension('O')->setAutoSize(true);
	        $sheet->getColumnDimension('P')->setAutoSize(true);
	        $sheet->getColumnDimension('Q')->setAutoSize(true);
	        if($this->config->item('client')=='AtlanticPrime'){
	        	$sheet->getColumnDimension('R')->setAutoSize(true);
	        }
        //-----------------------------------> Color
        if($this->config->item('client')=='AtlanticPrime'){
        	$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE8E5E5');
        }else{
        	$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()
	        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	        ->getStartColor()->setARGB('FFE8E5E5');
        }

        $contador=1;
        foreach ($data as $key => $value) {
            $contador++;
            $sheet->setCellValueExplicit("A$contador", $value->datetimecaptured,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("B$contador", $value->tag,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("C$contador", $value->areadesc,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("D$contador", $value->plant,PHPExcel_Cell_DataType::TYPE_STRING);

            $sheet->setCellValueExplicit("E$contador", $value->warehouse,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("F$contador", $value->location,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("G$contador", $value->itemcode,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("H$contador", $value->status,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("I$contador", $value->qty,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("J$contador", $value->description,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("K$contador", $value->uom,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("L$contador", $value->type,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("M$contador", $value->openbox2,PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("N$contador", '$'.number_format($value->extended, 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("O$contador", '$'.number_format($value->cost_price, 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue("P$contador", "=I$contador*O$contador");
            if($this->config->item('client')=='AtlanticPrime'){
	        	$sheet->setCellValueExplicit("Q$contador", $value->precounted,PHPExcel_Cell_DataType::TYPE_STRING);
	        	$sheet->setCellValueExplicit("R$contador", $value->routine,PHPExcel_Cell_DataType::TYPE_STRING);
	        }else{
	        	$sheet->setCellValueExplicit("Q$contador", $value->routine,PHPExcel_Cell_DataType::TYPE_STRING);
	        }
        }
                    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$name.'.xlsx"');
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
	}
	//===================================> Módulo de impresión de etiquetas=>
	function labels(){
		$data['itemcodes']=$this->tags_model->getitemcodes();
		$this->load->view('labels_view', $data);
	}
	function createlabels(){
		$data=array();
		parse_str($_POST['data'], $data);
		$description=$this->tags_model->getItemDescription($data['itemcode']);
		//print_r($data);

		$this->load->library('Pdf_labels');
		$this->fpdf->SetAutoPageBreak(false);
		$this->fpdf->SetFont('Helvetica','B',10);

		$this->fpdf->SetXY(15,31);
		$this->fpdf->Write(2, 'Lote: '.$data['lot']);
		$this->fpdf->SetXY(15,36);
		$this->fpdf->Write(2, 'Fecha empaque');
		$this->fpdf->SetXY(20,41);
		$this->fpdf->SetFont('Helvetica','',10);
		$this->fpdf->Write(2, $this->formatdate2($data['packingdate']));

		$this->fpdf->SetFont('Helvetica','B',12);
		$this->fpdf->SetXY(15,51);
		$this->fpdf->Write(2, $data['itemcode']);
		$this->fpdf->SetXY(15,58);
		$this->fpdf->Write(2, $description);

		$this->fpdf->SetFont('Helvetica','B',16);
		$this->fpdf->SetXY(45,85);
		$this->fpdf->Write(2, $data['weight']);

		$packingdate=strtotime($data['packingdate']);
		$barcode='**'.$data['itemcode'].$data['unit'].'-'.$data['lot'].'|*'.date('dmy',$packingdate).'|-'.$data['weight'];

		//$barcode='**{1}--{3}|*{5}|-{4}';
		//
		// **{10000100S}--{LOTE1}|*{200530}|-{240.2}
		$this->fpdf->Code128(5,100,$barcode,120,10);
		$this->fpdf->SetXY(20,110);
		
		$this->fpdf->SetFont('Helvetica','',8);
		$this->fpdf->Write(5,$barcode);
		
		$this->fpdf->SetTitle('Label');
        $this->fpdf->Output('Label.pdf','I');
	}
}