<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Databaseconfig_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    function cleandatabase(){
        //$this->db->query("truncate table areas;");
        $this->db->query("truncate table devices;");
        $this->db->query("truncate table locations;");
        $this->db->query("truncate table masterdata;");
        $this->db->query("truncate table perpetuo;");
        $this->db->query("truncate table scanners;");
        $this->db->query("truncate table scannersrecords;");
        $this->db->query("truncate table tags;");
        $this->db->query("truncate table users;");
        $this->db->query("truncate table audits;");
        $this->db->query("insert into `users`(`name`,`username`,`password`,`idprofile`,`active`,`creationdate`,`changepassword`) values ('Administrator','admin','admin',3,1,'2020-02-24 12:15:17',0);");
        //$this->db->query("insert into `areas`(`orden`,`plant`,`area`,`active`) values (1,'Test plant','Test Area',1);");
    }
    function cleanmaindata(){
        $this->db->query("truncate table devices;");
        $this->db->query("truncate table locations;");
        $this->db->query("truncate table masterdata;");
        $this->db->query("truncate table perpetuo;");
        $this->db->query("truncate table scannersrecords;");
        $this->db->query("truncate table tags;");
        $this->db->query("truncate table audits;");
    }
}