<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Welcome';
        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar.");
            redirect(site_url("Login/index/"));
        }
    }

    public function index() {
        $data = new stdClass();
        $data->Controller = "Welcome";
        $data->title = "Inicio";
        $data->subtitle = "Inicio";
        $data->contenido = $this->viewControl . '/index';
        //$this->load->view('frontend', $data);

        $this->load->view('welcome_message');
    }

}
