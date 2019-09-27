<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estados_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerEstados() {
        $this->db->where('Habilitado', 1);
        $query = $this->db->get("Estados");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function obtenerEstadosPor($tipo) {
        $this->db->where('TipoEstado', $tipo);
        $this->db->where('Habilitado', 1);
        $query = $this->db->get("Estados");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

}

?>