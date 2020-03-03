<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos_model extends CI_Model {

    public function obtenerPedidos() {
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pedidos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerProductosPedidosxPedido($pedido) {
        $this->db->where('Pedido', $pedido);
        $query = $this->db->get("ProductosPedidos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerProductosPedidosxPedPro($pedido, $producto) {
        $this->db->where('Pedido', $pedido);
        $this->db->where('Producto', $producto);
        $query = $this->db->get("ProductosPedidos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedidosActivos($pedido = null) {
        $this->db->select('p.*, c.Estado as EstCliente');
        $this->db->from('Pedidos as p');
        $this->db->join('Clientes as c', 'p.Cliente = c.Codigo');
        if ($pedido != null) {
            $this->db->where('p.Codigo', $pedido);
        }
        $this->db->where('p.Saldo >', 0);
        $this->db->where('p.Estado !=', $this->config->item('ped_devol')); //Devolución
        $this->db->where('p.Estado !=', $this->config->item('ped_paz')); //Paz y Salvo
        $this->db->where('p.Habilitado', '1');
        $query = $this->db->get();
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedidosDeben() {
        $this->db->where('Estado', '112'); //Deuda
        $this->db->where('Saldo >', 0);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pedidos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedidosDatacredito() {
        $this->db->where("(Estado = '125' OR Estado = '127')"); //Datacredito - Reportado
        $this->db->where('Saldo >', 0);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pedidos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedido($cod) {
        $this->db->where('Codigo', $cod);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pedidos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedidosCliente($cliente) {
        $this->db->select('p.*, e.Nombre as EstNombre, v.Nombre as NomVen, t.Cuotas as NumCuotas, t.ValorCuota as ValCuota');
        $this->db->from('Pedidos as p');
        $this->db->join('Tarifas as t', 'p.Tarifa = t.Codigo');
        $this->db->join('Vendedores as v', 'p.Vendedor = v.Codigo');
        $this->db->join('Estados as e', 'p.Estado = e.Codigo');
        $this->db->where('p.Cliente', $cliente);
        $this->db->where('p.Estado !=', $this->config->item('ped_devol'));
        $this->db->where('p.Estado !=', $this->config->item('ped_paz'));
        $this->db->where('p.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedidosClientePorPedido($pedido) {
        $this->db->select('p.*, e.Nombre as EstNombre, v.Nombre as NomVen, t.Cuotas as NumCuotas, t.ValorCuota as ValCuota');
        $this->db->from('Pedidos as p');
        $this->db->join('Tarifas as t', 'p.Tarifa = t.Codigo');
        $this->db->join('Vendedores as v', 'p.Vendedor = v.Codigo');
        $this->db->join('Estados as e', 'p.Estado = e.Codigo');
        $this->db->where('p.Codigo', $pedido);
        $this->db->where('p.Estado !=', $this->config->item('ped_devol'));
        $this->db->where('p.Estado !=', $this->config->item('ped_paz'));
        $this->db->where('p.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedidosClienteAll($cliente) {
        $this->db->select('p.*, e.Nombre as EstNombre, v.Nombre as NomVen, t.Cuotas as NumCuotas, t.ValorCuota as ValCuota');
        $this->db->from('Pedidos as p');
        $this->db->join('Tarifas as t', 'p.Tarifa = t.Codigo');
        $this->db->join('Vendedores as v', 'p.Vendedor = v.Codigo');
        $this->db->join('Estados as e', 'p.Estado = e.Codigo');
        $this->db->where('p.Cliente', $cliente);
        $this->db->where('p.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPaginaFisicaPorPedido($pedido) {
        $this->db->select('PaginaFisica');
        $this->db->where('pe.Codigo', $pedido);
        $query = $this->db->get("Pedidos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerClientePorPedido($pedido) {
        $this->db->select('pe.*, cl.*, d.*, d.Direccion as Dir');
        $this->db->from('Pedidos as pe');
        $this->db->join('Clientes as cl', 'cl.Codigo = pe.Cliente');
        $this->db->join('Direcciones as d', 'd.Codigo = cl.Direccion');
        $this->db->where('pe.Codigo', $pedido);
        $this->db->where('pe.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerProductosPedidoCliente($pedido) {
        $this->db->select('pe.Codigo as CodPedido, c.Nombre as NombreCliente, pe.*, e.Nombre as EstNombre, pe.Valor as Valor1, pp.*, pp.Valor as ValPP, t.*, productos.Codigo as CodPro, productos.nombre as NomPro, t.Nombre as NomTarifa, productos.Valor as ValTarifa, t.Descuento as Descuento, t.Cuotas as NumCuotas, t.ValorCuota as ValCuota');
        $this->db->from('Pedidos as pe');
        $this->db->join('ProductosPedidos as pp', 'pe.Codigo = pp.Pedido');
        $this->db->join('Productos as productos', 'productos.Codigo = pp.Producto');
        $this->db->join('Tarifas as t', 't.Codigo = pe.Tarifa');
        $this->db->join('Estados as e', 'pe.Estado = e.Codigo');
        $this->db->join('Clientes as c', 'pe.Cliente = c.Codigo');
        $this->db->where('pe.Codigo', $pedido);
        $this->db->where('pe.Estado !=', $this->config->item('ped_devol'));
        $this->db->where('pe.Estado !=', $this->config->item('ped_paz'));
        $this->db->where('pe.Habilitado', '1');
        $query = $this->db->get();
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerProductosPedidoClienteAll($pedido) {
        $this->db->select('pe.Codigo as CodPedido, pe.*, e.Nombre as EstNombre, pe.Valor as Valor1, pp.*, t.*, productos.nombre as NomPro, t.Nombre as NomTarifa, t.Descuento as Descuento, t.Cuotas as NumCuotas, t.ValorCuota as ValCuota');
        $this->db->from('Pedidos as pe');
        $this->db->join('ProductosPedidos as pp', 'pe.Codigo = pp.Pedido');
        $this->db->join('Productos as productos', 'productos.Codigo = pp.Producto');
        $this->db->join('Tarifas as t', 't.Codigo = pe.Tarifa');
        $this->db->join('Estados as e', 'pe.Estado = e.Codigo');
        $this->db->where('pe.Codigo', $pedido);
        $this->db->where('pe.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedidoPorCliente($cliente) {
        $this->db->where('Cliente', $cliente);
        $this->db->where('Saldo >=', 0);
        $this->db->where('Valor >=', 0);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pedidos");
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerSaldoValorPedidoCod($pedido) {
        $this->db->select('pe.Saldo as Saldo');
        $this->db->from('Pedidos as pe');
        $this->db->where('pe.Codigo', $pedido);
        $this->db->where('pe.Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedidoCliValUserFec($cliente, $DiaCobro, $user, $fecha) {
        $this->db->where('Cliente', $cliente);
        $this->db->where('DiaCobro', $DiaCobro);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pedidos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedidoCliValUserFecLast($cliente, $DiaCobro, $user, $fecha) {
        $this->db->where('Cliente', $cliente);
        $this->db->where('DiaCobro', $DiaCobro);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);
        $this->db->where('Habilitado', '1');
        $this->db->order_by("Codigo", "DESC");
        $query = $this->db->get("Pedidos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPedidoProUserFec($pedido, $producto, $user, $fecha) {
        $this->db->where('Pedido', $pedido);
        $this->db->where('Producto', $producto);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("ProductosPedidos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function save($data) {
        if ($this->db->insert("Pedidos", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function saveProPed($data) {
        if ($this->db->insert("ProductosPedidos", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function saveValDeuda($data) {
        if ($this->db->insert("ValidacionDeudas", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function update($codigo, $data) {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("Pedidos", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function updateProPedidoxPedido($pedido, $producto, $data) {
        $this->db->where("Pedido", $pedido);
        $this->db->where("Producto", $producto);
        if ($this->db->update("ProductosPedidos", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function updateValDeuda($codigo, $data) {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("ValidacionDeudas", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function LogPedido($pedido) {
        $this->db->like('Modulo', 'Pedido');
        $this->db->where('Llave', $pedido);
        $query = $this->db->get("Log");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function valPedido($pedido) {
        $this->db->where('Pedido', $pedido);
        $query = $this->db->get("ValidacionDeudas");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    //Contar Pedidos
    public function contarPedidos($fechaI, $fechaF) {
        $this->db->select('Count(*) as Num');
        $this->db->where('DiaCobro >=', $fechaI);
        $this->db->where('DiaCobro <=', $fechaF);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pedidos");
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

}

?>