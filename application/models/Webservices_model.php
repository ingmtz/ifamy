<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Webservices_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    function getTags(){
        $query=$this->db->get('tags');
        //$query=$this->db->query('select Top 1 * from tags');
        return $query->result();
    }
    function getAudits(){
        $query=$this->db->get('audits');
        //$query=$this->db->query('select Top 1 * from tags');
        return $query->result();
    }
    function getItems(){
    	$this->db->select('Id, itemcode, description, uom');
        $query=$this->db->get('masterdata');
        //$query=$this->db->query('select Top 5 * from masterdata');
        return $query->result();
    }
    function getUsers(){
    	$this->db->select('username, password, Id, idprofile');
    	$this->db->where('active', '1');
        $query=$this->db->get('users');
        return $query->result();
    }
    function getLocations(){
    	$this->db->select('Id, wh, location, plataforma');
        $query=$this->db->get('locations');
        return $query->result();
    }
    function updateTag($tag){
        $this->db->where('tag', $tag['tag']);
        unset($tag['tag']);
        $this->db->update('tags',$tag);
    }
    function updateTagTEST($tag){
        $this->db->where('tag', $tag['tag']);
        unset($tag['tag']);
        $this->db->update('tags',$tag);
        
        $errors= $this->db->error();
        print_r($errors);
    }
    function getConfig(){
        $query=$this->db->get('config');
        return $query->row();
    }
    function insertAudit($audit){
        $this->db->insert('audits', $audit);
    }
}