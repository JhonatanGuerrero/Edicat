<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TiposDocumentos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerTiposDocumentos() {
        $this->db->where('Habilitado', 1);
        $query = $this->db->get("TiposDocumentos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerTiposDocumentoCod($codigo) {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', 1);
        $query = $this->db->get("TiposDocumentos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
}

?>