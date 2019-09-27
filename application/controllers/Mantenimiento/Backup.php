<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Backup';
        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://" . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI]);
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        }
    }

    function index() {
        $data = new stdClass();
        $data->Controller = "Backup";
        $data->title = "Backup's";
        $data->subtitle = "Opciones de Backup";
        $data->contenido = $this->viewControl . '/Index';

        $this->load->view('frontend', $data);
    }

    function Nuevo() {
        $this->buildBackup();
    }

    function buildBackup() {
        $fecha = date("Y-m-d");
        $this->backupBaseDeDatos($fecha);
    }

    function backupBaseDeDatos($fecha) {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->library('zip');

        try {
            $nombreBaseDeDatos = $this->db->database;
            $db_formato = array(
                "format" => 'zip',
                "filename" => $nombreBaseDeDatos . '_' . $fecha . '.sql'
            );
            $backup = & $this->dbutil->backup($db_formato);
            $nombreArchivo = "backup_" . $fecha . ".edicat";
            $ruta = 'db_backup/' . $nombreArchivo;
            write_file($ruta, $backup);
            force_download($nombreArchivo, $backup);

            echo "Base de Datos: OK <br>";
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
    }

}

?>