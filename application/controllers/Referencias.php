<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referencias extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Referencias';
        $this->load->model('Referencias_model');
        $this->load->model('Estados_model');
        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI] );
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        }
    }

    public function index() {
        //redirect(site_url($this->viewControl . "/Admin/"));
    }

    

}

?>