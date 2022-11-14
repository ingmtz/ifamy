<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Settings_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    function getsettings(){
        $query=$this->db->get('config');
        return $query->row();
    }
    function updatesettings($params){
        $this->db->update('config', $params);
    }
}