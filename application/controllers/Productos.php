<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Productos';
        $this->load->model('Productos_model');
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
        $dataProductos = $this->Productos_model->obtenerProductos();
        //var_dump($dataProductos);
        if (isset($dataProductos) && $dataProductos == false) {
            $this->session->set_flashdata("error", "Aun no hay Productos creados.");
        }

        $data = new stdClass();
        $data->Controller = "Productos";
        $data->title = "Productos";
        $data->subtitle = "Listado de Productos";
        $data->contenido = $this->viewControl . '/Admin';
        $data->ListaDatos = $dataProductos;

        $this->load->view('frontend', $data);
    }

    public function obtenerProductoCod() {
        $codPro = $this->input->post("codigo");

        $producto = $this->Productos_model->obtenerProducto($codPro);
        if (!isset($producto) || $producto == false) {
            $arr = array('Error: El producto indicado no existe o no está habilitado para ser comprado');
            echo json_encode($arr);
        } else {
            echo json_encode($producto);
        }
    }

}

?>