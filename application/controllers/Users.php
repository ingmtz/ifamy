<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('users_model');
	}
	public function index(){
		$data['users']=$this->users_model->getusers();
		$this->load->view('users_view', $data);
	}
	public function newuser(){
		$data['profiles']=$this->users_model->getprofiles();
		$this->load->view('newusers_view', $data);
	}
	
	public function saveuser(){
		$_POST['changepassword']='1';
		$this->users_model->saveuser($_POST);
		redirect(base_url(). 'users');
	}
	/*function newspecialuser(){
		$data['types']=$this->users_model->gettypes();
		$this->load->view('specialusersnew_view', $data);
	}
	function savespecialuser(){
		$this->users_model->savespecialuser($_POST);
		redirect(base_url(). 'users/specialusers');
	}
	function specialuseredit($id){
		$data['specialuser']=$this->users_model->getspecialuser($id);
		$data['types']=$this->users_model->gettypes();
		$this->load->view('specialuseredit_view', $data);
	}
	function savespecialedit($id){
		$this->users_model->savespecialedit($_POST, $id);
		redirect(base_url(). 'users/specialusers');
	}
	function specialuserdelete(){
		$this->users_model->specialuserdelete($_POST['id']);
	}
	function specialusers(){
		$data['specialusers']=$this->users_model->getspecialusers();
		$this->load->view('specialusers_view', $data);
	}*/
	public function useredit($id){
		$data['user']=$this->users_model->getuser($id);
		$data['profiles']=$this->users_model->getprofiles();
		$this->load->view('useredit_view', $data);
	}
	
	public function saveedit($id){
		$this->users_model->saveedit($_POST, $id);
		redirect(base_url(). 'users');
	}
	
	public function validateusername(){
		$id=0;
		if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		$result=$this->users_model->validateusername(trim($_POST['username']), $id);
		//print_r($result);
		echo (isset($result->Id)==0) ? '{ "valid": true}' : '{ "valid": false}';
	}
	public function delete(){
		$this->users_model->delete($_POST['id']);
	}
	
	function printusers(){
		$config=$this->users_model->getconfig();
		if($_POST['id']!='supervisor'){
			$user=$this->users_model->getuser($_POST['id']);

			$cadena='%BTW% /AF="'.$config->btwuserfile.'"  /PRN="'.$_POST['printer'].'" /P /D="<Trigger File Name>" /C=1 /R=3'.chr(13).chr(10);
	        $cadena.='%END%'.chr(13).chr(10);
	        $cadena.=$user->username.'|'.$user->password.'|'.$user->name.chr(13).chr(10);

	        $nombre_archivo = $config->tagfilesprintpath.'_IFA_user.dd';
	    }else{
	    	$btwsupervisor=$config->btwuserfile;
	    	$btwsupervisor=strtolower($btwsupervisor);
	    	$btwsupervisor=str_replace('ifa_user.btw', 'ifa_supervisor.btw', $btwsupervisor);
	    	$cadena='%BTW% /AF="'.$btwsupervisor.'"  /PRN="'.$_POST['printer'].'" /P /D="<Trigger File Name>" /C=1 /R=3'.chr(13).chr(10);
	        $cadena.='%END%'.chr(13).chr(10);

	        $cadena.='S@ltill01'.chr(13).chr(10);
	        $nombre_archivo = $config->tagfilesprintpath.'_IFA_supervisor.dd';
	    }

		//echo $cadena.' - ';
		//echo $nombre_archivo;
		//echo '<br/>';
		//$nombre_archivo='databases/testuser.txt';
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
}