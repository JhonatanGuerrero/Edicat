<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tarifas_model extends CI_Model {

    public function obtenerTarifas() {
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Tarifas");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerTarifa($codigo) {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Tarifas");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerTarifasProductos() {
        $this->db->select('t.*, p.Nombre as NomProducto');
        $this->db->from('Tarifas as t');
        $this->db->join('Productos as p', 'p.Codigo = t.Producto');
        $this->db->where('t.Habilitado', '1');
        $this->db->where('p.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerTarifaPorProducto($producto) {
        $this->db->where('Producto', $producto);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Tarifas");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

}

?>