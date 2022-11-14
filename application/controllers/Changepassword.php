<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Changepassword extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }
    public function index() {
        $this->load->view('changepassword_view');
    }
    public function update(){
        $this->session->set_userdata('changepassword', '0');
        $_POST['changepassword']='0';
        $this->users_model->saveedit($_POST, $this->session->userdata('ifatrimuserid'));
    }
}
