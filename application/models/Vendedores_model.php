<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedores_model extends CI_Model {

    public function obtenerVendedores() {
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Vendedores");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerVendedor($codigo) {
        $this->db->where('Codigo', $codigo);
        $query = $this->db->get("Vendedores");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerVendedorHab($codigo) {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Vendedores");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
}

?>