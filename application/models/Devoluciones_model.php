<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Devoluciones_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerDevoluciónCod($codigo) {
        $this->db->where('Codigo', $codigo);
        $query = $this->db->get("Devoluciones");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerDevolucionesFechaUser($user, $fechaI, $fechaF) {
        $this->db->select('d.*, cli.Nombre as NomCliente');
        $this->db->from('Devoluciones as d');
        $this->db->join('Clientes as cli', 'cli.Codigo = d.Cliente');
        $this->db->where('d.FechaCreacion >=', $fechaI);
        $this->db->where('d.FechaCreacion <=', $fechaF);
        if ($user != "*") {
            $this->db->where('d.UsuarioCreacion', $user);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();

        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerDevolución($pedido, $cliente, $user, $fecha) {
        $this->db->where('Pedido', $pedido);
        $this->db->where('Cliente', $cliente);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);
        $query = $this->db->get("Devoluciones");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function save($data) {
        if ($this->db->insert("Devoluciones", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

}

?>