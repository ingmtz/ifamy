<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webservices extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('webservices_model');
	}	

	function getDatabase(){
		date_default_timezone_set("Mexico/General");
		$unico = time();
		$dir1 = 'databases/';
        if(!is_dir($dir1)){
      		mkdir($dir1);          	
        }

		$this->load->library('BaseDatos');
		$db = new BaseDatos();

		$db->open('databases/ifa'.$unico.'.db');
		$script = "BEGIN TRANSACTION;";

		$script .= "\ncreate table if not exists config (devicename, lastupdate, apiurl, tagprefix, itemprefix); ";
		$script .= "\ncreate table if not exists users (Id, username, password, idprofile); ";
		$script .= "\ncreate table if not exists locations (Id, wh, location, plataforma); ";

		$script .= "\ncreate table if not exists tags (Id, tag, area, plant, datetimecaptured, itemcode, status, qty, warehouse, location, iduser, tosync, toaudit, resultaudit, iduseraudit, datetimeaudit, openbox, auditcounter, reasontoedit); ";
		$script .= "\ncreate table if not exists masterdata (Id, itemcode, description, uom); ";

		/*$script .= "\nCREATE TABLE IF NOT EXISTS cuentas (
					  Id INTEGER NOT NULL,
					  cve_cartera INTEGER NOT NULL,
					  nombre TEXT NOT NULL,
					  idasignado INTEGER NOT NULL,
					  latitud TEXT NOT NULL,
					  longitud TEXT NOT NULL,
					  PRIMARY KEY (Id, cve_cartera)
					);"; */
		$config = $this->webservices_model->getConfig();
		if(isset($config->Id)){
			$script .= "\ninsert into config values('','".date('Y-m-d H:i:s')."', '".base_url()."', '".$db->escape($config->tagprefix)."', '".$db->escape($config->itemprefix)."'); ";
		}

		$tags = $this->webservices_model->getTags();
		foreach ($tags as $key => $value) {
			$script .= "\ninsert into tags values(".$db->escape($value->Id).",'".$db->escape($value->tag)."', '".$db->escape($value->area)."', '".$db->escape($value->plant)."', '".$db->escape($value->datetimecaptured)."', '".$db->escape($value->itemcode)."', '".$db->escape($value->status)."', '".$db->escape($value->qty)."', '".$db->escape($value->warehouse)."', '".$db->escape($value->location)."', '".$db->escape($value->iduser)."', 0, '".$db->escape($value->toaudit)."', '', '', '', '".$db->escape($value->openbox)."', 0, '".$db->escape($value->reasontoedit)."'); ";
		}
		$items = $this->webservices_model->getItems();
		foreach ($items as $key => $value) {
			$script .= "\ninsert into masterdata values(".$db->escape($value->Id).",'".$db->escape($value->itemcode)."', '".$db->escape($value->description)."', '".$db->escape($value->uom)."'); ";
		}
		$users = $this->webservices_model->getUsers();
		foreach ($users as $key => $value) {
			$script .= "\ninsert into users values(".$db->escape($value->Id).",'".$db->escape($value->username)."', '".$db->escape($value->password)."', '".$db->escape($value->idprofile)."'); ";
		}
		$locations = $this->webservices_model->getLocations();
		foreach ($locations as $key => $value) {
			$script .= "\ninsert into locations values(".$db->escape($value->Id).",'".$db->escape($value->wh)."', '".$db->escape($value->location)."', '".$db->escape($value->plataforma)."'); ";
		}
		//echo $script;

		$script .= "\n"."COMMIT;";
	    $ret = $db->exec($script);
	   	$db->close();
	   	
	   	$file_url = 'databases/ifa'.$unico.'.db';
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-Length: ".filesize($file_url));
		header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
		readfile($file_url); 

		unlink($file_url);
	}
	function receiveTags(){
		if(isset($_POST['data'])){if(is_array($_POST['data'])){
			foreach ($_POST['data'] as $key => $tag) {
				unset($tag['Id']);
				unset($tag['tosync']);
				$this->webservices_model->updateTag($tag);
			}
		}}
		echo 'ok';
		//file_put_contents(uniqid().'.txt', print_r($_POST, true));
	}
}
