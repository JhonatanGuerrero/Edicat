<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Eventos';
        $this->load->model('Eventos_model');
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

    public function obtenerEventosCod() {
        $codEve = $this->input->post("codigo");

        $Eventos = $this->Eventos_model->obtenerEvento($codEve);
        if (!isset($Eventos) || $Eventos == false) {
            $arr = array('Error: El Evento indicado no existe o no está habilitado para ser vinculado.');
            echo json_encode($arr);
        } else {
            echo json_encode($Eventos);
        }
    }

}

?>