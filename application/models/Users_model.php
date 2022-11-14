<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class users_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    function getconfig(){
        $query=$this->db->get('config');
        return $query->row();
    }
    public function getusers(){
        $this->db->select('a.Id, a.name, a.username, b.profile');
        $this->db->join('profiles b', 'a.idprofile=b.Id');
        $this->db->where('a.active','1');
        $query=$this->db->get('users a');
        return $query->result();
    }
    function getspecialusers(){
        $this->db->select('a.Id, a.name, b.type');
        $this->db->join('specialtypes b', 'a.idtype=b.Id');
        $this->db->where('a.active','1');
        $query=$this->db->get('specialusers a');
        return $query->result();
    }
    public function saveuser($data){
        $this->db->set('creationdate', 'now()', FALSE);
        $this->db->set('active', '1', FALSE);
        $this->db->insert('users', $data);
    }
    function savespecialuser($data){
        $this->db->set('active', '1', FALSE);
        $this->db->insert('specialusers', $data);
    }
    public function getprofiles(){
         $query=$this->db->get('profiles');
        return $query->result();
    }
    function gettypes(){
         $query=$this->db->get('specialtypes');
        return $query->result();
    }
    public function getuser($id){
        $this->db->where('Id',$id);
        $query=$this->db->get('users');
        return $query->row();
    }
    function getspecialuser($id){
        $this->db->where('Id',$id);
        $query=$this->db->get('specialusers');
        return $query->row();
    }
    public function saveedit($data, $id){
        $this->db->where('Id', $id);
        $this->db->update('users',$data);
    }
    function savespecialedit($data, $id){
        $this->db->where('Id', $id);
        $this->db->update('specialusers',$data);
    }
    public function validateusername($username,$id){
        if($id<>0){
            $this->db->where('Id <>',$id);
        }
        $this->db->where('username',$username);
        $this->db->where('active',1);
        $query=$this->db->get('users');
        return $query->row();
    }
    public function delete($id){
        $this->db->where('Id',$id);
        $query=$this->db->update('users', array('active'=>'0'));
    }
    function specialuserdelete($id){
        $this->db->where('Id',$id);
        $query=$this->db->update('specialusers', array('active'=>'0'));
    }
}