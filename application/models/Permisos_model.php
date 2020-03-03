<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Permisos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerPermisos() {
        $this->db->select('p.*, t.Nombre as TipoPermiso');
        $this->db->from('Permisos as p');
        $this->db->join('TiposPermisos as t', 'p.Tipo = t.Codigo');
        $this->db->where('p.Habilitado', 1);
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerNombrePermiso($codigo) {
        $this->db->select('Nombre');
        $this->db->from('Permisos as p');
        $this->db->where('p.Codigo', $codigo);
        $this->db->where('p.Habilitado', 1);
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPermisosXControl($controlador = null, $tipo = null) {
        $this->db->select('p.*, t.Nombre as TipoPermiso');
        $this->db->from('Permisos as p');
        $this->db->join('TiposPermisos as t', 'p.Tipo = t.Codigo');
        if ($controlador != null) {
            $this->db->where('p.Controlador', $controlador);
        }
        if ($tipo != null) {
            $this->db->where('p.Tipo', $tipo);
        }
        $this->db->where('p.Habilitado', 1);
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPermisosXUsuario($usuario) {
        $this->db->select('p.*, u.*, t.Nombre as TipoPermiso');
        $this->db->from('Permisos as p');
        $this->db->join('PermisosUsuarios as u', 'p.Codigo = u.Permiso');
        $this->db->join('TiposPermisos as t', 'p.Tipo = t.Codigo');
        $this->db->where('u.Usuario', $usuario);
        $this->db->where('u.Habilitado', 1);
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function validarPermisosXUsuario($permiso, $usuario) {
        $this->db->select('p.*, u.*, t.Nombre as TipoPermiso');
        $this->db->from('Permisos as p');
        $this->db->join('PermisosUsuarios as u', 'p.Codigo = u.Permiso');
        $this->db->join('TiposPermisos as t', 'p.Tipo = t.Codigo');
        $this->db->where('p.Codigo', $permiso);
        $this->db->where('u.Usuario', $usuario);
        //$this->db->where('u.Habilitado', 1);
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerTiposPermisos() {
        $this->db->where('Habilitado', 1);
        $query = $this->db->get("TiposPermisos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function save($data) {
        if ($this->db->insert("Permisos", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function savePermisosUsuarios($data) {
        if ($this->db->insert("PermisosUsuarios", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function update($codigo, $data) {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("Permisos", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function updatePermisosUsuarios($codigo, $data) {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("PermisosUsuarios", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

}
