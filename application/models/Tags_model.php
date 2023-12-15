<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tags_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    function getconfig(){
        $query=$this->db->get('config');
        return $query->row();
    }
    public function getareas(){
        $query=$this->db->query('select distinct plant, area from locations order by plant, area');
        return $query->result();
    }
    function getwh(){
        $query=$this->db->query('select distinct wh from locations order by wh');
        return $query->result();
    }
    function getmaxtag(){
        $query=$this->db->query('select max(cast(tag as unsigned)) as maxtag from tags');
        return $query->row();
    }
    public function checktags($data){
        $finaltag=$data['finaltag'];
        $query=$this->db->query('select sum(case when status is null then 0 else 1 end) as nulls from tags where CAST(tag AS unsigned)>='.$data['starttag'].' and CAST(tag AS unsigned)<='.$finaltag.'');
        return $query->row();
    }
    public function inserttag($data){
        $data['printcounter']='0';
        $data['auditcounter']='0';
        $this->db->insert('tags', $data);
    }
    public function checktag($tag){
        $query=$this->db->query("select count(*) as total from tags where tag='$tag'");
        return $query->row();
    }
    public function updatetag($data){
        $this->db->where('tag',$data['tag']);
        $this->db->update('tags', $data);
    }
    function incrementcounter($tag){
        $this->db->query("update tags set printcounter=printcounter+1 where tag='$tag'");
    }
    function gettag($tag){
        //$this->db->where('tag',$tag);
        //$query=$this->db->get('tags');
        $query=$this->db->query("select a.*, a.area as areadesc from tags a where a.tag='$tag'");
        return $query->row();
    }
    public function createlocations($data){
        $this->db->insert('locations', $data);
    }
    public function clearlocations(){
        $this->db->truncate('locations');
    }
    function deletelocation($id){
        $this->db->where('Id',$id);
        $query=$this->db->delete('locations');
    }
    function getwhbyarea($params){
        $query=$this->db->query("select distinct wh from locations where plant='".$params[0]."' and area='".$params[1]."'");
        return $query->result();
    }
    function clearaudit(){
        $this->db->where('toaudit', 1);
        $this->db->update('tags', array('toaudit'=>'0'));
    }
    //Routines
    function getRoutineOpenBox(){
        $querystr="select a.datetimecaptured, a.tag, a.area as areadesc, a.warehouse, a.location, a.itemcode, b.uom, a.status, a.qty, b.description, b.type, d.name, b.cost_price, a.plant, a.auditcounter,

        tmp2.total as extended
        , case when a.precounted='Ok' or a.precounted='1' then 'Yes' else 'No' end as precounted
        , case when a.openbox='Ok' or a.openbox='1' then 'Yes' else 'No' end as openbox2

            ,'Open box' as routine
            from tags a 
            left join masterdata b on a.itemcode=b.itemcode
            left join users d on a.iduser=d.Id
            left join perpetuo f on f.itemcode=a.itemcode

            left join (
              select a.itemcode, ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end) *case when a.cost_price is null then 0 else a.cost_price end) as total
              from masterdata a
              left join perpetuo b on a.itemcode=b.itemcode
              left join (
                select a.itemcode, sum(qty) as total
                from tags a
                where not a.itemcode is null
                group by a.itemcode
              ) as temp on temp.itemcode=a.itemcode
            ) tmp2 on a.itemcode = tmp2.itemcode

            where not tmp2.itemcode is null and (a.openbox='Ok' or a.openbox='1')
           order by a.area, a.warehouse, a.location, a.tag";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    function getRoutinePrecounted(){
        $querystr="select a.datetimecaptured, a.tag, a.area as areadesc, a.warehouse, a.location, a.itemcode, b.uom, a.status, a.qty, b.description, b.type, d.name, b.cost_price, a.plant, a.auditcounter,

        tmp2.total as extended
        , case when a.precounted='Ok' or a.precounted='1' then 'Yes' else 'No' end as precounted
        , case when a.openbox='Ok' or a.openbox='1' then 'Yes' else 'No' end as openbox2

            ,'Precounted' as routine
            from tags a 
            left join masterdata b on a.itemcode=b.itemcode
            left join users d on a.iduser=d.Id
            left join perpetuo f on f.itemcode=a.itemcode

            left join (
              select a.itemcode, ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end) *case when a.cost_price is null then 0 else a.cost_price end) as total
              from masterdata a
              left join perpetuo b on a.itemcode=b.itemcode
              left join (
                select a.itemcode, sum(qty) as total
                from tags a
                where not a.itemcode is null
                group by a.itemcode
              ) as temp on temp.itemcode=a.itemcode
            ) tmp2 on a.itemcode = tmp2.itemcode

            where not tmp2.itemcode is null and (a.precounted='Ok' or a.precounted='1')
           order by a.area, a.warehouse, a.location, a.tag";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    function greaterthan($param){
        $querystr="select a.datetimecaptured, a.tag, a.area as areadesc, a.warehouse, a.location, a.itemcode, b.uom, a.status, a.qty, b.description, b.type, d.name, b.cost_price, a.plant, a.auditcounter,

        tmp2.total as extended
        , case when a.precounted='Ok' or a.precounted='1' then 'Yes' else 'No' end as precounted
        , case when a.openbox='Ok' or a.openbox='1' then 'Yes' else 'No' end as openbox2

            ,'Greater than ".$param."' as routine
            from tags a 
            left join masterdata b on a.itemcode=b.itemcode
            left join users d on a.iduser=d.Id
            left join perpetuo f on f.itemcode=a.itemcode

            left join (
              select a.itemcode, ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end) *case when a.cost_price is null then 0 else a.cost_price end) as total
              from masterdata a
              left join perpetuo b on a.itemcode=b.itemcode
              left join (
                select a.itemcode, sum(qty) as total
                from tags a
                where not a.itemcode is null
                group by a.itemcode
              ) as temp on temp.itemcode=a.itemcode

            ) tmp2 on a.itemcode = tmp2.itemcode

            where tmp2.total>=".$param."
            order by a.area, a.warehouse, a.location, a.tag
           ";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    function lessthan($param){
        $querystr=$querystr="select a.datetimecaptured, a.tag, a.area as areadesc, a.warehouse, a.location, a.itemcode, b.uom, a.status, a.qty, b.description, b.type, d.name, b.cost_price, a.plant, a.auditcounter,

        tmp2.total as extended
        , case when a.precounted='Ok' or a.precounted='1' then 'Yes' else 'No' end as precounted
        , case when a.openbox='Ok' or a.openbox='1' then 'Yes' else 'No' end as openbox2

            ,'Less than ".$param."' as routine
            from tags a 
            left join masterdata b on a.itemcode=b.itemcode
            left join users d on a.iduser=d.Id
            left join perpetuo f on f.itemcode=a.itemcode

            left join (
              select a.itemcode, ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end) *case when a.cost_price is null then 0 else a.cost_price end) as total
              from masterdata a
              left join perpetuo b on a.itemcode=b.itemcode
              left join (
                select a.itemcode, sum(qty) as total
                from tags a
                where not a.itemcode is null
                group by a.itemcode
              ) as temp on temp.itemcode=a.itemcode

            ) tmp2 on a.itemcode = tmp2.itemcode

            where tmp2.total<".$param."
            order by a.area, a.warehouse, a.location, a.tag
           ";;
        $query=$this->db->query($querystr);
        return $query->result();
    }
    function routinesall($lessthan, $greaterthan){
        $querystr="select a.datetimecaptured, a.tag, a.area as areadesc, a.warehouse, a.location, a.itemcode, b.uom, a.status, a.qty, b.description, b.type, d.name, b.cost_price, a.plant, a.auditcounter,

        tmp2.total as extended

        , case when a.openbox='Ok' or a.openbox='1' then 'Yes' else 'No' end as openbox2

            ,case when tmp2.total < ".$lessthan." then 'Less than ".$lessthan."'
            when tmp2.total >=".$greaterthan." then 'Greater than ".$greaterthan."'
            else 'Open box' end as routine
            from tags a 
            left join masterdata b on a.itemcode=b.itemcode
            left join users d on a.iduser=d.Id
            left join perpetuo f on f.itemcode=a.itemcode

            left join (
              select a.itemcode, ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end) *case when a.cost_price is null then 0 else a.cost_price end) as total
              from masterdata a
              left join perpetuo b on a.itemcode=b.itemcode
              left join (
                select a.itemcode, sum(qty) as total
                from tags a
                where not a.itemcode is null
                group by a.itemcode
              ) as temp on temp.itemcode=a.itemcode
              #Aquí iba el where
            ) tmp2 on a.itemcode = tmp2.itemcode

            /*where (a.openbox='Ok' or a.openbox='1') or tmp2.total<".$lessthan." or tmp2.total>=".$greaterthan."*/
            order by a.area, a.warehouse, a.location, a.tag
           ";
        //echo $querystr;exit(0);
        $query=$this->db->query($querystr);
        return $query->result();
    }
    //=============================>
    function getItemDescription($item){
        $this->db->where('itemcode', $item);
        $query=$this->db->get('masterdata');
        $result=$query->row();
        return isset($result->description) ? $result->description : ''; 
    }
    function getitemcodes(){
        $this->db->select('itemcode');
        $query=$this->db->get('masterdata');
        return $query->result();
    }
}