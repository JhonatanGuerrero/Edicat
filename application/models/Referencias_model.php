<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referencias_model extends CI_Model {

    public function obtenerReferencias() {
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Referencias");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerReferencia($codigo) {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Referencias");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerReferenciasCodUserFec($Nombres, $user, $fecha) {
        $this->db->where('Nombres', $Nombres);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Referencias");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function obtenerReferenciasCodUserFecLast($Nombres, $user, $fecha) {
        $this->db->where('Nombres', $Nombres);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);
        $this->db->where('Habilitado', '1');
        $this->db->order_by("Codigo", "DESC");
        $query = $this->db->get("Referencias");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerRefClienteCodUserFec($cliente, $Referencia, $user, $fecha) {
        $this->db->where('Cliente', $cliente);
        $this->db->where('Referencia', $Referencia);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("ReferenciasCliente");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function obtenerRefClienteCodUserFecLast($cliente, $Referencia, $user, $fecha) {
        $this->db->where('Cliente', $cliente);
        $this->db->where('Referencia', $Referencia);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);
        $this->db->where('Habilitado', '1');
        $this->db->order_by("Codigo", "DESC");
        $query = $this->db->get("ReferenciasCliente");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerRefClienteData($cliente) {
        $this->db->where('Cliente', $cliente);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("ReferenciasCliente");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            $ref = $query->result_array();
            $referencia = array();
            $i = 0;
            foreach ($ref as $item) {
                $i++;
                $referencia[$i] = $this->obtenerReferencia($item["Codigo"]);
            }
            return $referencia;
        }
    }

    public function save($data) {
        if ($this->db->insert("Referencias", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function saveRefCli($data) {
        if ($this->db->insert("ReferenciasCliente", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function update($codigo, $data) {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("Referencias", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

}

?>