<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Direcciones_model extends CI_Model {

    public function obtenerDirecciones() {
//        $this->db->where('Codigo !=', '100');
//        $this->db->where('Estado', '101');
//        $this->db->where('Habilitado', '1');
//        $query = $this->db->get("Usuarios");
//        if ($query->num_rows() <= 0) {
//            return false;
//        } else {
//            return $query->result_array();
//        }
    }
    
    public function obtenerDireccionPorCod($codigo) {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Direcciones");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function obtenerDireccionPorDirUserFec($direccion, $user, $fecha) {
        $this->db->where('Direccion', $direccion);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);        
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Direcciones");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function obtenerDireccionPorDirUserFecLast($direccion, $user, $fecha) {
        $this->db->where('Direccion', $direccion);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);        
        $this->db->where('Habilitado', '1');
        $this->db->order_by("Codigo", "DESC");
        $query = $this->db->get("Direcciones");
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    

    public function obtenerTiposVivienda() {
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("TiposViviendas");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
     public function save($data) {
        if ($this->db->insert("Direcciones", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }
    
    public function update($codigo, $data) {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("Direcciones", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

}

?>