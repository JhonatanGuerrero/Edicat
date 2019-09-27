<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tarifas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Tarifas';
        $this->load->model('Tarifas_model');
        $this->load->model('Estados_model');
        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI] );
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        }
    }

    public function index() {
        redirect(site_url($this->viewControl . "/Admin/"));
    }

     public function Admin() {
        $dataTarifas = $this->Tarifas_model->obtenerTarifasProductos();
        //var_dump($dataTarifas);
        if (isset($dataTarifas) && $dataTarifas == false) {
            $this->session->set_flashdata("error", "Aun no hay Tarifas creadas.");
        }

        $data = new stdClass();
        $data->Controller = "Tarifas";
        $data->title = "Tarifas";
        $data->subtitle = "Listado de Tarifas";
        $data->contenido = $this->viewControl . '/Admin';
        $data->ListaDatos = $dataTarifas;

        $this->load->view('frontend', $data);
    }
    
    public function obtenerTarifaCod() {
        $codTar = $this->input->post("codigo");

        $Tarifa = $this->Tarifas_model->obtenerTarifa($codTar);
        if (!isset($Tarifa) || $Tarifa == false) {
            $arr = array('Error: La tarifa indicada no existe o no está habilitado para ser aplicada.');
            echo json_encode($arr);
        } else {
            echo json_encode($Tarifa);
        }
    }

    public function obtenerTarifaProductoJson() {
        $codigo = $this->input->post("codigo");
        $Tarifa = $this->Tarifas_model->obtenerTarifaPorProducto($codigo);
        if (!isset($Tarifa) || $Tarifa == false) {
            echo 0;
        } else {
            echo json_encode($Tarifa);
        }
    }

}

?>