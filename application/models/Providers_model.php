<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Providers_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    function getproviders(){
        $this->db->where('active', '1');
        $query=$this->db->get('providers');
        return $query->result();
    }
    public function saveprovider($data){
        $this->db->set('active', '1', FALSE);
        $this->db->insert('providers', $data);
        return $this->db->insert_id(); 
    }
    public function saveproviderequivalency($data){
        $this->db->insert('providersequivalencies', $data);
    }
    function getprovider($idprovider){
        $this->db->where('Id', $idprovider);
        $query=$this->db->get('providers');
        return $query->row();
    }
    public function updateprovider($idprovider, $data){
        $this->db->where('Id', $idprovider);
        $this->db->update('providers', $data);
    }

    public function deleteproviderequivalencies($idprovider){
        $this->db->where('idprovider', $idprovider);
        $this->db->delete('providersequivalencies');
    }
}