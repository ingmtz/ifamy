<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
require_once APPPATH."/third_party/PHPExcel.php";
class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}
/**  Define a Read Filter class implementing PHPExcel_Reader_IReadFilter  */ 
		class MyReadFilter implements PHPExcel_Reader_IReadFilter {
		    public function readCell($column, $row, $worksheetName = '') {
		        //  Read rows 1 to 7 and columns A to J only 
		        //if ($row >= 5) {
		           if (in_array($column,array('A','B','D','I','J','F'))) { //range('A','J')
		              return true;
		           }
		        //} 
		        return false;
		    }
		}
?>