<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
	public $permissions = array();
	public $menu;
	public $header;
	function __construct(){
		parent::__construct();
		date_default_timezone_set("Mexico/General");
		$this->load->library(array('session','permission','excel'));
		//Cargar los permisos disponibles de acuerdo al perfil
		if (!$this->permissions = $this->permission->get_user_permissions($this->session->userdata('ifatrimidprofile'))){
			//redirect(base_url().'login'); //Produce redireccionamientos infinitos si el perfil no tiene permiso
			redirect(base_url().'noaccess');
        }
        //Prevenir url sin controlador
        if($this->uri->segment(1)==''){
        	redirect(base_url().$this->router->default_controller);
        }
        if($this->session->userdata('changepassword')=='1' && $this->uri->segment(1)!='changepassword'){
            redirect(base_url().'changepassword');
        }
        //Url a buscar en los permisos
        $url=$this->uri->segment(1);
        $url=strtolower($url);
        if($this->uri->segment(2)!='') { //Si quisieramos validar el acceso a cada función de los controladores
        	$url.='/'.$this->uri->segment(2);
        }
        //echo $url;exit(0);
        //Consultar los permisos en base a la url, acceso a todo el controlador o función en específico.
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		   # Ejecuta si la petición es a través de AJAX.
		}else{
			//print_r( $this->permissions);
			//echo '--->'.$url;exit(0);
		   if (!in_array($url, $this->permissions) && !in_array($this->uri->segment(1).'/*', $this->permissions)){
	       		redirect(base_url().'noaccess');
	       }
		}
        //Language========>
        $defaultlang='english';
        if($this->session->userdata('site_lang')!==null){
        	if($this->session->userdata('site_lang')!=''){
        		$defaultlang=$this->session->userdata('site_lang');
        	}
        }else{
        	$this->session->set_userdata('site_lang', $defaultlang);
        }
        //echo $this->session->userdata('site_lang');exit(0);
        $this->lang->load('idioma', $this->session->userdata('site_lang'));
        //Load menus----------------------->
        $this->sidebar=$this->load->view('sidebar_view',array('active'=>$url),true);
        $this->header=$this->load->view('header_view',array(),true);
	}
	public function exportexcel(){
		set_time_limit(1200);
		$tabledata=json_decode($_POST['tabledata']);
		//print_r($tabledata);exit(0);
		$objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("CVG")
                        ->setLastModifiedBy("Novarumsystems.com")
                        ->setTitle($_POST['title'])
                        ->setSubject($_POST['title'])
                        ->setDescription($_POST['title'])
                        ->setKeywords("CVG")
                        ->setCategory("Novarumsystems.com");
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle($_POST['title']);

        //Headers & Data
        $colString ='';
        foreach ($tabledata as $kr => $row) {
        	foreach ($row as $kc => $column) {
        		$rownumber=$kr+1;
        		$colString = PHPExcel_Cell::stringFromColumnIndex($kc);
	        	//$sheet->setCellValue($colString.$rownumber, $column);
	        	if($column=='-colspan-') $column='';
	        	$column=str_replace('<b>', '', $column);
	        	$column=str_replace('</b>', '', $column);
	        	$sheet->setCellValueExplicit($colString.$rownumber, (string)$column,PHPExcel_Cell_DataType::TYPE_STRING);
	        	$sheet->getColumnDimension($colString)->setAutoSize(true);
        	}
        }
        //Merge cells
       	/*$sheet->mergeCells('C1:E1');
       	$style = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
	        )
	    );
    	$sheet->getStyle('C1:E1')->applyFromArray($style);*/
        //exit(0);
        //-----------------------------------> Color
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$colString.'1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('DDE6F1');//ARGB
		// Save Excel 2007 file
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=report.xlsx");
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		//$objWriter->save('test.xlsx');



		/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$_POST['title'].'.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->setIncludeCharts(TRUE);
		$objWriter->save('php://output');
		exit;*/
	}
	function formatdate($date){
		$vector_date = explode("/",$date);
		$aux = $vector_date[2];
		$vector_date[2] = $vector_date[0];
		$vector_date[0] = $aux;
		return implode("-",$vector_date);
	}
	function formatdate2($date){
		$vector_date = explode("-",$date);
		if(sizeof($vector_date)>1){
			$aux = $vector_date[2];
			$vector_date[2] = $vector_date[0];
			$vector_date[0] = $aux;
			return implode("/",$vector_date);
		}else{
			return '';
		}
	}
	function utf8_encode_deep(&$array) {
	    array_walk_recursive($array, function(&$item, $key){
	    	if(is_null($item)){$item='';}
	        if(!mb_detect_encoding($item, 'utf-8', true)){
	                $item = utf8_encode($item);
	        }
    	});
    	return $array;
	}
}
