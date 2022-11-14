<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exportfile extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('exportfile_model');
	}
	public function index(){
		$this->load->view('exportfile_view');
	}
	public function download(){
		//set_time_limit(1800);//30 minutos de límite
		set_time_limit(0);
    	ini_set('memory_limit', '-1');
		$data=$this->exportfile_model->getdata();
		$objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("CVG")
                        ->setLastModifiedBy("Novarumsystems.com")
                        ->setTitle('tstemplate5')
                        ->setSubject('tstemplate5')
                        ->setDescription('tstemplate5')
                        ->setKeywords("CVG")
                        ->setCategory("Novarumsystems.com");
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('tstemplate5');

        $sheet->setCellValue("A1", 'Journal');
        $sheet->setCellValue("B1", 'Line No');
        $sheet->setCellValue("C1", 'Date');
        $sheet->setCellValue("D1", 'Tag');
        $sheet->setCellValue("E1", 'Item number');
        $sheet->setCellValue("F1", 'Dimension No.');
        $sheet->setCellValue("G1", 'Quantity');
        $sheet->setCellValue("H1", 'Employee');
        $sheet->setCellValue("I1", 'Unit');
        $sheet->setCellValue("J1", 'Status');
        $sheet->setCellValue("K1", 'Posted');
        $sheet->setCellValue("L1", 'Journal type');

        for ($i=1; $i < 13; $i++) {
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
			$status='000::';
			switch ($value->status) {
			    case 'Used':
			    	$status='001::Used';
			        break;
			    case 'Voided':
			        $status='002::Voided';
			        break;
			    case 'Blank':
			        $status='003::Missing';
			        break;
			}
            $contador++;
            //$sheet->setCellValue("A$contador", '');
            $sheet->setCellValueExplicit("A$contador", '',  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("B$contador", $contador-1,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("C$contador", $value->date,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("D$contador", $value->tag,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("E$contador", $value->itemcode,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("F$contador", $value->dimm,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("G$contador", $value->qty,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("H$contador", '',  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("I$contador", $value->uom,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("J$contador", $status,  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("K$contador", '000::No',  PHPExcel_Cell_DataType::TYPE_STRING);
	        $sheet->setCellValueExplicit("L$contador", '006::Tag counting',  PHPExcel_Cell_DataType::TYPE_STRING);
        }
        //-----------------------------------> Color
		// Save Excel 2007 file
		/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.'tstemplate5'.'.xlsx"');
		header('Cache-Control: max-age=0');*/

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="tstemplate5.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//Excel5	Excel2007
		//$objWriter->setIncludeCharts(TRUE);
		$objWriter->save('php://output');
		exit;
	}
}