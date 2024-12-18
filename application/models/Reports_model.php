<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reports_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    public function getalllocations(){
        $query=$this->db->query("select upper(wh) as wh, upper(location) as location, plataforma from locations order by wh, location");
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
    public function getprogressdata($type, $params){
        $where='';
        if($params['plant']!='0'){
            $where="and a.warehouse='".$params['plant']."'";
        }
        if($type=='capture'){
            $querystr="select a.warehouse as area, count(*) as total, sum(case when status='Used' then 1 else 0 end) as used,
                        sum(case when status='Voided' then 1 else 0 end) as voided,
                        sum(case when status='Blank' then 1 else 0 end) as blank
                        from tags a
                        where 1=1 ".$where."
                        group by a.warehouse
                        order by ";
        }else{
            $querystr="select a.warehouse as area, sum(case when a.auditcounter=0 then 0 else 1 end) as audit, count(*) as total
            ,case when tmp2.total < ".$params['lessthan']." then 'Less than ".$params['lessthan']."'
            when tmp2.total >=".$params['greaterthan']." then 'Greater than ".$params['greaterthan']."'
            else 'Open box' end as routine
            from tags a 
            left join masterdata b on a.itemcode=b.itemcode
            left join users d on a.iduser=d.Id
            left join perpetuo f on f.itemcode=a.itemcode

            left join (
              select a.itemcode, ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end) * case when a.cost_price is null then 0 else a.cost_price end) as total
              from masterdata a
              left join perpetuo b on a.itemcode=b.itemcode
              left join (
                select a.itemcode, sum(qty) as total
                from tags a
                where not a.itemcode is null
                group by a.itemcode
              ) as temp on temp.itemcode=a.itemcode
                
                where ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end)
                *case when a.cost_price is null then 0 else a.cost_price end)<".$params['lessthan']."
                or ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end)
                *case when a.cost_price is null then 0 else a.cost_price end)>=".$params['greaterthan']."
            ) tmp2 on a.itemcode = tmp2.itemcode

            where a.status = 'Used' and (not tmp2.itemcode is null ";
            if($params['openbox']==1){
                $querystr.=" or (a.openbox='Ok' or a.openbox='1') ";
            }
            $querystr.=') '.$where." 
            group by a.warehouse
            order by ";
        }
        if($this->config->item('client')=='AtlanticPrime'){
            $querystr.="area";
        }else{
            $querystr.="count(*) desc";
        }
        //echo $querystr;exit(0);
        $query=$this->db->query($querystr);
        return $query->result();
    }

    public function getprogressdetail($type, $params){
        $where="and a.area= '".$params['area']."' ";
        if($params['plant']!='0'){
            $where="and a.plant='".$params['plant']."'";
        }
        if($type=='capture'){
            $querystr="select a.tag, a.plant, a.area, a.warehouse, a.status, case when a.auditcounter=0 then 'Pending to audit' else 'Audited' end auditstatus
                        from tags a
                        where 1=1 ".$where;
        }else{
            $querystr="select a.tag, a.plant, a.area, a.warehouse, a.status, case when a.auditcounter=0 then 'Pending to audit' else 'Audited' end auditstatus
            from tags a 
            left join masterdata b on a.itemcode=b.itemcode
            left join users d on a.iduser=d.Id
            left join perpetuo f on f.itemcode=a.itemcode

            left join (
              select a.itemcode, ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end) * case when a.cost_price is null then 0 else a.cost_price end) as total
              from masterdata a
              left join perpetuo b on a.itemcode=b.itemcode
              left join (
                select a.itemcode, sum(qty) as total
                from tags a
                where not a.itemcode is null
                group by a.itemcode
              ) as temp on temp.itemcode=a.itemcode
                
                where ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end)
                *case when a.cost_price is null then 0 else a.cost_price end)<".$params['lessthan']."
                or ((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end)
                *case when a.cost_price is null then 0 else a.cost_price end)>=".$params['greaterthan']."
            ) tmp2 on a.itemcode = tmp2.itemcode

            where a.status = 'Used' and (not tmp2.itemcode is null ";
            if($params['openbox']==1){
                $querystr.=" or (a.openbox='Ok' or a.openbox='1') ";
            }
            $querystr.=') '.$where;
        }
        //echo $querystr;exit(0);
        $query=$this->db->query($querystr);
        return $query->result();
    }


    function getauditprogressdata($plant){
        $where='';
        if($plant!='0'){
            $where="and a.plant='$plant'";
        }
        $querystr="select case when b.orden is null then 0 else b.orden end as orden, b.area, count(*) as total, sum(case when resultaudit is null or resultaudit='' then 0 else 1 end) as audited
                    from tags a
                    left join areas b on a.area=b.Id
                    where b.active=1 and (a.toaudit=1 or a.openbox='1' or a.openbox='ok') $where
                    group by b.orden, b.area
                    order by b.orden";//order by count(*) desc
        $query=$this->db->query($querystr);
        return $query->result();
    }
    public function gettotaltags(){
        $query=$this->db->query('select count(*) as total from tags');
        return $query->row()->total;
    }
    public function getplants(){
        $querystr="select distinct(warehouse) as plant from tags where not warehouse is null order by warehouse";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    public function truncateperpetuo(){
        $this->db->query('TRUNCATE TABLE perpetuo');
    }
    public function truncatemasterdata(){
        $this->db->query('TRUNCATE TABLE masterdata');
    }

    public function truncaterawdata(){
        $this->db->query('TRUNCATE TABLE rawdata');
    }
    function truncatelocations(){
        $this->db->query('TRUNCATE TABLE locations');
    }

    function truncatetags(){
        $this->db->query('TRUNCATE TABLE tags');
    }

    function saveimportdata($data){
        $this->db->insert_batch('perpetuo', $data);
    }
    function saveimportrawdata($data){
        $this->db->insert_batch('rawdata', $data);
    }
    function saveimportdatamasterdata($data){
        $this->db->insert_batch('masterdata', $data);
    }
    function insertLocation($data){
        $this->db->insert_batch('locations', $data);
    }
    function saveimporttags($data){
        $this->db->insert_batch('tags', $data);
    }
    public function getdetailvariation($itemcode){
        $querystr="select a.*, b.description, b.uom, b.type, d.name, a.area as areadesc from tags a 
                    left join masterdata b on a.itemcode=b.itemcode
                    left join users d on a.iduser=d.Id
                    where a.itemcode='$itemcode'";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    public function getvariationreporttotals($plant = '', $area = ''){
        $querystr="select           
                    sum(case when b.onhand is null then 0 else b.onhand end) as onhand, 

                    sum(case when b.onhand is null then 0 else b.onhand end * a.cost_price) as extonhand, 

                    sum(case when temp.total is null then 0 else temp.total end) as counted,
                    
                    sum(case when temp.total is null then 0 else temp.total end * a.cost_price) as extcounted,
                    

                    sum(case when temp.total is null then 0 else temp.total end-case when b.onhand is null then 0 else b.onhand end) as variationqty,

                    sum(abs(case when temp.total is null then 0 else temp.total end-case when b.onhand is null then 0 else b.onhand end)) as variationqtyabs,
                    
                    sum((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end)*case when a.cost_price is null then 0 else a.cost_price end) as extvariation,
                    
                    sum(abs((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end)*case when a.cost_price is null then 0 else a.cost_price end)) as extvariationabs
                    
                    from masterdata a
                    left join perpetuo b on a.itemcode=b.itemcode
                    left join (
                                            select a.itemcode, sum(qty) as total, area, plant
                                            from tags a
                                            where not a.itemcode is null
                                            group by a.itemcode
                                            ) as temp on temp.itemcode=a.itemcode";

        if($plant == 'null' && $area == 'null'){
            $querystr.=" where temp.plant is null and temp.area is null";

        }else if($plant != '' && $area != ''){
            $querystr.=" where temp.plant = '".$plant."' and temp.area = '".$area."'";
        }
        $query=$this->db->query($querystr);
        return $query->row();
    }
    public function getZeroPrice(){
        $querystr="select itemcode from masterdata where cost_price<=0";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    public function checkperpetuoVSMaster(){
        $querystr="select count(itemcode) as total from masterdata a
                where not a.itemcode in (select b.itemcode from perpetuo b)";
        $query=$this->db->query($querystr);
        return $query->row();
    }
    public function getNoInperpetuo(){
        $querystr="select itemcode from masterdata a
                where not a.itemcode in (select b.itemcode from perpetuo b)";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    public function getNoInMasterdata(){
        $querystr="select * from perpetuo where not itemcode in (select itemcode from masterdata)";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    public function setStep($data){
        $this->db->where('Id',1);
        $this->db->update('config', $data);
    }
    public function getStep(){
        $query=$this->db->get('config');
        return $query->row()->validationstep;
    }
    public function getConfig(){
        //Update orden
        /*$querystr="update areas set orden=(

            select top 1 orden from (
            select ROW_NUMBER() OVER(ORDER BY count(*) desc, b.area) AS orden, a.area as idarea, b.area, a.warehouse, count(*) as cuantos
            from tags a
            left join areas b on a.area=b.Id
            group by b.orden, a.area, b.area, a.warehouse
            ) temp1
            where idarea=areas.ID
            );";
        $this->db->query($querystr);*/

        $query=$this->db->get('config');
        return $query->row();
    }
    /*function insertorgetarea($planta, $area){
        $this->db->where('plant', $planta);
        $this->db->where('area', $area);
        $query=$this->db->get('areas');
        $area2=$query->row();
        if(isset($area2->Id)){
            return $area2->Id;
        }else{
            $this->db->insert('areas', array('plant' => $planta, 'area' => $area, 'orden' => 1, 'active' => 1));
            return $this->db->insert_id(); 
        }
    }*/
    function getScannedbyuser(){
        $querystr="select a.plant, a.area, b.name, count(*) as cuantos from tags a
            left join users b on a.iduser=b.Id
            left join scanners c on b.Id=c.assigneduser
            where a.status='Used'
            group by a.plant, a.area, b.name
            order by count(*) desc";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    function getAuditbyuser(){
        $querystr="select b.name, c.profile, count(*) as cuantos from tags a
        left join users b on a.iduseraudit=b.Id
        left join profiles c on b.idprofile=c.Id
        where a.auditcounter>0 and a.status = 'Used'
        group by b.name
        order by count(*) desc";
        $query=$this->db->query($querystr);
        return $query->result();
    }
    function getexporttotalcapture($mode){
        $querystr="select a.*, b.description, b.type, d.name, b.cost_price, e.name as counter, f.name as auditor, a.area as areadesc, case when a.toaudit=1 or a.openbox='Ok' then 'Yes' else 'No' end as toaudit2, b.uom as uom2
                    from tags a 
                    left join masterdata b on a.itemcode=b.itemcode
                    left join users d on a.iduser=d.Id
                    left join users e on a.idcounter=e.Id
                    left join users f on a.iduseraudit=f.Id";
        if($mode=='1'){
            $querystr.=" where a.iduser=".$this->session->userdata('ifatrimuserid')." and a.area='".$this->session->userdata('ifatrimarea')."' and a.plant='".$this->session->userdata('ifatrimplant')."'";
        }
        $query=$this->db->query($querystr);
        return $query->result();
    }

    function getvariationarea(){
        $querystr="select           
        CONCAT(case when temp.plant is null then 'null'  else temp.plant end, '|',case when temp.area is null then 'null' else temp.area end) AS Id,
        case when temp.plant is null then 'NO plant identified'  else temp.plant end AS plant, case when temp.area is null then 'NO area identified' else temp.area end AS area,
        sum((case when temp.total is null then 0 else temp.total end - case when b.onhand is null then 0 else b.onhand end)*case when a.cost_price is null then 0 else a.cost_price end) as extvariation
        from masterdata a
        left join perpetuo b on a.itemcode=b.itemcode
        left join (
                                select a.itemcode, sum(qty) as total, plant, area
                                from tags a
                                where not a.itemcode is null
                                group by a.itemcode
                                ) as temp on temp.itemcode=a.itemcode
           group by
           temp.plant, temp.area";
        $query=$this->db->query($querystr);
        return $query->result();
    }
}