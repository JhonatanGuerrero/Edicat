<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function validarPermisoPagina($idPermiso) {
    $CI = get_instance();
    $usuario = $CI->session->userdata('Codigo');
    if ($usuario != $CI->config->item('usuDesarrollador')) {
        return true;
    } else {
        $CI->load->model('Permisos_model');
        $listaPermisosUsu = $CI->Permisos_model->validarPermisosXUsuario($idPermiso, $usuario);

        $nombre = $CI->Permisos_model->obtenerNombrePermiso($idPermiso);
        $CI->session->set_flashdata("permisos", "El Usuario <b>" . $CI->session->userdata('Nombre') . "</b> no tiene permisos para acceder a la Página: <b>" . $nombre[0]["Nombre"] . "</b>.");
        $mensaje = "<script type=\"text/javascript\">location.href = '" . base_url() . "';</script>";

        if ($listaPermisosUsu == FALSE) {
            echo $mensaje;
            exit;
        } else {
            if ($listaPermisosUsu[0]["Habilitado"] != 1) {
                echo $mensaje;
                exit;
            }
        }
    }
}

function validarPermisoAcciones($idPermiso) {
    $CI = get_instance();
    $usuario = $CI->session->userdata('Codigo');
    if ($usuario != $CI->config->item('usuDesarrollador')) {
        return true;
    } else {
        $CI->load->model('Permisos_model');
        $listaPermisosUsu = $CI->Permisos_model->validarPermisosXUsuario($idPermiso, $usuario);
        if ($listaPermisosUsu == FALSE) {
            return false;
        } else {
            if ($listaPermisosUsu[0]["Habilitado"] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }
}

function validarPermisoBoton($idPermiso) {
    $CI = get_instance();
    $usuario = $CI->session->userdata('Codigo');
    if ($usuario != $CI->config->item('usuDesarrollador')) {
        return true;
    } else {
        $CI->load->model('Permisos_model');
        $listaPermisosUsu = $CI->Permisos_model->validarPermisosXUsuario($idPermiso, $usuario);
        if ($listaPermisosUsu == FALSE) {
            return false;
        } else {
            if ($listaPermisosUsu[0]["Habilitado"] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }
}

function validarPermisoMenu($idPermiso) {
    $CI = get_instance();
    $usuario = $CI->session->userdata('Codigo');
    if ($usuario != $CI->config->item('usuDesarrollador')) {
        return true;
    } else {
        $CI->load->model('Permisos_model');
        $listaPermisosUsu = $CI->Permisos_model->validarPermisosXUsuario($idPermiso, $usuario);
        if ($listaPermisosUsu == FALSE) {
            return false;
        } else {
            if ($listaPermisosUsu[0]["Habilitado"] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }
}

?>