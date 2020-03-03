<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Index';
        $this->load->model('Pagos_model');
        $this->load->model('Clientes_model');
        $this->load->model('Pedidos_model');
        $this->load->model('Pagos_model');
        $this->load->model('Estados_model');
    }

    public function index() {
        $user = $this->session->userdata('Login');
        if (!isset($user)) {
            redirect(site_url("Login/index/"));
        } else { 
            $data = new stdClass();
            $data->Controller = "Index";
            $data->title = "Inicio";
            $data->subtitle = "Bienvenido a su plataforma";
            $data->contenido = $this->viewControl . '/index'; 

            $this->load->view('frontend', $data); 
        }
    }

    public function Acercade() {
        $user = $this->session->userdata('Login');
        if (!isset($user)) {
            redirect(site_url("Login/index/"));
        } else {
            $data = new stdClass();
            $data->Controller = "Index";
            $data->title = "Acerca de...";
            $data->contenido = $this->viewControl . '/acercade';

            $this->load->view('frontend', $data);
        }
    }
}
