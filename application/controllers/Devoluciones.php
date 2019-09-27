<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Devoluciones extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->viewControl = 'Devoluciones';
        $this->load->model('Devoluciones_model');
        $this->load->model('Pagos_model');
        $this->load->model('Clientes_model');
        $this->load->model('Pedidos_model');
        $this->load->model('Estados_model');
        $this->load->model('Cobradores_model');
        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]);
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        }
    }

    public function index()
    {
        redirect(site_url($this->viewControl . "/Admin/"));
    }

    public function Admin()
    {
        $data = new stdClass();
        $data->Controller = "Devoluciones";
        $data->title = "Listado de Devoluciones";
        $data->subtitle = "Listado de Devoluciones por Fecha";
        $data->contenido = $this->viewControl . '/Admin';

        $this->load->view('frontend', $data);
    }

    public function listadoDevoluviones()
    {
        $user = "*";
        $fechaIni = date('Y-m-d') . " 00:00:00";
        $fechaFin = date('Y-m-d') . " 23:59:59";
        $arreglo = $this->consultarDevolucion($user, $fechaIni, $fechaFin);

        echo json_encode($arreglo);
    }

    public function consultarDevolucion($user, $fechaIni, $fechaFin)
    {
        try {
            $dataDevolucion = $this->Devoluciones_model->obtenerDevolucionesFechaUser($user, $fechaIni, $fechaFin);
            $arreglo["data"] = [];

            if (isset($dataDevolucion) && $dataDevolucion != false) {
                $i = 0;
                foreach ($dataDevolucion as $item) {
                    $btn1 = "<a href='" . base_url() . "Clientes/Consultar/" . $item['Cliente'] . "/' title='Ver Información de Cliente'><i class='fa fa-search' aria-hidden='true' style='padding:5px;'></i></a>";
                    $btn2 = "<a href='" . base_url() . "Devoluciones/Consultar/" . $item['Codigo'] . "/' title='Ver Detalle Devolución'><i class='fa fa-truck' aria-hidden='true' style='padding:5px;'></i></a>";
                    $btn3 = "<a href='" . base_url() . "Pagos/Historial/" . $item['Pedido'] . "/' title='Historial de Pagos'><i class='fa fa-history' aria-hidden='true' style='padding:5px;'></i></a>";
                    $btn4 = "";

                    if (strlen($item["Observaciones"]) > 30) {
                        $osb = substr($item["Observaciones"], 0, 30) . " (...)";
                    } else {
                        $osb = $item["Observaciones"];
                    }

                    $arreglo["data"][$i] = array(
                        "pedido" => $item["Pedido"],
                        "cliente" => $item["NomCliente"],
                        "Saldo" => money_format("%.0n", $item["Saldo"]),
                        "fecha" => date("d/m/Y", strtotime($item["FechaCreacion"])),
                        "observacion" => $osb,
                        "btn" => '<div class="btn-group text-center" style="margin: 0px auto;  width:100%;">' . $btn1 . $btn2 . $btn3 . $btn4 . '</div>'
                    );
                    $i++;
                }
            }
            return $arreglo;
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage() . "<br>";
        }
    }

    public function FiltroDevol()
    {
        $user = "*"; //trim($this->input->post('pag_usu'));
        $fechaIni = trim($this->input->post('pag_fec1'));
        $date = str_replace('/', '-', $fechaIni);
        $fechaIni = date('Y-m-d', strtotime($date)) . " 00:00:00";
        $fechaFin = trim($this->input->post('pag_fec2'));
        $date = str_replace('/', '-', $fechaFin);
        $fechaFin = date("Y-m-d", strtotime($date)) . " 23:59:59";

        $arreglo = $this->consultarDevolucion($user, $fechaIni, $fechaFin);

        echo json_encode($arreglo);
    }

    public function Generar()
    {
        $idPermiso = 97;
        $accion = validarPermisoAcciones($idPermiso);
        if ($accion) {
            $pedido = trim($this->input->post('pedido'));
            $cliente = trim($this->input->post('cliente'));
            $nombre = trim($this->input->post('nombre'));
            $saldo = trim($this->input->post('saldo'));
            $cuotas = trim($this->input->post('cuotas'));
            $valor = trim($this->input->post('valor'));
            $cobrador = trim($this->input->post('cobrador'));
            $observaciones = trim($this->input->post('observaciones'));

            if ($pedido == "") {
                echo "El Pedido no existe. Actualice la página y vuelva a intentarlo.";
            } else {
                if ($cliente == "") {
                    echo "El Cliente no existe. Actualice la página y vuelva a intentarlo.";
                } else {
                    if ($saldo == "") {
                        echo "El Saldo del Pedido no es válido. Actualice la página y vuelva a intentarlo.";
                    } else {
                        if ($cuotas == "") {
                            echo "El número de Cuotas no es válido. Actualice la página y vuelva a intentarlo.";
                        } else {
                            $dataPedido = $this->Pedidos_model->obtenerPedido($pedido);
                            if ($dataPedido == false) {
                                echo "El Pedido no existe. Actualice la página y vuelva a intentarlo.";
                            } else {
                                $dataCliente = $this->Clientes_model->obtenerCliente($cliente);
                                if ($dataCliente == false) {
                                    echo "El Cliente no existe. Actualice la página y vuelva a intentarlo.";
                                } else {
                                    //Datos Auditoría
                                    $user = $this->session->userdata('Usuario');
                                    $fecha = date("Y-m-d H:i:s");

                                    $devolucion = array(
                                        "Pedido" => $pedido,
                                        "Cliente" => $cliente,
                                        "Saldo" => $saldo,
                                        "Cuota" => $cuotas,
                                        "ValorDevol" => $valor,
                                        "Cobrador" => $cobrador,
                                        "Observaciones" => $observaciones,
                                        "UsuarioCreacion" => $user,
                                        "FechaCreacion" => $fecha
                                    );

                                    try {
                                        if ($this->Devoluciones_model->save($devolucion)) {
                                            $dataDevoluciones = $this->Devoluciones_model->obtenerDevolución($pedido, $cliente, $user, $fecha);
                                            $codDevolucion = $dataDevoluciones [0]["Codigo"];
                                            $modulo = "Devolución Pedido";
                                            $accion = "Crear Devolución";
                                            $tabla = "Devoluciones";
                                            $llave = $codDevolucion;
                                            $cliente_log = $cliente;
                                            $enlace = "Clientes|Consultar|" . $llave;
                                            $dataInsert = $devolucion;
                                            $observaciones = "Se genera Devolución\nCliente: " . $nombre . "\n" . $observaciones;
                                            insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
 
                                            $obs = $dataPedido[0]["Observaciones"] . "\n---\nSe genera Devolución:\n" . $observaciones;

                                            $dataActPedido = array(
                                                "Estado" => 113, //Devoluciones
                                                //"DiaCobro" => NULL,
                                                // "Saldo" => 0,
                                                "Observaciones" => $obs,
                                                "UsuarioCreacion" => $user,
                                                "FechaCreacion" => $fecha
                                            );
                                            
                                            $dataOriginal = $dataPedido[0];
                                            $dataNew = compararCambiosLog($dataOriginal, $dataActPedido);
                                            $this->Pedidos_model->update($pedido, $dataNew);
                                            $modulo = "Devolución Pedido";
                                            $accion = "Actualizar Pedido";
                                            $tabla = "Pedidos";
                                            $llave = $pedido;
                                            $cliente_log = $cliente;
                                            $enlace = "Clientes|Consultar|" . $cliente;
                                            $observaciones = "Se hace la Devolución del Pedido\n---\nSe actualiza Estado y se Anulan Cobros\n \nObservación automática.";
                                            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);

                                            //Se Crea Historial Pago
                                            $this->History($cliente, $pedido, $fecha, $user, "Devolución de Pedido", intval($saldo * -1), 0, 0, 0, $observaciones);
                                                
                                            $dataActCliente = array(
                                                "Estado" => 106, //Devolucion
                                                "Observaciones" => $obs,
                                                "UsuarioCreacion" => $user,
                                                "FechaCreacion" => $fecha
                                            );

                                            $dataCliente = $this->Clientes_model->obtenerCliente($cliente);
                                            $dataOriginal = $dataCliente[0];
                                            $dataNew = compararCambiosLog($dataOriginal, $dataActCliente);
                                            $this->Clientes_model->update($cliente, $dataNew);
                                            $modulo = "Devolución Pedido";
                                            $accion = "Actualizar Cliente";
                                            $tabla = "Clientes";
                                            $llave = $pedido;
                                            $cliente_log = $cliente;
                                            $enlace = "Clientes|Consultar|" . $cliente;
                                            $observaciones = "Se hace cambio de Estado por Devolución de Pedido\n---\nSe actualiza Estado\n \nObservación automática.";
                                            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);

                                            $this->Pagos_model->quitarPagosProgramaPendientePedido($pedido);
                                            $this->Pagos_model->quitarllamadas($cliente, $pedido);
                                            
                                            echo 1;
                                        } else {
                                            echo "No se Generar la Devolución. Actualice la página y vuelva a intentarlo.";
                                        }
                                    } catch (Exception $e) {
                                        echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            echo "No tiene permisos para Generar la Devolución del Cliente";
        }
    }

    public function History($cliente, $pedido, $fecha, $usuario, $accion, $saldoAnt, $cuota, $saldoNue, $abono, $obs)
    {
        $historia = array(
            "Pedido" => $pedido,
            "Cliente" => $cliente,
            "FechaHistorial" => $fecha,
            "Accion" => $accion,
            "SaldoAnterior" => $saldoAnt,
            "Cuota" => $cuota,
            "Abono" => $abono,
            "SaldoNuevo" => $saldoNue,
            "Observaciones" => $obs,
            "UsuarioCreacion" => $usuario,
            "FechaCreacion" => $fecha
        );
        $this->Pagos_model->saveHistoria($historia);
    }

    public function Consultar($codigo)
    {
        $dataDevolucion = $this->Devoluciones_model->obtenerDevoluciónCod($codigo);
        if (isset($dataDevolucion) && $dataDevolucion != false) {
            $dataClientes = $this->Clientes_model->obtenerClienteDir($dataDevolucion[0]["Cliente"]);
            if (isset($dataClientes) && $dataClientes != false) {
                $dataCobradores = $this->Cobradores_model->obtenerCobrador($dataDevolucion[0]["Cobrador"]);
                if (isset($dataCobradores) && $dataCobradores != false) {
                    $dataDevolucion[0]["NomCobrador"] = $dataCobradores [0]["Nombre"];


                    $data = new stdClass();
                    $data->Controller = "Devoluciones";
                    $data->title = "Consultar Devoluciones";
                    $data->subtitle = "Datos de la Devolución " . $codigo;
                    $data->contenido = $this->viewControl . '/Consultar';
                    $data->ListaDatos = $dataDevolucion;
                    $data->ListaDatos2 = $dataClientes;

                    $this->load->view('frontend', $data);
                } else {
                    $this->session->set_flashdata("error", "No se encontraron datos del Cobrador: <b>" . $dataDevolucion[0]["Cobrador"] . "</b>");
                    redirect(base_url("/Devoluciones/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>" . $dataDevolucion[0]["Cliente"] . "</b>");
                redirect(base_url("/Devoluciones/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos de la Devolución: <b>" . $codigo . "</b>");
            redirect(base_url("/Devoluciones/Admin/"));
        }
    }

    public function Contador()
    {
        $f1 = date("Y-m-d 00:00:00");
        $f2 = date("Y-m-d 23:59:59");

        $Pagos = $this->ConteoPagos($f1, $f2);
        $data = new stdClass();
        $data->Controller = "Devoluciones";
        $data->title = "Conteo de Devoluciones";
        $data->subtitle = "Listado de Devoluciones";
        $data->contenido = $this->viewControl . '/Contador';
        $data->Pagos = $Pagos;

        $this->load->view('frontend', $data);
    }
}