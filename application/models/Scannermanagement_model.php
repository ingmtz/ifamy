<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Scannermanagement_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    function savescanner($data){
        if($data['assigneduser']!='0'){
            $this->db->set('assignmenttime', 'now()', FALSE);
            //$this->db->set('endassignment', null, TRUE);
        }else{
            $this->db->set('assignmenttime', null, TRUE);
        }
        $this->db->set('creationdate', 'now()', FALSE);
        $this->db->insert('scanners', $data);
    }
    function validatescanner($serial, $id){
        if($id<>0){
            $this->db->where('Id <>',$id);
        }
        $this->db->where('serial',$serial);
        $this->db->where('assignmentstatus',1);
        $this->db->where('active',1);
        $query=$this->db->get('scanners');
        return $query->row();
    }
    function validateuser($assigneduser, $id){
        if($id<>0){
            $this->db->where('Id <>',$id);
        }
        $this->db->where('assigneduser',$assigneduser);
        $this->db->where('assignmentstatus',1);
        $this->db->where('active',1);
        $query=$this->db->get('scanners');
        return $query->row();
    }
    function getscanner($id){
        $this->db->where('Id',$id);
        $query=$this->db->get('scanners');
        return $query->row();
    }
    function saveeditscanner($data, $id){
        if($data['assigneduser']!='0'){
            $this->db->set('assignmenttime', 'now()', FALSE);
            //$this->db->set('endassignment', null, TRUE);
        }else{
            $this->db->set('unassignmenttime', 'now()', FALSE);
        }
        $this->db->where('Id', $id);
        //unset($data['notes']);
        $this->db->update('scanners',$data);

        $this->db->select('assignmenttime');
        $this->db->where('Id', $id);
        $query=$this->db->get('scanners');
        return $query->row()->assignmenttime;
    }
    function removescanner($id){
        $this->db->select('Id, name');
        $this->db->where('Id',$id);
        $query=$this->db->delete('scanners');
    }
    function getUsers(){
        $this->db->order_by("name", "asc");
        $this->db->where('active', 1);
        $query=$this->db->get('users');
        return $query->result();
    }
    function getAreas(){
        $query=$this->db->query('select distinct plant, area from locations');
        return $query->result();
    }
    function getunassignator($id){
        $this->db->select('unassignator');
        $this->db->where('Id', $id);
        $query=$this->db->get('scanners');
        return $query->row()->unassignator;
    }
    function getunassignmenttime($id){
        $this->db->select('unassignmenttime');
        $this->db->where('Id', $id);
        $query=$this->db->get('scanners');
        return $query->row()->unassignmenttime;
    }
    function getassigneduser($id){
        $this->db->select('assigneduser');
        $this->db->where('Id', $id);
        $query=$this->db->get('scanners');
        return $query->row()->assigneduser;
    }
    function getassignator($id){
        $this->db->select('assignator');
        $this->db->where('Id', $id);
        $query=$this->db->get('scanners');
        return $query->row()->assignator;
    }
    function setrecordscanner($data){
        $this->db->insert('scannersRecords', $data);
    }
    function gethistoryscanner($id){
        $query=$this->db->query("select CASE a.assignmentstatus WHEN 1 THEN 'Assignment' ELSE 'Unassignment' END as assignment, b.name as assigneduser, CASE a.assignmentstatus WHEN 1 THEN c.name ELSE d.name END as assignator, CASE a.assignmentstatus WHEN 1 THEN a.assignmenttime ELSE a.unassignmenttime END as datetime, a.notes from scannersRecords a 
            inner join users b on b.Id = a.assigneduser
            inner join users c on c.Id = a.assignator
            left join users d on d.Id = a.unassignator
            where a.idscanner =".$id);
        return $query->result();
    }
}