<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_model extends CI_Model {

    
    public function obtenerProductos() {
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Productos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function obtenerProductoCod($codigo) {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Productos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    
    public function obtenerConceptos() {
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Conceptos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerProducto($codigo) {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Productos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    
}

?>