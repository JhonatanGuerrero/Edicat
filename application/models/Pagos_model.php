<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pagos_model extends CI_Model
{
    public function obtenerPagosCod($codigo)
    {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pagos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosCliente($cliente)
    {
        $this->db->where('Cliente', $cliente);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pagos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosPedido($pedido)
    {
        $this->db->where('Pedido', $pedido);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pagos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosCampos($cod, $select)
    {
        $this->db->select($select);
        $this->db->where('Codigo', $cod);
        $query = $this->db->get("Pagos");
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosProgramaCod($codigo)
    {
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("PagosProgramados");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosProgramadosCampos($cod, $select)
    {
        $this->db->select($select);
        $this->db->where('Codigo', $cod);
        $query = $this->db->get("PagosProgramados");
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function obtenerPagosProgramadosCamposAll($select = '*')
    {
        $this->db->select($select);
        $query = $this->db->get("PagosProgramados");
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    
    public function obtenerPagosProgramadosCamposIniFin($select = '*', $fechaI, $fechaF, $estado)
    {
        $this->db->select($select);
        $this->db->where('FechaProgramada >=', $fechaI);
        $this->db->where('FechaProgramada <=', $fechaF);
        $this->db->where('Estado', $estado);
        $query = $this->db->get("PagosProgramados");
        //echo $this->db->last_query()."<br><br>";
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosProgramaFechaUser($user, $fechaI, $fechaF)
    {
        $this->db->select('p.*, est.Nombre as NomEstado, ped.Cliente as Cliente, ped.Valor as Valor, ped.Saldo as Saldo, ped.Estado as estped, ped.PaginaFisica as Pagina');
        $this->db->from('PagosProgramados as p');
        $this->db->join('Estados as est', 'est.Codigo = p.Estado');
        $this->db->join('Pedidos as ped', 'ped.Codigo = p.Pedido');
        $this->db->where('p.FechaProgramada >=', $fechaI);
        $this->db->where('p.FechaProgramada <=', $fechaF);
        if ($user != "*") {
            $this->db->where('p.UsuarioCreacion', $user);
        }
        $this->db->where('p.Estado', 116); //Programado
        $this->db->where('p.Habilitado', '1');
        $query = $this->db->get();
        //echo $this->db->last_query();

        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosFechaUser($user, $fechaI, $fechaF)
    {
        $this->db->select('p.*, ped.Cliente as Cliente, ped.Valor as Valor, ped.Saldo as Saldo, ped.Estado as estped');
        $this->db->from('Pagos as p');
        $this->db->join('Pedidos as ped', 'ped.Codigo = p.Pedido');
        $this->db->where('p.FechaPago >=', $fechaI);
        $this->db->where('p.FechaPago <=', $fechaF);
        if ($user != "*") {
            $this->db->where('p.UsuarioCreacion', $user);
        }
        $this->db->where('p.Habilitado', '1');
        $query = $this->db->get();
        //echo $this->db->last_query();

        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosPorPedido($pedido)
    {
        $this->db->select('COUNT(*) as Cuotas');
        $this->db->from('Pagos as p');
        $this->db->where('p.Pedido', $pedido);
        $this->db->where('p.Habilitado', '1');

        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosProgramadosPorPedido($pedido, $fechaI = null, $fechaF = null)
    {
        $this->db->select('COUNT(*) as Cuotas');
        $this->db->from('PagosProgramados as p');
        $this->db->where('p.Pedido', $pedido);
        $this->db->where('p.Habilitado', '1');
        if ($fechaI != null) {
            $this->db->where('FechaProgramada >=', $fechaI);
        }
        if ($fechaF != null) {
            $this->db->where('FechaProgramada <=', $fechaF);
        }

        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function obtenerPagosPorPedidoxFechas($pedido, $fechaI = null, $fechaF = null)
    {
        $this->db->select('COUNT(*) as Cuotas');
        $this->db->from('Pagos as p');
        $this->db->where('p.Pedido', $pedido);
        $this->db->where('p.Habilitado', '1');
        
        if ($fechaI != null) {
            $this->db->where('FechaPago >=', $fechaI);
        }
        if ($fechaF != null) {
            $this->db->where('FechaPago <=', $fechaF);
        }

        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    

    public function ultimaCuota($pedido)
    {
        $this->db->select('Cuota');
        $this->db->from('Pagos as p');
        $this->db->where('p.Pedido', $pedido);
        $this->db->where('p.Habilitado', '1');
        $this->db->order_by("p.Cuota DESC");
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosProgramaPedido($pedido)
    {
        $this->db->select('p.*, est.Nombre as NomEstado');
        $this->db->from('PagosProgramados as p');
        $this->db->join('Estados as est', 'est.Codigo = p.Estado');
        $this->db->where('p.Pedido', $pedido);
        $this->db->where('p.Habilitado', '1');
        $this->db->order_by("p.FechaProgramada desc, p.FechaCreacion desc");

        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }
    
    public function obtenerPagosProgramaPedidoLast($pedido)
    {
        $this->db->select('p.*, est.Nombre as NomEstado');
        $this->db->from('PagosProgramados as p');
        $this->db->join('Estados as est', 'est.Codigo = p.Estado');
        $this->db->where('p.Pedido', $pedido);
        $this->db->where('p.Estado', 116);
        $this->db->where('p.Habilitado', '1');
        $this->db->order_by("p.FechaProgramada desc, p.FechaCreacion desc");
        //echo $this->db->last_query();
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosProgramaPedidoProg($pedido, $pagoProg)
    {
        $this->db->select('p.*, est.Nombre as NomEstado, ped.Cliente as Cliente, ped.Valor as Valor, ped.Saldo as Saldo, ped.Estado as estped');
        $this->db->from('PagosProgramados as p');
        $this->db->join('Estados as est', 'est.Codigo = p.Estado');
        $this->db->join('Pedidos as ped', 'ped.Codigo = p.Pedido');
        $this->db->where('p.Codigo', $pagoProg);
        $this->db->where('p.Pedido', $pedido);
        $this->db->where('p.Habilitado', '1');
        $this->db->order_by("p.FechaProgramada desc, p.FechaCreacion desc");

        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosProgramaPedidoPagoUserFec($pedido, $fechaPro, $user, $fecha)
    {
        $this->db->where('Pedido', $pedido);
        $this->db->where('FechaProgramada', $fechaPro);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("PagosProgramados");
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosProgramaPedidoActivos($pedido)
    {
        $this->db->select('COUNT(Codigo) as PagosActivos');
        $this->db->where('Pedido', $pedido);
        $this->db->where('Estado', 116);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("PagosProgramados");
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosPedidoUserFec($cliente, $pedido, $user, $fecha)
    {
        $this->db->where('Cliente', $cliente);
        $this->db->where('Pedido', $pedido);
        $this->db->where('UsuarioCreacion', $user);
        $this->db->where('FechaCreacion', $fecha);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pagos");
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerPagosPedidoActivos($pedido)
    {
        $this->db->select('COUNT(Codigo) as PagosActivos');
        $this->db->where('Pedido', $pedido);
        //$this->db->where('Estado', 116);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get("Pagos");
        //echo $this->db->last_query();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function obtenerHistorialPagosPedido($pedido)
    {
        $this->db->where('Pedido', $pedido);
        $this->db->order_by("FechaHistorial asc");
        $query = $this->db->get("HistorialPagos");
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function numCopias($codigo)
    {
        $this->db->select('Copias, FechaImpresion');
        $this->db->from('PagosProgramados');
        $this->db->where('Codigo', $codigo);
        $this->db->where('Habilitado', '1');
        $query = $this->db->get();
        if ($query->num_rows() <= 0) {
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function save($data)
    {
        if ($this->db->insert("Pagos", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function saveProg($data)
    {
        if ($this->db->insert("PagosProgramados", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function saveHistoria($data)
    {
        if ($this->db->insert("HistorialPagos", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function update($codigo, $data)
    {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("Pagos", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function updateProg($codigo, $data)
    {
        $this->db->where("Codigo", $codigo);
        if ($this->db->update("PagosProgramados", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function quitarPagosProgramaPendientePedido($pedido)
    {
        $data = array(
            "Estado" => 116
        );
        $this->db->where('Pedido', $pedido);
        $this->db->where('Estado !=', 116);
        $this->db->where('Habilitado', '1');
        if ($this->db->update("PagosProgramados", $data)) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    public function quitarllamadas($cliente, $pedido)
    {
        $this->db->where("Cliente", $cliente);
        $this->db->where("Pedido", $pedido);
        if ($this->db->delete('DevolucionLlamadas')) {
            return $error = $this->db->error();
        } else {
            return 1;
        }
    }

    //Conteo
    //Todos Los Pagos
    public function AllPay()
    {
        $this->db->select('Count(*) as Num');
        $query = $this->db->get('Pagos');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Todos los Pagos Programados
    public function AllPayProg()
    {
        $this->db->select('Count(*) as Num');
        $query = $this->db->get('PagosProgramados');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Todos los pagos Descartados
    public function AllPayProgDesc()
    {
        $this->db->select('Count(*) as Num');
        $this->db->where('Estado', 122); //Descartado
        $query = $this->db->get('PagosProgramados');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Todos los pagos Descartados
    public function AllPayProgNoPago()
    {
        $this->db->select('Count(*) as Num');
        $this->db->where("(Estado = '116' OR Estado = '118')"); //Programado o No Pagado
        $query = $this->db->get('PagosProgramados');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Pagos Realizados
    public function PayOk($fechaI, $fechaF)
    {
        $this->db->select('Count(*) as Num');
        $this->db->where('FechaPago >=', $fechaI);
        $this->db->where('FechaPago <=', $fechaF);
        $query = $this->db->get('Pagos');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Pagos Programados
    public function PayProg($fechaI, $fechaF)
    {
        $this->db->select('Count(*) as Num');
        $this->db->where('FechaProgramada >=', $fechaI);
        $this->db->where('FechaProgramada <=', $fechaF);
        $query = $this->db->get('PagosProgramados');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Pagos Descartados
    public function PayProgDesc($fechaI, $fechaF)
    {
        $this->db->select('Count(*) as Num');
        $this->db->where('Estado', 122); //Descartado
        $this->db->where('FechaProgramada >=', $fechaI);
        $this->db->where('FechaProgramada <=', $fechaF);
        $query = $this->db->get('PagosProgramados');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }

    //Pagos Descartados
    public function PayProgNoPago($fechaI, $fechaF)
    {
        $this->db->select('Count(*) as Num');
        $this->db->where("(Estado = '116' OR Estado = '118')"); //Programado o No Pagado
        $this->db->where('FechaProgramada >=', $fechaI);
        $this->db->where('FechaProgramada <=', $fechaF);
        $query = $this->db->get('PagosProgramados');
        if ($query->num_rows() <= 0) {
            return 0;
        } else {
            return $query->result_array();
        }
    }
}