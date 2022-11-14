<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Capture_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    public function getitemcode($itemcode){
        $this->db->select('a.description, a.uom, a.type, a.uom as descriptionuom');
        $this->db->where('a.itemcode',$itemcode);
        //$this->db->join('catuom b','a.uom=b.uom', 'left');
        $query=$this->db->get('masterdata a');
        return $query->row();
    }
    public function gettag($tag){
        $this->db->select('a.*, b.name, a.area as areadesc');
        $this->db->where('tag',$tag);
        $this->db->join('users b','a.iduser=b.Id', 'left');
        $query=$this->db->get('tags a');
        return $query->row();
    }
    public function updatecapture($data){
        $this->db->where('tag', $data['tag']);
        unset($data['tag']);
        if($data['reasontoedit']=='5. Auditado'){//Auditado
            $data['iduseraudit']=$data['iduser'];
            $data['resultaudit']='5. Auditado';
            $this->db->set('datetimeaudit', 'now()', FALSE);
            $this->db->set('auditcounter', 'auditcounter+1', FALSE);
        }
        $this->db->set('datetimecaptured', 'now()', FALSE);
        $this->db->update('tags',$data);
    }
    function getwarehouseByArea($plant, $area){
        $querystr="select distinct(upper(wh)) as wh from locations where plant='$plant' and area='$area' order by wh";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    public function getwarehouse(){
        $querystr="select distinct(upper(wh)) as wh from locations order by wh";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    public function getcounters(){
        $querystr="select Id, name from users where (idprofile=1 or idprofile=3) and active=1 order by name";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    public function getauditors(){
        $querystr="select Id, name from users where (idprofile=1 or idprofile=3) and active=1 order by name";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    public function getlocations($wh){
        $query=$this->db->query("select location from locations where wh='$wh' order by location");
        return $query->result();
    }
    public function getalllocations(){
        $query=$this->db->query("select upper(wh) as wh, upper(location) as location, plataforma from locations order by wh, location");
        return $query->result();
    }
    public function resetcapture($id){
        $this->db->where('tag', $id);
        $data=array('datetimecaptured'  =>null,
                    'itemcode'          =>null,
                    'status'            =>null,
                    'qty'               =>null,
                    'iduser'            =>null,
                    'location'          =>null,
                    'idcounter'         =>null,
                    'idauditor'         =>null,
                    'openbox'           =>null,
                    'reasontoedit'      =>null);
        $this->db->update('tags',$data);
    }
    function getConfig(){
        $query=$this->db->get('config');
        return $query->row();
    }
    public function getStep(){
        $query=$this->db->get('config');
        return $query->row()->validationstep;
    }
}