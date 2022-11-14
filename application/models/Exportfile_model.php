<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exportfile_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    public function getdata(){//CONVERT(varchar, a.datetimecaptured, 101)
    	//and a.area=b.area
        $querystr="select a.datetimecaptured as date, a.tag, a.itemcode, b.plataforma as dimm, a.qty, c.uom,
a.status
from tags a
left join (
select distinct z1.wh, z1.location, z1.plataforma
from locations z1 
) b on a.warehouse=b.wh and a.location=b.location
left join masterdata c on a.itemcode = c.itemcode
where not datetimecaptured is null
union all
select a.datetimecaptured as date, a.tag, a.itemcode, b.plataforma as dimm, a.qty, c.uom,
a.status
from tags a
left join (
select distinct z1.wh, z1.location, z1.plataforma
from locations z1 
) b on a.warehouse=b.wh and a.location=b.location
left join masterdata c on a.itemcode = c.itemcode
where datetimecaptured is null";
        $query=$this->db->query($querystr);
        return $query->result();
    }
}