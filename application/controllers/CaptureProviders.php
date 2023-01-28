<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/Spout/Autoloader/autoload.php";
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Type;

class CaptureProviders extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('captureproviders_model');
	}
	function index(){
		$data['providers']=$this->captureproviders_model->getproviders();
		$this->load->view('captureproviders_view', $data);
	}
    function downloaddataproviders(){
		$this->load->view('downloaddataproviders_view');
	}
    
	function save(){
		$data=array();
		parse_str($_POST['data'], $data);

		$this->captureproviders_model->saveprovider($data);
	}

    function export(){
		set_time_limit(0);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		ini_set('memory_limit', '-1');
		$providerscodes=$this->captureproviders_model->getproviderscodes();

		$writer = WriterFactory::create(Type::XLSX);
		$writer->openToFile('assets/Proveedores.xlsx');
		$writer->addRow(array('Proveedor',
								'Código AP',
								'Código Proveedor',
								'Fecha/Hora'));


		foreach ($providerscodes as $key => $codes) {
			$writer->addRow(array($codes->provider,
									$codes->apCode,
									$codes->providerCode,
									$codes->datetime));
		}

		$writer->close();

		header('Content-Type: application/xlsx');
		header('Content-Disposition: attachment; filename=Proveedores.xlsx');
		header('Pragma: no-cache');
		readfile("assets/Proveedores.xlsx");
	}

}