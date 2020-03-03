<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cobradores_model extends CI_Model {

    public function obtenerCobradores() {
        $this->db->where('Estado', '119');
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Cobradores");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerCobrador($cod) {
        $this->db->where('Codigo', $cod);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Cobradores");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerMotivosLlamadas() {
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("MotivosLlamadas");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerMotivosLlamadasCod($codigo) {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("MotivosLlamadas");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerLlamada($codigo) {
        $this->db->where('Codigo', $codigo);
        $query = $this->db->get("Llamadas");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerLlamadasPedidoFecha($pedido, $cliente, $fecha) {
        $this->db->select('ll.*, m.color, m.Nombre as nombreMotivo, m.Codigo as codMotivo');
        $this->db->from('Llamadas as ll');
        $this->db->join('MotivosLlamadas as m', 'll.Motivo = m.Codigo');
        $this->db->where('Pedido', $pedido);
        $this->db->where('Cliente', $cliente);
        $this->db->where('Fecha', $fecha);
        $this->db->order_by('FechaCreacion', 'ASC');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerLlamadasPedidoFechas($pedido, $cliente, $fecha1, $fecha2) {
        $this->db->select('ll.*, m.color, m.Nombre as nombreMotivo, m.Codigo as codMotivo');
        $this->db->from('Llamadas as ll');
        $this->db->join('MotivosLlamadas as m', 'll.Motivo = m.Codigo');
        $this->db->where('Pedido', $pedido);
        $this->db->where('Cliente', $cliente);
        $this->db->where('Fecha >=', $fecha1);
        $this->db->where('Fecha <=', $fecha2);
        $this->db->order_by('FechaCreacion', 'ASC');
        $query = $this->db->get();
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }


    public function obtenerDevolucionLlamadasPedidoFecha($pedido, $cliente, $fecha) {
        $this->db->select('ll.*, m.color, m.Nombre as nombreMotivo, m.Codigo as codMotivo');
        $this->db->from('DevolucionLlamadas as ll');
        $this->db->join('MotivosLlamadas as m', 'll.Motivo = m.Codigo');
        $this->db->where('Pedido', $pedido);
        $this->db->where('Cliente', $cliente);
        $this->db->where('Fecha', $fecha);
        $this->db->order_by('FechaCreacion', 'ASC');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerDevolucionLlamadasPedidoFechaCre($pedido, $cliente, $fecha) {
        $this->db->select('ll.*, m.color, m.Nombre as nombreMotivo, m.Codigo as codMotivo');
        $this->db->from('DevolucionLlamadas as ll');
        $this->db->join('MotivosLlamadas as m', 'll.Motivo = m.Codigo');
        $this->db->where('Pedido', $pedido);
        $this->db->where('Cliente', $cliente);
        $this->db->where('ll.FechaCreacion >=', $fecha . " 00:00:00");
        $this->db->where('ll.FechaCreacion <=', $fecha . " 23:59:59");
        $this->db->order_by('ll.FechaCreacion', 'ASC');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerLlamadasPedidoFechaPro($pedido, $cliente, $fecha) {
        $this->db->select('ll.*, m.color, m.Nombre as nombreMotivo, m.Codigo as codMotivo');
        $this->db->from('Llamadas as ll');
        $this->db->join('MotivosLlamadas as m', 'll.Motivo = m.Codigo');
        $this->db->where('Pedido', $pedido);
        $this->db->where('Cliente', $cliente);
        $this->db->where('Fecha', $fecha);
        $this->db->order_by('FechaCreacion', 'DESC');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerDevolucionLlamadasPedidoFechaPro($pedido, $cliente, $fecha) {
        $this->db->select('ll.*, m.color, m.Nombre as nombreMotivo, m.Codigo as codMotivo');
        $this->db->from('DevolucionLlamadas as ll');
        $this->db->join('MotivosLlamadas as m', 'll.Motivo = m.Codigo');
        $this->db->where('Pedido', $pedido);
        $this->db->where('Cliente', $cliente);
        $this->db->where('Fecha', $fecha);
        $this->db->order_by('FechaCreacion', 'DESC');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerLlamadasMotivoFechaPro($fecha) {
        $this->db->select('ll.*, m.color, m.Nombre as nombreMotivo');
        $this->db->from('Llamadas as ll');
        $this->db->join('MotivosLlamadas as m', 'll.Motivo = m.Codigo');
        $this->db->where('Fecha', $fecha);
        $this->db->order_by('FechaCreacion', 'DESC');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerDevolucionLlamadasMotivoFechaPro($fechaI, $fechaF) {
        $this->db->select('ll.*, m.color, m.Nombre as nombreMotivo');
        $this->db->from('DevolucionLlamadas as ll');
        $this->db->join('MotivosLlamadas as m', 'll.Motivo = m.Codigo');
        $this->db->where('Fecha >=', $fechaI);
        $this->db->where('Fecha <=', $fechaF);
        
        $this->db->order_by('FechaCreacion', 'DESC');
        $query = $this->db->get();
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function saveLlamada($data) {
        if ($this->db->insert("Llamadas", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function saveDevolucionLlamada($data) {
        if (!$this->db->insert("DevolucionLlamadas", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    } 

    public function updateLlamada($codigo, $data) {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("Llamadas", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function updateDevolucionLlamada($codigo, $data) {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("DevolucionLlamadas", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

}

?>