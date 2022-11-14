<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Itemcodes_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    public function saveitem($data){
        $this->db->insert('masterdata', $data);
    }
    public function getitemcode($id){
        $this->db->where('Id',$id);
        $query=$this->db->get('masterdata');
        return $query->row();

    }
    public function saveedit($data, $id){
        $this->db->where('Id', $id);
        $this->db->update('masterdata',$data);
    }
    public function validateitemcode($itemcode, $id){
        if($id<>0){
            $this->db->where('Id <>',$id);
        }
        $this->db->where('itemcode',$itemcode);
        $query=$this->db->get('masterdata');
        return $query->row();
    }
    public function removeitem($id){
        $this->db->where('Id',$id);
        $query=$this->db->delete('masterdata');
    }
}