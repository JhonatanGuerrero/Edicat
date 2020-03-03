<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

    public function obtenerUsuarios() {
        $this->db->where('Codigo !=', '100');
        $this->db->where('Estado', '101');
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Usuarios");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerUsuario($cod) {
        $this->db->where('Codigo', $cod);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Usuarios");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerAdmin() {
        $this->db->select('a.Codigo as Cod, a.*');
        $this->db->where('a.Habilitado', 1);
        $query = $this->db->get("Administradores as a");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerAdminPorUsuario($usuario) {
        $this->db->select('u.Usuario, u.Nombre, a.*');
        $this->db->from('Usuarios as u');
        $this->db->join('Administradores as a', 'u.Administrador = a.Codigo');
        $this->db->where('u.Usuario', $usuario);
        $this->db->where('u.Habilitado', 1);
        $this->db->where('a.Habilitado', 1);
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerUsuariosEP() {
        $this->db->select('u.Codigo, u.Usuario, u.Nombre, u.Perfil as PerfilId, p.Nombre as Perfil, u.Estado as EstadoId, e.Nombre as Estado');
        $this->db->from('Usuarios as u');
        $this->db->join('Perfiles as p', 'u.Perfil = p.Codigo');
        $this->db->join('Estados as e', 'u.Estado = e.Codigo');
        $this->db->where('u.Codigo !=', '100');
        //$this->db->where('u.Estado', '101');
        $this->db->where('u.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerDelUsuariosEP() {
        $this->db->select('u.Codigo, u.Usuario, u.Nombre, u.Perfil as PerfilId, p.Nombre as Perfil, u.Estado as EstadoId, e.Nombre as Estado');
        $this->db->from('Usuarios as u');
        $this->db->join('Perfiles as p', 'u.Perfil = p.Codigo');
        $this->db->join('Estados as e', 'u.Estado = e.Codigo');
        $this->db->where('u.Codigo !=', '100');
        $this->db->where('u.Habilitado', '0');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerUsuarioPorUser($usuario) {
        $this->db->where('Usuario', $usuario);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Usuarios");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerUsuarioPorUserEP($usuario) {
        $this->db->select('u.Codigo, u.Usuario, u.Nombre, u.Perfil as PerfilId, p.Nombre as Perfil, u.Estado as EstadoId, e.Nombre as Estado');
        $this->db->from('Usuarios as u');
        $this->db->join('Perfiles as p', 'u.Perfil = p.Codigo');
        $this->db->join('Estados as e', 'u.Estado = e.Codigo');
        $this->db->where('u.Usuario', $usuario);
        $this->db->where('u.Habilitado', '1');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerUsuarioPorCodEP($usuario) {
        $this->db->select('u.Codigo, u.Usuario, u.Nombre, u.Documento, u.TipoDocumento as TipoDocumentoId, td.Nombre as TipoDocumento, '
                . 'u.Perfil as PerfilId, p.Nombre as Perfil, u.Estado as EstadoId, e.Nombre as Estado, u.CambioPass, '
                . 'u.UsuarioCreacion, u.FechaCreacion, u.UsuarioModificacion, u.FechaModificacion');
        $this->db->from('Usuarios as u');
        $this->db->join('Perfiles as p', 'u.Perfil = p.Codigo');
        $this->db->join('Estados as e', 'u.Estado = e.Codigo');
        $this->db->join('TiposDocumentos as td', 'u.TipoDocumento = td.Codigo');
        $this->db->where('u.Codigo', $usuario);
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function save($data) {
        if ($this->db->insert("Usuarios", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function update($codigo, $data) {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("Usuarios", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

}
