<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Captureproviders_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    function getproviders(){
        $query=$this->db->get('providersencoder');
        return $query->result();
    }
    public function saveprovider($data){
        $this->db->set('datetime', 'now()', FALSE);
        $this->db->insert('providerscodes', $data);
    }
    function getproviderscodes(){
        $this->db->select('B.provider, A.apCode, A.providerCode, A.datetime');
        $this->db->join('providersencoder B', 'B.Id = A.idprovider', 'inner');
        $query=$this->db->get('providerscodes A');
        return $query->result();
    }
}