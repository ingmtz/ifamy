<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function login_user($username,$password){
        $this->db->select('users.Id, name, username, idprofile, profile, changepassword');
        $this->db->where('active',1);
        $this->db->where("username = '$username' AND password ='$password'");
        $this->db->join('profiles', 'profiles.Id = users.idprofile');
        $query = $this->db->get('users');
        //echo $this->db->last_query();exit(0);
        //var_dump($this->db);
        //exit(0);
        //print_r($query->result());exit(0);
        if(sizeof($query->result()) == 1){//Regresa -1 $query->num_rows()
            return $query->row();
        }else{
            $this->session->set_flashdata('loginfailed','Login failed!');
            redirect(base_url().'login','refresh');
        }
    }
    public function getareas(){
        $query = $this->db->query('select distinct plant, area from locations');
        return $query->result();
    }
    function getConfig(){
        $this->db->select('inventorytype');
        $query=$this->db->get('config');
        return $query->row();
    }
    /*function getAreaName($id){
        $this->db->where('Id',$id);
        $query = $this->db->get('areas');
        return $query->row();
    }*/
}