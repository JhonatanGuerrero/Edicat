<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerUsuario($usuario) {
        $this->db->where('Usuario', $usuario);
        $query = $this->db->get("Usuarios");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->row();
        }
    }

    public function obtenerEstado($estado) {
        $this->db->where('Codigo', $estado);
        $query = $this->db->get("Estados");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->row();
        }
    }

    public function obtenerPerfil($perfil) {
        $this->db->where('Codigo', $perfil);
        $query = $this->db->get("Perfiles");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->row();
        }
    }

}
