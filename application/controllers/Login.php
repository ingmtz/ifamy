<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Login extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('login_model');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form'));
        $this->load->database('default');
    }
    
    public function index(){
        switch ($this->session->userdata('ifatrimidprofile')) {
            case '':
                $data['token'] = $this->token();
                $data['titulo'] = 'Login';
                $data['areas'] = $this->login_model->getareas();
                $this->load->view('login_view',$data);
                break;
            case '1'://Analist
                redirect(base_url().'reports/progress');
                break;
            case '2'://Data capturer
                redirect(base_url().'capture');
                break;
            default://Por defecto los usuarios se van aquí
                redirect(base_url().'capture');
                break;
        }
    }
 
    public function new_user(){
        //echo $this->input->post('token')."-->".$this->session->userdata('token');exit(0);
        if($this->input->post('token') && $this->input->post('token') == $this->session->userdata('token'))
        {
            $this->form_validation->set_rules('username', 'username', 'required|trim|min_length[2]|max_length[150]');
            $this->form_validation->set_rules('password', 'password', 'required|trim|min_length[2]|max_length[150]');
 
            //lanzamos mensajes de error si es que los hay
            
                    
            if($this->form_validation->run() == FALSE)
            {
                $this->index();
            }else{
                $username = $this->input->post('username');
                $password = $this->input->post('password');//sha1($this->input->post('password'));
                $check_user = $this->login_model->login_user($username,$password);
                //print_r($check_user);exit(0);
                if($check_user == TRUE){
                    //------->
                    $idprofile=$check_user->idprofile;
                    if($idprofile==4 || $idprofile==5) $idprofile=3;
                    //$area = $this->login_model->getAreaName($this->input->post('area'));
                    $tmparr= explode('|', $this->input->post('area'));
                    $data = array(
                            'ifatrimis_logued_in'  =>         TRUE,
                            'ifatrimuserid'        =>         $check_user->Id,
                            'ifatrimidprofile'     =>         $idprofile,
                            'ifatrimprofile'       =>         $check_user->profile,
                            'ifatrimusername'      =>         $check_user->username,
                            'ifatrimname'          =>         $check_user->name,
                            'ifatrimplant'         =>         $tmparr[0],
                            'ifatrimarea'          =>         $tmparr[1],
                            'changepassword'       =>         $check_user->changepassword,
                            'inventorytype'        =>         ''
                            );
                    if($this->config->item('client')=='CVG'){
                        $it = $this->login_model->getConfig();
                        $data['inventorytype']=$this->config->item('title').' '.$it->inventorytype;
                    }

                    $this->session->set_userdata($data);
                    $this->index();
                }
            }
        }else{
        	redirect(base_url().'login');

        }
    }
    public function token(){
        $token = md5(uniqid(rand(),true));
        $this->session->set_userdata('token',$token);
        return $token;
    }
    public function logout(){
        //$this->session->sess_destroy();
        $data = array(
                'ifatrimis_logued_in'  =>         FALSE,
                'ifatrimuserid'        =>         '',
                'ifatrimidprofile'     =>         '',
                'ifatrimprofile'       =>         '',
                'ifatrimusername'      =>         '',
                'ifatrimname'          =>         '',
                'ifatrimarea'          =>         ''
                );
        $this->session->set_userdata($data);
        redirect(base_url().'login');
    }
}