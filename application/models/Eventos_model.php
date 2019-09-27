<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos_model extends CI_Model {

    public function obtenerEventos() {
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Eventos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerEventosIGLBARR() {
        $this->db->distinct();
        $this->db->select('Iglesia, Barrio');
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Eventos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerEvento($codigo) {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Eventos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerEventoVendIglBarFec($vendedor, $iglesia, $barrio, $fecha) {
        $this->db->where('Vendedor', $vendedor);
        $this->db->where('Iglesia', $iglesia);
        $this->db->where('Barrio', $barrio);
        $this->db->where('Fecha', $fecha);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Eventos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function obtenerEventoVendIglBarFecLast($vendedor, $iglesia, $barrio, $fecha) {
        $this->db->where('Vendedor', $vendedor);
        $this->db->where('Iglesia', $iglesia);
        $this->db->where('Barrio', $barrio);
        $this->db->where('Fecha', $fecha);
        $this->db->where('Habilitado', '1');
        $this->db->order_by("Codigo", "DESC");
        $query = $this->db->get("Eventos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function save($data) {
        if ($this->db->insert("Eventos", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

}

?>