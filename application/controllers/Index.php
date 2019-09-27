<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Index';
        $this->load->model('Pagos_model');
        $this->load->model('Clientes_model');
        $this->load->model('Pedidos_model');
        $this->load->model('Pagos_model');
        $this->load->model('Estados_model');
    }

    public function index() {
        $user = $this->session->userdata('Login');
        if (!isset($user)) {
            redirect(site_url("Login/index/"));
        } else {
//            $codPermiso = 1;
//            $nombrePermiso = 'Lista Usuarios';
//            $permisoPagina = validarPermisoPagina($codPermiso, $this->session->userdata('Codigo'), $nombrePermiso);

            $datosPagos = $this->obtenerListadosClientesCobro();
            if (isset($datosPagos) && $datosPagos != FALSE) {
                $data = new stdClass();
                $data->Controller = "Index";
                $data->title = "Inicio";
                $data->subtitle = "Bienvenido a su plataforma";
                $data->contenido = $this->viewControl . '/index';
                //$data->permisoPagina = $permisoPagina;

                $this->load->view('frontend', $data);
            } else {
                $this->session->set_flashdata("error", "Aun no hay Clientes/Pedidos creados.");
                redirect(base_url("/Clientes/Admin/"));
            }
        }
    }

    public function Acercade() {
        $user = $this->session->userdata('Login');
        if (!isset($user)) {
            redirect(site_url("Login/index/"));
        } else {
            $data = new stdClass();
            $data->Controller = "Index";
            $data->title = "Acerca de...";
            $data->contenido = $this->viewControl . '/acercade';

            $this->load->view('frontend', $data);
        }
    }

    public function obtenerListadosClientesCobro() {
        $dataClientes = $this->Clientes_model->obtenerNomClientesDir($this->config->item('cli_data'), $this->config->item('cli_devol'), $this->config->item('cli_deud'));
        if (isset($dataClientes) && $dataClientes != FALSE) {
            $d = array();
            $dataPagoPedido = array();
            $datosPagos = array();
            $dataPagosP = array();
            foreach ($dataClientes as $itemCliente) {
                $cliente = $itemCliente["Codigo"];
                $dataPedido = $this->Pedidos_model->obtenerPedidosClienteAll($cliente);
                if (isset($dataPedido) && $dataPedido != FALSE) {
                    foreach ($dataPedido as $item) {
                        $i = intval($item['Codigo']);

                        $dataPagos = $this->Pagos_model->obtenerPagosPedido($i);
                        if (isset($dataPagos) && $dataPagos != FALSE) {
                            $dataPagoPedido[$i] = $dataPagos;
                        } else {
                            $p1 = array(
                                "Codigo" => "0",
                                "Cliente" => $item["Cliente"],
                                "Pedido" => $item["Codigo"],
                                "Cuota" => $item["NumCuotas"],
                                "Pago" => "0",
                                "FechaPago" => "-",
                                "TotalPago" => $item["Valor"],
                                "Observaciones" => "",
                                "Habilitado" => 1,
                                "UsuarioCreacion" => "ADMIN",
                                "FechaCreacion" => "",
                                "UsuarioModificacion" => "",
                                "FechaModificacion" => ""
                            );
                            $p["0"] = $p1;
                            $dataPagoPedido[$i] = $p;
                        }
                    }

                    foreach ($dataPedido as $item1) {
                        $i = intval($item1['Codigo']);
                        if (array_key_exists($i, $dataPagoPedido)) {
                            $dataPagosP["Nombre"] = $itemCliente["Nombre"];
                            $dataPagosP["Cliente"] = $itemCliente["Codigo"];
                            $direccion = $itemCliente["Dir"];
                            $direccion = ($itemCliente["Etapa"] != "") ? $direccion . " ET " . $itemCliente["Etapa"] : $direccion;
                            $direccion = ($itemCliente["Torre"] != "") ? $direccion . " TO " . $itemCliente["Torre"] : $direccion;
                            $direccion = ($itemCliente["Apartamento"] != "") ? $direccion . " AP " . $itemCliente["Apartamento"] : $direccion;
                            $direccion = ($itemCliente["Manzana"] != "") ? $direccion . " MZ " . $itemCliente["Manzana"] : $direccion;
                            $direccion = ($itemCliente["Interior"] != "") ? $direccion . " IN " . $itemCliente["Interior"] : $direccion;
                            $direccion = ($itemCliente["Casa"] != "") ? $direccion . " CA " . $itemCliente["Casa"] : $direccion;
                            $dataPagosP["Direccion"] = $direccion;
                            $telefono = $itemCliente["Telefono1"];
                            $telefono = ($itemCliente["Telefono2"] != "") ? $telefono . " - " . $itemCliente["Telefono2"] : $telefono;
                            //$telefono = ($itemCliente["Telefono3"] != "") ? $telefono . " - " . $itemCliente["Telefono3"] : $telefono;
                            $dataPagosP["Telefono"] = $telefono;
                            $diaCobro = $item1["DiaCobro"];
                            $dataPagosP["Pedidos"] = $i;
                            $cuota = 0;
                            $abonado = 0;
                            $diaCobro = "2018-01-01 00:00:00";
                            $f2 = "2018-01-01 00:00:00";
                            $ultimoPago = 0;

                            if ($dataPagoPedido[$i][0]["Pago"] != 0) {
                                foreach ($dataPagoPedido[$i] as $item) {
                                    $cuota++;
                                    $abonado = $abonado + $item["Pago"];
                                    $f1 = $item["FechaPago"];
                                    if ($f1 > $f2) {
                                        $f2 = $f1;
                                    }
                                }
                                $dataPagosP["diacobro"] = date("d/m/Y", strtotime($diaCobro));
                                $dataPagosP["fechaUltimoPago"] = date("d/m/Y", strtotime($f2));
                                foreach ($dataPagoPedido[$i] as $item) {
                                    if ($item["FechaPago"] == $f2) {
                                        if ($item["Pago"] > $ultimoPago) {
                                            $ultimoPago = $item["Pago"];
                                        }
                                    }
                                }
                                $dataPagosP["UltimoPago"] = $ultimoPago;
                            } else {
                                $dataPagosP["diacobro"] = "-";
                                $dataPagosP["fechaUltimoPago"] = "-";
                                $dataPagosP["UltimoPago"] = "-";
                            }
                            $dataPagosP["cuota"] = $cuota;
                            $dataPagosP["abonado"] = $abonado;
                            $dataPagosP["valor"] = $item1["Valor"];
                            $dataPagosP["saldo"] = intval($item1["Valor"]) - intval($abonado);

                            $datosPagos[$i] = $dataPagosP;
                        }
                    }
                }
            }

            //print_r($datosPagos);
            return $datosPagos;
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos de Clientes.");
            return false;
        }
    }

}
