<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_model extends CI_Model {

    public function obtenerClientes() {
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

    public function obtenerCliente($cod) {
        $this->db->where('Codigo', $cod);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Clientes");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerClienteDoc($documento, $order = null) {
        $this->db->where('Documento', $documento);
        if ($order = 'DESC') {
            $this->db->order_by("Codigo", "DESC");
        }
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Clientes");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function searchCliente($nombre, $cedula, $direccion, $telefono, $estado, $ubicacion) {
        $where = "c.Habilitado = 1 AND c.Nombre LIKE '%" . $nombre . "%' AND c.Documento LIKE '%" . $cedula . "%' AND (d.Direccion LIKE '%" . $direccion . "%' OR d.Etapa LIKE '%" . $direccion . "%' " .
                "OR d.Torre LIKE '%" . $direccion . "%' OR d.Apartamento LIKE '%" . $direccion . "%' OR d.Manzana LIKE '%" . $direccion . "%' OR d.Casa LIKE '%" . $direccion . "%' " .
                "OR d.Barrio LIKE '%" . $direccion . "%') AND (c.Telefono1 LIKE '%" . $telefono . "%' OR c.Telefono2 LIKE '%" . $telefono . "%' " .
                "OR c.Telefono3 LIKE '%" . $telefono . "%') AND c.estado  LIKE '%" . $estado . "%' AND p.PaginaFisica LIKE '%" . $ubicacion . "%'";

        $this->db->select('c.*, e.Nombre as EstNombre, d.Direccion as Dir, d.Etapa, d.Torre, d.Apartamento, d.Manzana, d.Interior, d.Casa, d.Barrio, p.Codigo as Pedido, p.*');
        $this->db->from('Clientes as c');
        $this->db->join('Pedidos as p', 'p.Cliente = c.Codigo');
        $this->db->join('Estados as e', 'c.Estado = e.Codigo');
        $this->db->join('Direcciones as d', 'c.Direccion = d.Codigo');
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function searchClienteAsignado($nombre, $estado, $usuario){
        $where = "c.Habilitado = 1 AND c.Nombre LIKE '%" . $nombre . "%' AND c.estado LIKE '%" . $estado . "%'";
        if ($usuario != "") {
            $where = "c.Habilitado = 1 AND c.Nombre LIKE '%" . $nombre . "%' AND c.estado LIKE '%" . $estado . "%' AND u.Codigo = '" . $usuario . "'";
        } 

        $this->db->select('c.*, e.Nombre as EstNombre, d.Direccion as Dir, d.Etapa, d.Torre, d.Apartamento, d.Manzana, d.Interior, d.Casa, d.Barrio, p.Codigo as Pedido, p.*, u.Usuario as UsuAsign, u.Nombre as NombreUsuAsign');
        $this->db->from('Clientes as c');
        $this->db->join('Pedidos as p', 'p.Cliente = c.Codigo');
        $this->db->join('Estados as e', 'c.Estado = e.Codigo');
        $this->db->join('ClientesUsuarios as clu', 'c.Codigo = clu.Cliente');
        $this->db->join('Usuarios as u', 'u.Codigo = clu.Usuario');
        $this->db->join('Direcciones as d', 'c.Direccion = d.Codigo');
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerClienteDocLast($documento) {
        $this->db->where('Documento', $documento);
        $this->db->where('Habilitado', '1');
        $this->db->order_by("Codigo", "DESC");
        $query = $this->db->get("Clientes");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerClientesNR() {
        $this->db->select('c.*, td.Nombre as TipDocNombre, e.Nombre as EstNombre, d.Direccion, d.Etapa, d.Torre, d.Apartamento, d.Manzana, d.Interior, d.Casa, d.Barrio');
        $this->db->from('Clientes as c');
        $this->db->join('TiposDocumentos as td', 'c.TipoDocumento = td.Codigo');
        $this->db->join('Estados as e', 'c.Estado = e.Codigo');
        $this->db->join('Direcciones as d', 'c.Direccion = d.Codigo');
        //$this->db->join('ReferenciasCliente as rc', 'c.Referencias = rc.Cliente');
        //$this->db->join('Referencias as r', 'rc.Referencia = r.Codigo');        
        $this->db->where('c.Estado', $this->config->item('cli_dia'));
        $this->db->where('c.Habilitado', '1');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerClientesNRDebe() {
        $this->db->select('c.*, td.Nombre as TipDocNombre, e.Nombre as EstNombre, d.Direccion, d.Etapa, d.Torre, d.Apartamento, d.Manzana, d.Interior, d.Casa, d.Barrio');
        $this->db->from('Clientes as c');
        $this->db->join('TiposDocumentos as td', 'c.TipoDocumento = td.Codigo');
        $this->db->join('Estados as e', 'c.Estado = e.Codigo');
        $this->db->join('Direcciones as d', 'c.Direccion = d.Codigo');
        //$this->db->join('ReferenciasCliente as rc', 'c.Referencias = rc.Cliente');
        //$this->db->join('Referencias as r', 'rc.Referencia = r.Codigo');        
        $this->db->or_where('c.Estado', $this->config->item('cli_debe'));
        $this->db->or_where('c.Estado', $this->config->item('cli_mor'));
        $this->db->where('c.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerClientesNRAll() {
        $this->db->select('c.*, td.Nombre as TipDocNombre, e.Nombre as EstNombre, d.Direccion, d.Etapa, d.Torre, d.Apartamento, d.Manzana, d.Interior, d.Casa, d.Barrio');
        $this->db->from('Clientes as c');
        $this->db->join('TiposDocumentos as td', 'c.TipoDocumento = td.Codigo');
        $this->db->join('Estados as e', 'c.Estado = e.Codigo');
        $this->db->join('Direcciones as d', 'c.Direccion = d.Codigo');
        //$this->db->join('ReferenciasCliente as rc', 'c.Referencias = rc.Cliente');
        //$this->db->join('Referencias as r', 'rc.Referencia = r.Codigo');
        $this->db->where('c.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerClienteDir($cliente) {
        $this->db->select('c.*, td.Nombre as TipDocNombre, e.Nombre as EstNombre, d.Codigo as Coddir, d.Direccion as Dir, d.Etapa, d.Torre, d.Apartamento, d.Manzana, d.Interior, d.Casa, d.Barrio as Barrio, d.TipoVivienda');
        $this->db->from('Clientes as c');
        $this->db->join('TiposDocumentos as td', 'c.TipoDocumento = td.Codigo');
        $this->db->join('Estados as e', 'c.Estado = e.Codigo');
        $this->db->join('Direcciones as d', 'c.Direccion = d.Codigo');
        $this->db->where('c.Codigo', $cliente);
        $this->db->where('c.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerClientesDir() {
        $this->db->select('c.*, td.Nombre as TipDocNombre, e.Nombre as EstNombre, d.Direccion as Dir, d.Etapa, d.Torre, d.Apartamento, d.Manzana, d.Interior, d.Casa, d.Barrio');
        $this->db->from('Clientes as c');
        $this->db->join('TiposDocumentos as td', 'c.TipoDocumento = td.Codigo');
        $this->db->join('Estados as e', 'c.Estado = e.Codigo');
        $this->db->join('Direcciones as d', 'c.Direccion = d.Codigo');
        $this->db->where('c.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerNomClientesDir($EstExcep1 = 0, $EstExcep2 = 0, $EstExcep3 = 0) {
        $this->db->select('c.Codigo, c.Nombre, c.Telefono1, c.Telefono2, c.Telefono3, td.Nombre as TipDocNombre, e.Nombre as EstNombre, d.Direccion as Dir, d.Etapa, d.Torre, d.Apartamento, d.Manzana, d.Interior, d.Casa, d.Barrio');
        $this->db->from('Clientes as c');
        $this->db->join('TiposDocumentos as td', 'c.TipoDocumento = td.Codigo');
        $this->db->join('Estados as e', 'c.Estado = e.Codigo');
        $this->db->join('Direcciones as d', 'c.Direccion = d.Codigo');
        if ($EstExcep1 != 0) {
            $this->db->where('c.Estado !=', $EstExcep1);
        }
        if ($EstExcep2 != 0) {
            $this->db->where('c.Estado !=', $EstExcep2);
        }
        if ($EstExcep3 != 0) {
            $this->db->where('c.Estado !=', $EstExcep3);
        }
        $this->db->where('c.Habilitado', '1');
        $query = $this->db->get();
        //echo $this->db->last_query() . "<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerClientesUsuario($cod) {
        $this->db->where('Codigo', $cod);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("ClientesUsuarios");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function confirmarClientesDelUsuario($usuario, $cliente) {
        $this->db->where('Usuario', $usuario);
        $this->db->where('Cliente', $cliente);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("ClientesUsuarios");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function save($data) {
        if ($this->db->insert("Clientes", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function saveCliUsu($data) {
        if ($this->db->insert("ClientesUsuarios", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function update($codigo, $data) {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("Clientes", $data)) {
            $error = $this->db->error();
            if ($error["code"] == 0) {
                return 1;
            } else {
                return $error["code"];
            }
        } else {
            return 1;
        }
    }

    public function LogCliente($cliente) {
        $this->db->like('Modulo', 'Cliente');
        $this->db->where('Llave', $cliente);
        $query = $this->db->get("Log");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    //Conteo
    //Registrados
    public function AllClients() {
        $this->db->select('Count(*) as Num');
        $query = $this->db->get('Clientes');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Eliminados
    public function ClientsDelete() {
        $this->db->select('Count(*) as Num');
        $this->db->where('Habilitado', 0);
        $query = $this->db->get('Clientes');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Al día
    public function ClientsOk() {
        $this->db->select('Count(*) as Num');
        $this->db->where('Estado', 104);  //Al día
        $query = $this->db->get('Clientes');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Deben
    public function ClientsDeb() {
        $this->db->select('Count(*) as Num');
        $this->db->where('Estado', 105);  //Deben
        $query = $this->db->get('Clientes');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //En mora
    public function ClientsMora() {
        $this->db->select('Count(*) as Num');
        $this->db->where('Estado', 124);  //En mora
        $query = $this->db->get('Clientes');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //DataCredito
    public function ClientsData() {
        $this->db->select('Count(*) as Num');
        $this->db->where('Estado', 115);  //DataCredito
        $query = $this->db->get('Clientes');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }
    
    //Devoluciones
    public function ClientsReturn() {
        $this->db->select('Count(*) as Num');
        $this->db->where('Estado', 106);  //Devolución
        $query = $this->db->get('Clientes');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Reportados
    public function ClientsReports() {
        $this->db->select('Count(*) as Num');
        $this->db->where('Estado', 126);  //Reportados
        $query = $this->db->get('Clientes');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Paz y Salvo
    public function ClientsPeace() {
        $this->db->select('Count(*) as Num');
        $this->db->where('Estado', 123);  //Paz y Salvo
        $query = $this->db->get('Clientes');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Nuevo
    public function ClientsNew($fechaI, $fechaF) {
        $this->db->select('Count(*) as Num');
        $this->db->where('FechaCreacion >=', $fechaI);
        $this->db->where('FechaCreacion <=', $fechaF);
        $query = $this->db->get('Clientes');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    public function ClienteUsuario($cliente, $usuario) {
        $this->db->where('Usuario', $usuario);
        $this->db->where('Cliente', $cliente);
        $this->db->where('Habilitado', 1);
        $query = $this->db->get('ClientesUsuarios');
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    public function ClienteUsuarioBool($cliente, $usuario) {
        $this->db->where('Usuario', $usuario);
        $this->db->where('Cliente', $cliente);
        $this->db->where('Habilitado', 1);
        $query = $this->db->get('ClientesUsuarios');
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return true;
        }
    }

    public function obtenerClientesAsignados() {
        $this->db->select('c.Codigo, c.Nombre, c.Telefono1, c.Telefono2, c.Telefono3, e.Nombre as EstNombre, d.Direccion as Dir, d.Etapa, d.Torre, d.Apartamento, d.Manzana, d.Interior, d.Casa, d.Barrio, cu.codigo as ccu, cu.usuario as ucu, cu.cliente as clcu, u.Nombre');
        //$this->db->select('c.Codigo, c.Nombre, cu.codigo as ccu, cu.usuario as ucu, cu.cliente as clcu, u.Nombre');
        $this->db->from('Clientes as c');
        $this->db->join('Estados as e', 'c.Estado = e.Codigo');
        $this->db->join('Direcciones as d', 'c.Direccion = d.Codigo');
        $this->db->join('ClientesUsuarios as cu', 'c.Codigo = cu.Cliente', 'left');
        $this->db->join('Usuarios as u', 'u.Codigo = cu.Usuario', 'left');
        $this->db->where('c.Estado !=', $this->config->item('cli_devol'));
        $this->db->where('c.Estado !=', $this->config->item('cli_paz'));
        $this->db->where('c.Habilitado', 1);
        $query = $this->db->get();
        //echo $this->db->last_query() . "<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
        //SELECT cu.*, c.* FROM Clientes as c LEFT JOIN ClientesUsuarios as cu ON c.Codigo = cu.Cliente
    }

}

?>