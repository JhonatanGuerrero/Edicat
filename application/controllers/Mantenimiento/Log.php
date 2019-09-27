<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Log extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuarios_model');
        $this->viewControl = 'Log';
        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]);
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        }
    }

    public function Usuarios($usuario)
    {
        $idPermiso = 5;
        $page = validarPermisoPagina($idPermiso);
        
        $dataUser = $this->Usuarios_model->obtenerUsuarioPorCodEP($usuario);
       
        if (isset($dataUser) && $dataUser != false) {
            $dataLog = $this->Log_model->obtenerLogPorUser($dataUser[0]['Codigo']);
            if (isset($dataLog) && $dataLog != false) {
                $data = new stdClass();
                $data->Controller = "Log";
                $data->title = "Registro de Actividades por Usuario";
                $data->subtitle = "Historial de <b>" . $dataUser[0]['Nombre'] . "</b>";
                $data->contenido = $this->viewControl . '/Usuarios';
                $data->ListaDatos = $dataLog;
                $this->load->view('frontend', $data);
            } else {
                $this->session->set_flashdata("error", "El Usuario <b>" . $dataUser[0]['Usuario'] . "</b> no tiene nada en su Log");
                redirect(base_url("/Mantenimiento/Usuarios/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se puede acceder al Log del Usuario <b>" . $dataUser[0]['Usuario'] . "</b>");
            redirect(base_url("/Mantenimiento/Usuarios/Admin/"));
        }
    }

    public function Ver($usuario, $codigo)
    {
        $dataUser = $this->Usuarios_model->obtenerUsuarioPorCodEP($usuario);

        if (isset($dataUser) && $dataUser != false) {
            $dataLog = $this->Log_model->obtenerLogPorCod($codigo);
            if (isset($dataLog) && $dataLog != false) {
                $sentencia = $dataLog[0]["Sentencia"];

                $data = new stdClass();
                $data->Controller = "Log";
                $data->title = "Detalle de Actividad";
                $data->subtitle = "Registros número <b>" . $codigo . "</b>";
                $data->ListaDatos = $dataLog;
                switch ($sentencia) {
                    case 'INSERT':
                    $data->contenido = $this->viewControl . '/Insert';
                        break;
                    
                    case 'UPDATE':
                    $data->contenido = $this->viewControl . '/Update';
                        break;

                    default:
                    $data->contenido = $this->viewControl . '/Ver';
                        break;
                }
                $this->load->view('frontend', $data);
            } else {
                $this->session->set_flashdata("error", "El Registro <b>" . $codigo . "</b> no fue encontrado.");
                redirect(base_url("/Mantenimiento/Log/Usuarios/" . $usuario . "/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se puede acceder al Log del Usuario <b>" . $dataUser[0]['Usuario'] . "</b>");
            redirect(base_url("/Mantenimiento/Usuarios/Admin/"));
        }
    }
}