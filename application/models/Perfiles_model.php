<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Perfiles_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerPerfiles() {
        $this->db->where('Codigo !=', 100);
        $this->db->where('Habilitado', 1);
        $query = $this->db->get("Perfiles");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

}

?>