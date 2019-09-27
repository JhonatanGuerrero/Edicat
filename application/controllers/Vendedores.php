<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedores extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Vendedores';
        $this->load->model('Vendedores_model');
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
    
    public function obtenerVendedoresCod() {
        $codVen = $this->input->post("codigo");

        $Vendedor = $this->Vendedores_model->obtenerEvento($codVen);
        if (!isset($Vendedor) || $Vendedor == false) {
            $arr = array('Error: El vendedor indicado no existe o no está habilitado para ser vinculado.');
            echo json_encode($arr);
        } else {
            echo json_encode($Vendedor);
        }
    }

}

?>