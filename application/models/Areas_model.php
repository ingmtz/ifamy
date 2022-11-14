<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Areas_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    public function getareas(){
        $this->db->where('active','1');
        $query=$this->db->get('areas');
        return $query->result();
    }
    public function savearea($data){
        $data['active']=1;
        $this->db->insert('areas', $data);
    }
    public function getarea($id){
        $this->db->where('id',$id);
        $query=$this->db->get('areas');
        return $query->row();
    }
    public function saveedit($data, $id){
        //Update tags with this area
        $this->db->where('plant', $data['plant_ant']);
        $this->db->where('area', $data['area_ant']);
        $this->db->update('tags',array('area'=>$data['area'], 'plant'=>$data['plant']));
        unset($data['plant_ant']);
        unset($data['area_ant']);
        //------>
        $this->db->where('Id', $id);
        $this->db->update('areas',$data);
    }
    public function delete($id){
        $this->db->where('Id',$id);
        $query=$this->db->update('areas', array('active'=>'0'));
    }
}