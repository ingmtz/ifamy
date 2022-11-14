<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//include_once('fpdf/fpdf.php');
require('PDF_Code128.php');
class Pdf_labels extends FPDF {
	public function __construct() {
		//$pdf = new FPDF('L', 'mm', array(51,51));//2 x 2 pulgadas
		$pdf=new PDF_Code128('L', 'mm', array(127,127));//5 x 5 pulgadas
		$pdf->SetMargins(0, 0);
		$pdf->SetFont('Arial','',10);
		$pdf->AliasNbPages();
		$pdf->AddPage();
		
		$CI =& get_instance();
		$CI->fpdf = $pdf;
	}
}		