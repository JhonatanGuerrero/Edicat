<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pagos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->viewControl = 'Pagos';
        $this->load->model('Usuarios_model');
        $this->load->model('Pagos_model');
        $this->load->model('Clientes_model');
        $this->load->model('Pedidos_model');
        $this->load->model('Estados_model');
        $this->load->model('Cobradores_model');

        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://" . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI]);
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        } else { 
            Deuda();
        }
    }

    public function index()
    {
        redirect(site_url($this->viewControl . "/Admin/"));
    } 

    public function Admin()
    {
        $datosMotivos = $this->Cobradores_model->obtenerMotivosLlamadas();

        $idPermiso = 111;
        $action = validarPermisoAcciones($idPermiso);
        if ($action == false) {
            unset($datosMotivos[0]);
        }
        $data = new stdClass();
        $data->Controller = "Pagos";
        $data->title = "Llamadas del Día";
        $data->subtitle = "Clientes para gestión de Cobro";
        $data->contenido = $this->viewControl . '/Admin';
        $data->Lista1 = $datosMotivos;

        $this->load->view('frontend', $data);
    }

    public function NoLlamada()
    {
        $datosMotivos = $this->Cobradores_model->obtenerMotivosLlamadas();

        $data = new stdClass();
        $data->Controller = "Pagos";
        $data->title = "Clientes Sin Llamar";
        $data->subtitle = "Clientes para gestión de Cobro";
        $data->contenido = $this->viewControl . '/NoLlamada';
        $data->Lista1 = $datosMotivos;

        $this->load->view('frontend', $data);
    }

    public function Admin2()
    {
        $datosPagos = $this->obtenerListadosClientesCobro();

        $datosMotivos = $this->Cobradores_model->obtenerMotivosLlamadas();
        if ($datosMotivos == false) {
            $datosMotivos = array(
                "Codigo" => "101",
                "Nombre" => "Pendiente",
                "Color" => ""
            );
        }

        $data = new stdClass();
        $data->Controller = "Pagos";
        $data->title = "Listado de Próximos Pagos";
        $data->subtitle = "Clientes para gestión de Cobro";
        $data->contenido = $this->viewControl . '/Admin2';
        $data->datosPagos = $datosPagos;
        $data->Lista1 = $datosMotivos;

        $this->load->view('frontend', $data);
    }

    public function Cliente($cliente)
    {
        $idPermiso = 23;
        $page = validarPermisoPagina($idPermiso);

        $dataClientes = $this->Clientes_model->obtenerCliente($cliente);
        if (isset($dataClientes) && $dataClientes != false) {
            $dataPago = $this->Pagos_model->obtenerPagosCliente($cliente);
            //var_dump($dataPago);

            $data = new stdClass();
            $data->Controller = "Pagos";
            $data->title = "Pagos de " . $dataClientes[0]["Nombre"];
            $data->subtitle = "Pagos de " . $dataClientes[0]["Nombre"];
            $data->contenido = $this->viewControl . '/Cliente';
            $data->cliente = $cliente;

            if (isset($dataPago) && $dataPago != false) {
                $data->ListaDatos = $dataPago;
                $data->pedido = $dataPago[0]["Pedido"];
                $dataPedido = $this->Pedidos_model->obtenerPedido($dataPago[0]["Pedido"]);
                if (isset($dataPedido) && $dataPedido != false) {
                    $data->saldo = $dataPedido[0]["Saldo"];
                }
            } else {
                $data->ListaDatos = null;
                $dataPedido = $this->Pedidos_model->obtenerPedidoPorCliente($cliente);
                if (isset($dataClientes) && $dataClientes != false) {
                    $data->pedido = $dataPedido[0]["Codigo"];
                    $data->saldo = $dataPedido[0]["Saldo"];
                }
            }
            $data->ListaDatos2 = $dataClientes;

            if ($dataClientes[0]["Estado"] == 123) {
                $this->session->set_flashdata("msg", "El Cliente <b>" . $dataClientes[0]["Nombre"] . "</b> pagó el total de la Biblia.");
            }

            $this->load->view('frontend', $data);
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>$cliente</b>");
            redirect(base_url("/Clientes/Admin/"));
        }
    }

    public function Generar($cliente)
    {
        $idPermiso = 19;
        $page = validarPermisoPagina($idPermiso);

        $dataClientes = $this->Clientes_model->obtenerClienteDir($cliente);
        if (isset($dataClientes) && $dataClientes != false) {
            $dataPedido = $this->Pedidos_model->obtenerPedidosCliente($cliente);
            if (isset($dataPedido) && $dataPedido != false) {
                $dataPagoPedido = array();
                foreach ($dataPedido as $item) {
                    $i = intval($item['Codigo']);
                    $dataPagos = $this->Pagos_model->obtenerPagosPedido($i);
                    if (isset($dataPagos) && $dataPagos != false) {
                        $dataPagoPedido[$i] = $dataPagos;
                    }
                }

                $datosPagos = array();
                $dataPagosP = array();
                foreach ($dataPedido as $item1) {
                    $i = intval($item1['Codigo']);
                    if (array_key_exists($i, $dataPagoPedido)) {
                        $dataPagosP["Pedidos"] = $i;
                        $cuota = 0;
                        $abonado = 0;
                        $f2 = "2018-01-01 00:00:00";
                        foreach ($dataPagoPedido[$i] as $item) {
                            $cuota ++;
                            $abonado = $abonado + $item["Pago"];
                            $f1 = $item["FechaPago"];
                            if ($f1 > $f2) {
                                $f2 = $f1;
                            }
                        }
                        if ($item1["ValCuota"] == "0") {
                            $num = $this->Pagos_model->ultimaCuota($i);
                            $cuota = $num[0]["Cuota"];
                        }
                        $dataPagosP["cuota"] = $cuota;
                        $dataPagosP["abonado"] = $abonado;
                        $dataPagosP["valor"] = $item1["Valor"];
                        $dataPagosP["saldo"] = intval($item1["Valor"]) - intval($abonado);
                        $dataPagosP["fechaUltimoPago"] = $f2;
                        $ultimoPago = 0;

                        foreach ($dataPagoPedido[$i] as $item) {
                            if ($item["FechaPago"] == $f2) {
                                if ($item["Pago"] > $ultimoPago) {
                                    $ultimoPago = $item["Pago"];
                                }
                            }
                        }
                        $dataPagosP["UltimoPago"] = $ultimoPago;
                        $datosPagos[$i] = $dataPagosP;
                    } else {
                        $dataPagosP["Pedidos"] = $i;
                        $dataPagosP["cuota"] = "0";
                        $dataPagosP["abonado"] = "0";
                        $dataPagosP["valor"] = $item1["Valor"];
                        $dataPagosP["saldo"] = intval($item1["Valor"]);
                        $dataPagosP["fechaUltimoPago"] = "--";
                        $ultimoPago = 0;
                        $dataPagosP["UltimoPago"] = 0;
                        $datosPagos[$i] = $dataPagosP;
                    }
                }

                $data = new stdClass();
                $data->Controller = "Pagos";
                $data->title = "Hacer Recibo";
                $data->subtitle = "Hacer nuevo Recibo a Cliente";
                $data->contenido = $this->viewControl . '/Generar';
                $data->cliente = $cliente;
                $data->ListaDatos = $dataPedido;
                $data->ListaDatos2 = $dataClientes;
                $data->ListaDatos3 = $datosPagos;

                $this->load->view('frontend', $data);
            } else {
                $this->session->set_flashdata("error", "No se encontraron pedidos del Cliente: <b>$cliente</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>$cliente</b>");
            redirect(base_url("/Clientes/Admin/"));
        }
    }

    public function SchedulePayment()
    {
        $idPermiso = 98;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            //Datos Pago Programado
            $pag_ped = trim($this->input->post('pag_ped'));
            $pag_pag = trim($this->input->post('pag_pag'));
            $pag_fec = trim($this->input->post('pag_fec') . " 00:00:00");
            $pag_fec = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $pag_fec);
            $pag_obs = trim($this->input->post('pag_obs'));
            $cli_nom = trim($this->input->post('cli_nom'));
            
            //Datos Historial
            $no_money = array("$", ".", " ");
            $pag_sal = trim($this->input->post('pag_sal'));
            $pag_sal = str_replace($no_money, "", $pag_sal);
            $pag_cuo = trim($this->input->post('pag_cuo'));
            $pag_cli = trim($this->input->post('pag_cli'));

            //Datos Auditoría
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");

            
            $tempPago = $this->Pagos_model->obtenerPagosProgramaPedidoActivos($pag_ped);
            $validacionPago = $tempPago[0]["PagosActivos"];

            if ($validacionPago == 0) {
                //Programar Pago
                $dataPago = array(
                    "Pedido" => $pag_ped,
                    "Cuota" => $pag_pag,
                    "FechaProgramada" => $pag_fec,
                    "Estado " => 116,
                    "Observaciones" => $pag_obs,
                    "Habilitado" => 1,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );

                try {
                    if ($this->Pagos_model->saveProg($dataPago)) {
                        $Pag = $this->Pagos_model->obtenerPagosProgramaPedidoPagoUserFec($pag_ped, $pag_fec, $user, $fecha);
                        if ($Pag) {
                            $modulo = "Programar Pago";
                            $accion = "Pago Programado del Cliente '" . $cli_nom."'";
                            $tabla = "PagosProgramados";
                            $llave = $Pag[0]['Codigo'];
                            $cliente_log = $pag_cli;
                            $enlace = "Pagos|Validar|" . $llave;
                            $dataInsert = $dataPago;
                            $observaciones = "Cliente programa pago por un valor de " . money_format("%.0n", $pag_pag) . ".";
                            insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                            //Se Crea Historial Pago
                            $this->History($pag_cli, $pag_ped, $fecha, $user, "Programar Pago", $pag_sal, 0, $pag_sal, $pag_pag, $pag_obs);
                            $dataLlamada = $this->Cobradores_model->obtenerLlamadasPedidoCliente($pag_ped, $pag_cli);
                            if (isset($dataLlamada) && $dataLlamada != false) {
                                $gestion = $this->AddGestionCall($pag_ped, $pag_cli, $fecha, 101, $observaciones, $user, $fecha, true);
                                $dataOriginal = $dataLlamada[0];
                                $dataNew = compararCambiosLog($dataOriginal, $gestion);
                                $llave = $dataOriginal['Codigo'];
                                $cliente_log = $pag_cli;
                                $this->Cobradores_model->updateLlamada($llave, $dataNew);
                                $modulo = "Gestión Cliente";
                                $tabla = "Llamadas";
                                $accion = "Actualizar Llamada a Cliente";
                                $enlace = "Cobradores|GestionLlamada|" . $pag_ped . "|" . $pag_cli . "|" . $llave;
                                updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                            } else {
                                $gestion = $this->AddGestionCall($pag_ped, $pag_cli, $fecha, 101, $observaciones, $user, $fecha, false);
                                $this->Cobradores_model->saveLlamada($gestion);
                                $dataGestion = $this->Cobradores_model->obtenerLlamadasPedidoFecha($pag_ped, $pag_cli, $fecha);
                                if ($dataGestion) {
                                    $modulo = "Gestión Cliente";
                                    $tabla = "Llamadas";
                                    $accion = "Hacer Llamada a Cliente";
                                    $llave = $dataGestion[0]['Codigo'];
                                    $cliente_log = $pag_cli;
                                    $enlace = "Cobradores|GestionLlamada|" . $pag_ped . "|" . $pag_cli . "|" . $llave;
                                    $dataInsert = $gestion;
                                    insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                                }
                            }

                            //ResetLlamada($pag_cli, $pag_ped);

                            echo 1;
                        } else {
                            $errores++;
                            $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                        }
                    } else {
                        $errores++;
                        $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                    }
                } catch (Exception $e) {
                    $errores++;
                    $lblErrores = 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
                }
            } else {
                echo "El Cliente ya tiene Programado un Pago. No puede agregar otro.";
            }
        } else {
            echo "No tiene permisos para Programar Pago";
        }
    }
 
    public function AddGestionCall($pedido, $cliente, $fechaGestion, $motivo, $observaciones, $user, $fecha, $actual)
    {
        if ($actual == false) {
            $gestion = array(
                "Pedido" => $pedido,
                "Cliente" => $cliente,
                "Fecha" => $fechaGestion,
                "Motivo" => $motivo,
                "Observaciones" => $observaciones,
                "UsuarioCreacion" => $user,
                "FechaCreacion" => $fecha
            );
        } else {
            $gestion = array(
                "Fecha" => $fechaGestion,
                "Motivo" => $motivo,
                "Observaciones" => $observaciones,
                "UsuarioModificacion" => $user,
                "FechaModificacion" => $fecha
            );
        }
        
        return $gestion;
    }

    public function Programados($pedido)
    {
        $dataPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
        if (isset($dataPedido) && $dataPedido != false) {
            $dataProgramados = $this->Pagos_model->obtenerPagosProgramaPedido($pedido);

            $idPermiso = 112;
            $action = validarPermisoAcciones($idPermiso);
            if ($action) {
                $eProgra = $this->valPagosProgramados($pedido, $dataProgramados, $dataPedido[0]["Cliente"]);
            } else {
                $eProgra = 0;
            }

            if (intval($eProgra) >= 1) {
                $this->session->set_flashdata("error", "Se Descartaron " . $eProgra . " Pagos por vencimiento de fecha.");
                redirect(base_url("/Pagos/Programados/" . $pedido . "/"));
            } elseif ($eProgra == 0) {
                $idPermiso = 113;
                $page = validarPermisoPagina($idPermiso);
            
                $data = new stdClass();
                $data->Controller = "Pagos";
                $data->title = "Recibos de Pago";
                $data->subtitle = "Recibos de Pago por Pedido";
                $data->contenido = $this->viewControl . '/Programados';
                $data->pedido = $pedido;
                $data->cliente = $dataPedido[0]["Cliente"];
                if (isset($dataProgramados) && $dataProgramados != false) {
                    $data->ListaDatos = $dataProgramados;
                } else {
                    $data->ListaDatos = array();
                }
                $data->ListaDatos2 = $dataPedido;

                $this->load->view('frontend', $data);
            } else {
                $this->session->set_flashdata("error", "No se Pudo validar correctamente el Pago.");
                redirect(base_url("/Pagos/Programados/" . $pedido . "/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a los datos del Pedido. Recargue la página e intente de nuevo.");
            redirect(base_url("/Clientes/Admin/"));
        }
    }

    public function ProgramadosPaz($pedido)
    {
        $dataPedido = $this->Pedidos_model->obtenerProductosPedidoClienteAll($pedido);
        if (isset($dataPedido) && $dataPedido != false) {
            $dataProgramados = $this->Pagos_model->obtenerPagosProgramaPedido($pedido);
            
            $idPermiso = 112;
            $action = validarPermisoAcciones($idPermiso);
            if ($action) {
                $eProgra = $this->valPagosProgramados($pedido, $dataProgramados, $dataPedido[0]["Cliente"]);
            } else {
                $eProgra = 0;
            }

            if (intval($eProgra) >= 1) {
                $this->session->set_flashdata("error", "Se Descartaron " . $eProgra . " Pagos por vencimiento de fecha.");
                redirect(base_url("/Pagos/Programados/" . $pedido . "/"));
            } elseif ($eProgra == 0) {
                $datacliente = $this->Clientes_model->obtenerCliente($dataPedido[0]["Cliente"]);

                $data = new stdClass();
                $data->Controller = "Pagos";
                $data->title = "Recibos de Pago";
                $data->subtitle = "Recibos de Pago por Pedido";
                $data->contenido = $this->viewControl . '/ProgramadosPaz';
                $data->pedido = $pedido;
                $data->cliente = $dataPedido[0]["Cliente"];
                if (isset($dataProgramados) && $dataProgramados != false) {
                    $data->ListaDatos = $dataProgramados;
                } else {
                    $data->ListaDatos = array();
                }
                $data->ListaDatos2 = $dataPedido;
                $this->session->set_flashdata("msg", "El Cliente <b>" . $datacliente[0]["Nombre"] . "</b> pagó el total de la Biblia.");

                $this->load->view('frontend', $data);
            } else {
                $this->session->set_flashdata("error", "No se Pudo validar correctamente el Pago.");
                redirect(base_url("/Pagos/Programados/" . $pedido . "/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a los datos del Pedido. Recargue la página e intente de nuevo.");
            redirect(base_url("/Clientes/Admin/"));
        }
    }

    public function valPagosProgramados($pedido, $dataProgramados, $cliente)
    {
        $fecha = date("Y-m-d");
        $fecha = date("Y-m-d", strtotime($fecha . "- 15 days"));
        $con = 0;

        if (isset($dataProgramados) && $dataProgramados != false) {
            foreach ($dataProgramados as $item) {
                if ($item["Estado"] == 116) { //Pago Programado
                    $datetime = date("Y-m-d", strtotime($item["FechaProgramada"]));

                    $datetime1 = date_create($datetime);
                    $datetime2 = date_create($fecha);
                    $interval = date_diff($datetime1, $datetime2);
                    $interval->format('%R%a');
                    $val = intval($interval->format('%R%a'));

                    if ($val >= 0) {
                        //Datos Auditoría
                        $user = $this->session->userdata('Usuario');
                        $fecha = date("Y-m-d H:i:s");

                        //Busqueda de pago
                        $pag_pro = $item["Codigo"];
                        $dataPagosPro = $this->Pagos_model->obtenerPagosProgramaCod($pag_pro);

                        //Descartar Pago (Actualizar pago programado No pagado)
                        $dataPago = array(
                            "Estado" => 122, //Descartado
                            "Observaciones" => $dataPagosPro[0]["Observaciones"] . "\n---\nSe descarta Pago:\nSe venció la fecha del recibo y cliente no generó pago. \nDescarte automático.",
                            "Habilitado" => 1,
                            "UsuarioModificacion" => $user,
                            "FechaModificacion" => $fecha
                        );

                        try {
                            $dataOriginal = $item;
                            $dataNew = compararCambiosLog($dataOriginal, $gestion);
                            $llave = $dataOriginal['Codigo'];
                            $cliente_log = $cliente;
                            if ($this->Pagos_model->updateProg($pag_pro, $dataNew)) {
                                $modulo = "Pagar Pedido";
                                $tabla = "PagosProgramados";
                                $accion = "Descartar Recibo de Pago Automática";
                                $enlace = "Pagos|Validar|" . $llave;
                                updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                                $con++;
                            } else {
                                return "No se pudo Actualizar el Recibo de Pago. Actualice la página y vuelva a intentarlo.";
                            }
                        } catch (Exception $e) {
                            return 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
                        }
                    }
                }
            }
        }
        if ($con >= 1) {
            return $con;
        }
        return 0;
    }

    public function Validar($pagoProgramado)
    {
        $dataPago = $this->Pagos_model->obtenerPagosProgramaCod($pagoProgramado);
        if (isset($dataPago) && $dataPago != false) {
            $dataPedido = $this->Pedidos_model->obtenerPedidoClienteAll($dataPago[0]["Pedido"]);
            if (isset($dataPedido) && $dataPedido != false) {
                $dataClientes = $this->Clientes_model->obtenerClienteDir($dataPedido[0]["Cliente"]);
                if (isset($dataClientes) && $dataClientes != false) {
                    $dataPagoPedido = array();
                    foreach ($dataPedido as $item) {
                        $i = intval($item['CodPedido']);
                        $dataPagos = $this->Pagos_model->obtenerPagosPedido($i);
                        if (isset($dataPagos) && $dataPagos != false) {
                            $dataPagoPedido[$i] = $dataPagos;
                        }
                    }

                    $datosPagos = array();
                    $dataPagosP = array();
                    foreach ($dataPedido as $item1) {
                        $i = intval($item1['CodPedido']);
                        if (array_key_exists($i, $dataPagoPedido)) {
                            $dataPagosP["Pedidos"] = $i;
                            $cuota = 0;
                            $abonado = 0;
                            $f2 = "2018-01-01 00:00:00";
                            foreach ($dataPagoPedido[$i] as $item) {
                                $cuota ++;
                                $abonado = $abonado + $item["Pago"];
                                $f1 = $item["FechaPago"];
                                if ($f1 > $f2) {
                                    $f2 = $f1;
                                }
                            }
                            $dataPagosP["cuota"] = $cuota;
                            $dataPagosP["abonado"] = $abonado;
                            $dataPagosP["valor"] = $item1["Valor1"];
                            $dataPagosP["saldo"] = intval($item1["Valor1"]) - intval($abonado);
                            $dataPagosP["fechaUltimoPago"] = $f2;
                            $ultimoPago = 0;

                            foreach ($dataPagoPedido[$i] as $item) {
                                if ($item["FechaPago"] == $f2) {
                                    if ($item["Pago"] > $ultimoPago) {
                                        $ultimoPago = $item["Pago"];
                                    }
                                }
                            }
                            $dataPagosP["UltimoPago"] = $ultimoPago;
                            $datosPagos[$i] = $dataPagosP;
                        } else {
                            $dataPagosP["Pedidos"] = $i;
                            $dataPagosP["cuota"] = "0";
                            $dataPagosP["abonado"] = "0";
                            $dataPagosP["valor"] = $item1["Valor1"];
                            $dataPagosP["saldo"] = intval($item1["Saldo"]);
                            $dataPagosP["fechaUltimoPago"] = "--";
                            $ultimoPago = 0;
                            $dataPagosP["UltimoPago"] = 0;
                            $datosPagos[$i] = $dataPagosP;
                        }
                    }

                    $data = new stdClass();
                    $data->Controller = "Pagos";
                    $data->title = "Validar Pago";
                    $data->subtitle = "Validar Recibo de Pago";
                    $data->contenido = $this->viewControl . '/Validar';
                    $data->cliente = $dataPedido[0]["Cliente"];
                    $data->ListaDatos = $dataPedido;
                    $data->ListaDatos2 = $dataClientes;
                    $data->ListaDatos3 = $datosPagos;
                    $data->dataPago = $dataPago;

                    $this->load->view('frontend', $data);
                } else {
                    $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>$cliente</b>");
                    redirect(base_url("/Clientes/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Pedido del Pago: <b>$cliente</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos de la Programación del Pago");
            redirect(base_url("/Pagos/Admin/"));
        }
    }

    public function numCuotas($pedido)
    {
        $dataPagos = $this->Pagos_model->ultimaCuota($pedido);
        return $dataPagos;
    }

    public function Confirmar($pagoProgramado)
    {
        $dataPago = $this->Pagos_model->obtenerPagosProgramaCod($pagoProgramado);
        if (isset($dataPago) && $dataPago != false) {
            $dataPedido = $this->Pedidos_model->obtenerPedidoCliente($dataPago[0]["Pedido"]);
            if (isset($dataPedido) && $dataPedido != false) {
                $dataClientes = $this->Clientes_model->obtenerClienteDir($dataPedido[0]["Cliente"]);
                if (isset($dataClientes) && $dataClientes != false) {
                    $dataCobradores = $this->Cobradores_model->obtenerCobradores();
                    if (isset($dataCobradores) && $dataCobradores != false) {
                        $dataPagoPedido = array();
                        foreach ($dataPedido as $item) {
                            $i = intval($item['CodPedido']);
                            $dataPagos = $this->Pagos_model->obtenerPagosPedido($i);
                            if (isset($dataPagos) && $dataPagos != false) {
                                $dataPagoPedido[$i] = $dataPagos;
                            }
                        }

                        $datosPagos = array();
                        $dataPagosP = array();
                        foreach ($dataPedido as $item1) {
                            $i = intval($item1['CodPedido']);
                            if (array_key_exists($i, $dataPagoPedido)) {
                                $dataPagosP["Pedidos"] = $i;
                                //echo "<script>alert(".$item1["ValCuota"].");</script>";
                                $cuota = 0;
                                $abonado = 0;
                                $f2 = "2018-01-01 00:00:00";
                                foreach ($dataPagoPedido[$i] as $item) {
                                    $cuota ++;
                                    $abonado = $abonado + $item["Pago"];
                                    $f1 = $item["FechaPago"];
                                    if ($f1 > $f2) {
                                        $f2 = $f1;
                                    }
                                }
                                
                                if ($item1["ValCuota"] == "0") {
                                    $num = $this->Pagos_model->ultimaCuota($i);
                                    $cuota = $num[0]["Cuota"];
                                }
                                $dataPagosP["cuota"] = $cuota;
                                $dataPagosP["abonado"] = $abonado;
                                $dataPagosP["valor"] = $item1["Valor1"];
                                $dataPagosP["saldo"] = intval($item1["Valor1"]) - intval($abonado);
                                $dataPagosP["fechaUltimoPago"] = $f2;
                                $ultimoPago = 0;

                                foreach ($dataPagoPedido[$i] as $item) {
                                    if ($item["FechaPago"] == $f2) {
                                        if ($item["Pago"] > $ultimoPago) {
                                            $ultimoPago = $item["Pago"];
                                        }
                                    }
                                }
                                $dataPagosP["UltimoPago"] = $ultimoPago;
                                $datosPagos[$i] = $dataPagosP;
                            } else {
                                $dataPagosP["Pedidos"] = $i;
                                $dataPagosP["cuota"] = "0";
                                $dataPagosP["abonado"] = "0";
                                $dataPagosP["valor"] = $item1["Valor1"];
                                $dataPagosP["saldo"] = intval($item1["Saldo"]);
                                $dataPagosP["fechaUltimoPago"] = "--";
                                $ultimoPago = 0;
                                $dataPagosP["UltimoPago"] = 0;
                                $datosPagos[$i] = $dataPagosP;
                            }
                        }

                        $data = new stdClass();
                        $data->Controller = "Pagos";
                        $data->title = "Confirmar Pago";
                        $data->subtitle = "Confirmar Pago";
                        $data->contenido = $this->viewControl . '/Confirmar';
                        $data->cliente = $dataPedido[0]["Cliente"];
                        $data->pedido = $dataPedido[0]["CodPedido"];
                        $data->valor = $dataPedido[0]["Valor1"];
                        $data->codigo = $pagoProgramado;
                        $data->ListaDatos = $dataPedido;
                        $data->ListaDatos2 = $dataClientes;
                        $data->ListaDatos3 = $datosPagos;
                        $data->dataPago = $dataPago;
                        $data->Lista1 = $dataCobradores;

                        $this->load->view('frontend', $data);
                    } else {
                        $this->session->set_flashdata("error", "No se encontraron datos de los Cobradores.");
                        redirect(base_url("/Cobradores/Admin/"));
                    }
                } else {
                    $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>$cliente</b>");
                    redirect(base_url("/Clientes/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Pedido del Pago: <b>$cliente</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos de la Programación del Pago");
            redirect(base_url("/Pagos/Admin/"));
        }
    }

    public function Confirm()
    {
        //Datos Pago
        $pag_cli = trim($this->input->post('pag_cli'));
        $pag_ped = trim($this->input->post('pag_ped'));
        $pag_cuo = trim($this->input->post('pag_cuo'));
        $pag_pag = trim($this->input->post('pag_pag'));
        $pag_fec = trim($this->input->post('pag_fec') . " 00:00:00");
        $pag_fec = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $pag_fec);
        $pag_pro = trim($this->input->post('pag_pro'));
        $pag_cob = trim($this->input->post('pag_cob'));
        $pag_tot = trim($this->input->post('pag_tot'));
        $pag_obs = trim($this->input->post('pag_obs'));
        $pag_obsAnt = trim($this->input->post('pag_obsAnt'));

        $this->conf($pag_cli, $pag_ped, $pag_cuo, $pag_pag, $pag_fec, $pag_pro, $pag_cob, $pag_tot, $pag_obs, $pag_obsAnt);
    }

    public function ConfirmarDia()
    {
        //Datos Pago
        $pag_cli = trim($this->input->post('cliente'));
        $pag_ped = trim($this->input->post('pedido'));
        $no_money = array("$", ".", " ");
        $pag_pag = trim($this->input->post('pago'));
        $pag_pag = str_replace($no_money, "", $pag_pag);
        $pag_fec = trim($this->input->post('FechaPago') . " 00:00:00");
        $pag_fec = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $pag_fec);
        $pag_pro = trim($this->input->post('codigo'));
        $pag_cob = trim($this->input->post('cobrador'));
        $pag_tot = trim($this->input->post('valor'));
        $pag_obs = trim($this->input->post('observaciones'));
        $pag_obsAnt = trim($this->input->post('ObservacionesAnt'));
        
        $num = $this->Pagos_model->ultimaCuota($pag_ped);
        $cuotas = $num[0]["Cuota"];

        $pag_cuo = $cuotas + 1;
        $this->conf($pag_cli, $pag_ped, $pag_cuo, $pag_pag, $pag_fec, $pag_pro, $pag_cob, $pag_tot, $pag_obs, $pag_obsAnt);
    }

    public function conf($pag_cli, $pag_ped, $pag_cuo, $pag_pag, $pag_fec, $pag_pro, $pag_cob, $pag_tot, $pag_obs, $pag_obsAnt)
    {
        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha = date("Y-m-d H:i:s");

        //Confirmar Pago (Crear Pago y actualizar pago programado)
        $dataPago = array(
            "Cliente" => $pag_cli,
            "Pedido" => $pag_ped,
            "Cuota" => $pag_cuo,
            "Pago" => $pag_pag,
            "Confirmacion" => $pag_pro,
            "FechaPago" => $pag_fec,
            "TotalPago" => $pag_tot,
            "Cobrador" => $pag_cob,
            "Observaciones" => $pag_obsAnt . "\n---\n" . $pag_obs,
            "Habilitado" => 1,
            "UsuarioCreacion" => $user,
            "FechaCreacion" => $fecha
        );

        try {
            if ($this->Pagos_model->save($dataPago)) {
                $Pag = $this->Pagos_model->obtenerPagosPedidoUserFec($pag_cli, $pag_ped, $user, $fecha);
                if ($Pag) {
                    $dataPago['Codigo'] = $Pag[0]['Codigo'];
                    $observaciones =  "Estado: Pago Realizado\n---\n" . $pag_obs;
                    $dataPago['Observaciones'] = $observaciones;
                    $modulo = "Pagar Pedido";
                    $accion = "Confirmar Pago";
                    $tabla = "Pagos";
                    $llave = $pag_ped;
                    $cliente_log = $pag_cli;
                    $enlace = "Pagos|Cliente|" . $pag_cli;
                    $dataInsert = $dataPago;
                    insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);

                    $dataPedido = $this->Pedidos_model->obtenerPedido($pag_ped);
                    $DiaCobro = $dataPedido[0]["DiaCobro"];
                    $dia = date("d", strtotime($DiaCobro . "+ 1 month"));
                    $mes = date("m", strtotime($fecha . "+ 1 month"));
                    $anio = date("Y", strtotime($DiaCobro . "+ 1 month"));
                    $proximoPago = date("Y-m-d H:i:s", strtotime($anio . "-" . $mes . "-" . $dia . " 00:00:00"));
                    $DiaCobro = date("Y-m-d H:i:s", strtotime($proximoPago));

                    $saldo = intval($dataPedido[0]["Saldo"]) - intval($pag_pag);
                    //Se Crea Historial Pago
                    $this->History($pag_cli, $pag_ped, $fecha, $user, "Confirmar Pago", $dataPedido[0]["Saldo"], $pag_cuo, $saldo, $pag_pag, $pag_obs);
                    $dataLlamada = $this->Cobradores_model->obtenerLlamadasPedidoCliente($pag_ped, $pag_cli);
                    if (isset($dataLlamada) && $dataLlamada != false) {
                        $gestion = $this->AddGestionCall($pag_ped, $pag_cli, $fecha, 101, $observaciones, $user, $fecha, true);
                        $dataOriginal = $dataLlamada[0];
                        $dataNew = compararCambiosLog($dataOriginal, $gestion);
                        $llave = $dataOriginal['Codigo'];
                        $cliente_log = $pag_cli;
                        $this->Cobradores_model->updateLlamada($llave, $dataNew);
                        $modulo = "Gestión Cliente";
                        $tabla = "Llamadas";
                        $accion = "Actualizar Llamada a Cliente";
                        $enlace = "Cobradores|GestionLlamada|" . $pag_ped . "|" . $pag_cli . "|" . $llave;
                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                    } else {
                        $gestion = $this->AddGestionCall($pag_ped, $pag_cli, $fecha, 101, $observaciones, $user, $fecha, false);
                        $this->Cobradores_model->saveLlamada($gestion);
                        $dataGestion = $this->Cobradores_model->obtenerLlamadasPedidoFecha($pag_ped, $pag_cli, $fecha);
                        if ($dataGestion) {
                            $modulo = "Gestión Cliente";
                            $tabla = "Llamadas";
                            $accion = "Hacer Llamada a Cliente";
                            $llave = $dataGestion[0]['Codigo'];
                            $cliente_log = $pag_cli;
                            $enlace = "Cobradores|GestionLlamada|" . $pag_ped . "|" . $pag_cli . "|" . $llave;
                            $dataInsert = $gestion;
                            insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                        }
                    }
                    ResetLlamada($pag_cli, $pag_ped);

                    if ($saldo <= 0) {
                        $dataActPedido = array(
                            "DiaCobro" => $DiaCobro,
                            "Saldo" => $saldo,
                            "Estado" => 114,
                            "FechaUltimoPago" => $fecha,
                            "Observaciones" => "Actualización de Saldo del Pedido:\nSaldo Anterior: " . money_format("%.0n", ($dataPedido[0]["Valor"]))
                            . "\nSaldo Actual: " . money_format("%.0n", ($saldo)) . "\nEstado: Paz y Salvo\n \nObservación automática.",
                            "UsuarioModificacion" => $user,
                            "FechaModificacion" => $fecha
                        );
                    } else {
                        $dataActPedido = array(
                            "DiaCobro" => $DiaCobro,
                            "Saldo" => $saldo,
                            "Estado" => 111,
                            "FechaUltimoPago" => $fecha,
                            "Observaciones" => "Actualización de Saldo del Pedido:\nSaldo Anterior: " . money_format("%.0n", ($dataPedido[0]["Valor"]))
                            . "\nSaldo Actual: " . money_format("%.0n", ($saldo)) . "\nEstado: Pagado\n \nObservación automática.",
                            "UsuarioModificacion" => $user,
                            "FechaModificacion" => $fecha
                        );
                    }
                    
                    $dataOriginal = $dataPedido[0];
                    $dataNew = compararCambiosLog($dataOriginal, $dataActPedido);
                    if ($this->Pedidos_model->update($pag_ped, $dataNew)) {
                        $modulo = "Pagar Pedido";
                        $accion = "Actualizar Saldo";
                        $tabla = "Pagos";
                        $llave = $pag_ped;
                        $cliente_log = $pag_cli;
                        $enlace = "Clientes|Consultar|" . $pag_cli;
                        $observaciones = "Actualización de Saldo del Pedido:\nSaldo Anterior: " . money_format("%.0n", ($dataOriginal["Valor"]));
                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);

                        $dataPagProg = array(
                            "Estado" => 117,
                            "Observaciones" => $pag_obsAnt . "\n---\n" . $pag_obs,
                            "UsuarioModificacion" => $user,
                            "FechaModificacion" => $fecha
                        );

                        $PagProg = $this->Pagos_model->obtenerPagosProgramaCod($pag_pro);
                        $dataOriginal = $PagProg[0];
                        $dataNew = compararCambiosLog($dataOriginal, $dataPagProg);
                        if ($this->Pagos_model->updateProg($pag_pro, $dataNew)) {
                            $modulo = "Pagar Pedido";
                            $accion = "Actualizar Recibo de Pago";
                            $tabla = "PagosProgramados";
                            $llave = $pag_pro;
                            $cliente_log = $pag_cli;
                            $enlace = "Pagos|Consultar|" . $llave;
                            $observaciones = "Estado: Pagado\n---\nSe actualiza estado del Recibo de Pago\n \nObservación automática.";
                            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
 
                            $this->Pagos_model->quitarllamadas($pag_cli, $pag_ped);
                            $dataCliente = $this->Clientes_model->obtenerCliente($pag_cli);

                            if ($saldo <= 0) {
                                $dataCli = array(
                                    "Estado" => 123,
                                    "Observaciones" => $pag_obsAnt . "\n---\nEstado: Paz y Salvo\n---\nCliente queda a Paz y Salvo por Saldo en $ 0\n \nObservación automática.",
                                    "UsuarioModificacion" => $user,
                                    "FechaModificacion" => $fecha
                                );
                                
                                $dataOriginal = $dataCliente[0];
                                $dataNew = compararCambiosLog($dataOriginal, $dataCli);
                                $this->Clientes_model->update($pag_cli, $dataNew);
                                $modulo = "Pagar Pedido";
                                $accion = "Paz y Salvo Cliente";
                                $tabla = "Clientes";
                                $llave = $pag_pro;
                                $cliente_log = $pag_cli;
                                $enlace = "Pagos|Cliente|" . $pag_cli;
                                $observaciones = "\nEstado: Paz y Salvo\n---\nSe actualiza estado del Cliente\n \nObservación automática.";
                                updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                                echo 123;
                            } else {
                                $dataCli = array(
                                    "Estado" => 104,
                                    "UsuarioModificacion" => $user,
                                    "FechaModificacion" => $fecha
                                );
 
                                $dataOriginal = $dataCliente[0];
                                $dataNew = compararCambiosLog($dataOriginal, $dataCli);
                                $this->Clientes_model->update($pag_cli, $dataNew);
                                $modulo = "Pagar Pedido";
                                $accion = "Estado Al día Cliente";
                                $tabla = "Clientes";
                                $llave = $pag_cli;
                                $cliente_log = $pag_cli;
                                $enlace = "Clientes|Consultar|" . $pag_cli;
                                $observaciones = "\nEstado: Al día\n---\nSe actualiza estado del Cliente\n \nObservación automática.";
                                updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                                echo 1;
                            }
                        } else {
                            echo "No se pudo Actualizar el Recibo de Pago. Actualice la página y vuelva a intentarlo.";
                        }
                    } else {
                        echo "No se pudo Actualizar el Saldo del Pedido. Actualice la página y vuelva a intentarlo.";
                    }
                } else {
                    echo "No se pudo Confirmar el Pago. Actualice la página y vuelva a intentarlo.";
                }
            } else {
                echo "No se pudo Confirmar el Pago. Actualice la página y vuelva a intentarlo.";
            }
        } catch (Exception $e) {
            echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
        }
    }

    public function Descartar($pagoProgramado)
    {
        $dataPago = $this->Pagos_model->obtenerPagosProgramaCod($pagoProgramado);
        if (isset($dataPago) && $dataPago != false) {
            $dataPedido = $this->Pedidos_model->obtenerPedidoCliente($dataPago[0]["Pedido"]);
            if (isset($dataPedido) && $dataPedido != false) {
                $dataClientes = $this->Clientes_model->obtenerClienteDir($dataPedido[0]["Cliente"]);
                if (isset($dataClientes) && $dataClientes != false) {
                    $dataPagoPedido = array();
                    foreach ($dataPedido as $item) {
                        $i = intval($item['CodPedido']);
                        $dataPagos = $this->Pagos_model->obtenerPagosPedido($i);
                        if (isset($dataPagos) && $dataPagos != false) {
                            $dataPagoPedido[$i] = $dataPagos;
                        }
                    }

                    $datosPagos = array();
                    $dataPagosP = array();
                    foreach ($dataPedido as $item1) {
                        $i = intval($item1['CodPedido']);
                        if (array_key_exists($i, $dataPagoPedido)) {
                            $dataPagosP["Pedidos"] = $i;
                            $cuota = 0;
                            $abonado = 0;
                            $f2 = "2018-01-01 00:00:00";
                            foreach ($dataPagoPedido[$i] as $item) {
                                $cuota ++;
                                $abonado = $abonado + $item["Pago"];
                                $f1 = $item["FechaPago"];
                                if ($f1 > $f2) {
                                    $f2 = $f1;
                                }
                            }
                            $dataPagosP["cuota"] = $cuota;
                            $dataPagosP["abonado"] = $abonado;
                            $dataPagosP["valor"] = $item1["Valor1"];
                            $dataPagosP["saldo"] = intval($item1["Valor1"]) - intval($abonado);
                            $dataPagosP["fechaUltimoPago"] = $f2;
                            $ultimoPago = 0;

                            foreach ($dataPagoPedido[$i] as $item) {
                                if ($item["FechaPago"] == $f2) {
                                    if ($item["Pago"] > $ultimoPago) {
                                        $ultimoPago = $item["Pago"];
                                    }
                                }
                            }
                            $dataPagosP["UltimoPago"] = $ultimoPago;
                            $datosPagos[$i] = $dataPagosP;
                        } else {
                            $dataPagosP["Pedidos"] = $i;
                            $dataPagosP["cuota"] = "0";
                            $dataPagosP["abonado"] = "0";
                            $dataPagosP["valor"] = $item1["Valor1"];
                            $dataPagosP["saldo"] = intval($item1["Saldo"]);
                            $dataPagosP["fechaUltimoPago"] = "--";
                            $ultimoPago = 0;
                            $dataPagosP["UltimoPago"] = 0;
                            $datosPagos[$i] = $dataPagosP;
                        }
                    }

                    $data = new stdClass();
                    $data->Controller = "Pagos";
                    $data->title = "Descartar Pago";
                    $data->subtitle = "Descartar Pago";
                    $data->contenido = $this->viewControl . '/Descartar';
                    $data->cliente = $dataPedido[0]["Cliente"];
                    $data->pedido = $dataPedido[0]["CodPedido"];
                    $data->valor = $dataPedido[0]["Valor1"];
                    $data->codigo = $pagoProgramado;
                    $data->ListaDatos = $dataPedido;
                    $data->ListaDatos2 = $dataClientes;
                    $data->ListaDatos3 = $datosPagos;
                    $data->dataPago = $dataPago;

                    $this->load->view('frontend', $data);
                } else {
                    $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>$cliente</b>");
                    redirect(base_url("/Clientes/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Pedido del Pago: <b>$cliente</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos de la Programación del Pago");
            redirect(base_url("/Pagos/Admin/"));
        }
    }

    public function Discard()
    {
        //Datos Pago
        $pag_cli = trim($this->input->post('pag_cli'));
        $pag_ped = trim($this->input->post('pag_ped'));
        $pag_pro = trim($this->input->post('pag_pro'));
        $pag_obs = trim($this->input->post('pag_obs'));
        $cli_nom = trim($this->input->post('cli_nom'));
        //Datos Historial
        $no_money = array("$", ".", " ");
        $pag_sal = trim($this->input->post('pag_sal'));
        $pag_sal = str_replace($no_money, "", $pag_sal);
        $pag_pag = trim($this->input->post('pag_pag'));
        $pag_pag = str_replace($no_money, "", $pag_pag);
        $pag_cuo = trim($this->input->post('pag_cuo'));
        $pag_Rellam = trim($this->input->post('pag_Rellam'));
        $pag_fechaRellam = trim($this->input->post('pag_Rellam'));
        $Fecha_Rellam = null;
        if (trim($pag_fechaRellam) == "") {
            $Fecha_Rellam = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $pag_Rellam);
        }

        $this->desc($pag_cli, $pag_ped, $pag_pro, $pag_obs, $pag_sal, $pag_pag, $pag_Rellam, $pag_fechaRellam, $Fecha_Rellam, $cli_nom);
    }

    public function DescartarDia()
    {
        $pag_cli = trim($this->input->post('cliente'));
        $pag_ped = trim($this->input->post('pedido'));
        $pag_pro = trim($this->input->post('codigo'));
        $pag_obs = trim($this->input->post('observaciones'));
        $cli_nom = trim($this->input->post('nombre'));

        //Datos Historial
        $no_money = array("$", ".", " ");
        $pag_sal = trim($this->input->post('saldo'));
        $pag_sal = str_replace($no_money, "", $pag_sal);
        $pag_pag = trim($this->input->post('pago'));
        $pag_pag = str_replace($no_money, "", $pag_pag);
        $pag_Rellam = trim($this->input->post('volverLlamar'));
        $pag_fechaRellam = trim($this->input->post('volverLlamar'));
        $Fecha_Rellam = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $pag_Rellam);

        $this->desc($pag_cli, $pag_ped, $pag_pro, $pag_obs, $pag_sal, $pag_pag, $pag_Rellam, $pag_fechaRellam, $Fecha_Rellam, $cli_nom);
    }

    public function desc($pag_cli, $pag_ped, $pag_pro, $pag_obs, $pag_sal, $pag_pag, $pag_Rellam, $pag_fechaRellam, $Fecha_Rellam = null, $cli_nom = null)
    {
        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha = date("Y-m-d H:i:s");

        //Busqueda de pago
        $dataPagosPro = $this->Pagos_model->obtenerPagosProgramaCod($pag_pro);

        //Descartar Pago (Actualizar pago programado No pagado)
        $dataPago = array(
            "Estado" => 122, //Descartado
            "Observaciones" => $dataPagosPro[0]["Observaciones"] . "\n---\nSe descarta Pago:\n" . $pag_obs,
            "Habilitado" => 1,
            "UsuarioModificacion" => $user,
            "FechaModificacion" => $fecha
        );

        try {
            $dataOriginal = $dataPagosPro[0];
            $dataNew = compararCambiosLog($dataOriginal, $dataPago);
            if ($this->Pagos_model->updateProg($pag_pro, $dataNew)) {
                $modulo = "Pagar Pedido";
                $tabla = "PagosProgramados";
                $accion = "Descartar Recibo de Pago del Cliente '" . $cli_nom."'";
                $llave = $pag_pro;
                $cliente_log = $pag_cli;
                $enlace = "Pagos|Validar|" . $llave;
                $observaciones = "Se descarta Pago:\n" . $pag_obs;
                updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);

                //Se Crea Historial Pago
                $this->History($pag_cli, $pag_ped, $fecha, $user, "Descartar Pago", $pag_sal, 0, $pag_sal, 0, $pag_obs);

                $dataLlamada = $this->Cobradores_model->obtenerLlamadasPedidoCliente($pag_ped, $pag_cli);
                if (isset($dataLlamada) && $dataLlamada != false) {
                    $gestion = $this->AddGestionCall($pag_ped, $pag_cli, $fecha, 100, $observaciones, $user, $fecha, true);
                    $dataOriginal = $dataLlamada[0];
                    $dataNew = compararCambiosLog($dataOriginal, $gestion);
                    $llave = $dataOriginal['Codigo'];
                    $cliente_log = $pag_cli;
                    $this->Cobradores_model->updateLlamada($llave, $dataNew);
                    $modulo = "Gestión Cliente";
                    $tabla = "Llamadas";
                    $accion = "Actualizar Llamada a Cliente";
                    $enlace = null;
                    updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                } else {
                    $gestion = $this->AddGestionCall($pag_ped, $pag_cli, $fecha, 100, $observaciones, $user, $fecha, false);
                    $this->Cobradores_model->saveLlamada($gestion);
                    $dataGestion = $this->Cobradores_model->obtenerLlamadasPedidoFecha($pag_ped, $pag_cli, $fecha);
                    if ($dataGestion) {
                        $modulo = "Gestión Cliente";
                        $tabla = "Llamadas";
                        $accion = "Hacer Llamada a Cliente";
                        $llave = $dataGestion[0]['Codigo'];
                        $cliente_log = $pag_cli;
                        $enlace = null;
                        $dataInsert = $gestion;
                        insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                    }
                }
                ResetLlamada($pag_cli, $pag_ped);
 
                if ($Fecha_Rellam != "" || $Fecha_Rellam != null) {
                    $Fecha_Rellam = $Fecha_Rellam . " 00:00:00";
                    //Programar Llamada para otro día
                    $observaciones = "Se descarta el Recibo de Pago.\n---\n" . $pag_obs . "\n\n"
                            . "Se asigna otro día para Llamar al Cliente nuevamente.\n"
                            . "Nuevo día: " . $pag_fechaRellam . ".\n \nObservación Automática";
                    $fechaGestion = date("Y-m-d", strtotime($fecha));
                    $gestion = array(
                        "Pedido" => $pag_ped,
                        "Cliente" => $pag_cli,
                        "Fecha" => $fechaGestion,
                        "Motivo" => 104, //Llamar otro día (black)
                        "FechaProgramada" => date("Y-m-d H:i:s", strtotime($Fecha_Rellam)),
                        "Observaciones" => $observaciones,
                        "UsuarioCreacion" => $user,
                        "FechaCreacion" => $fecha
                    );
                    if ($this->Cobradores_model->saveLlamada($gestion)) {
                        $dataGestion = $this->Cobradores_model->obtenerLlamadasPedidoFecha($pag_ped, $pag_cli, $fechaGestion);
                        if ($dataGestion) {
                            $modulo = "Pagar Pedido";
                            $tabla = "Llamada";
                            $accion = "Programar Volver a Llamar a '" . $cli_nom . "'";
                            $llave = $dataGestion[0]['Codigo'];
                            $cliente_log = $pag_cli;
                            $enlace = "Cobradores|GestionHoy|" . $pag_ped . "|" . $pag_cli;
                            $dataInsert = $gestion;
                            insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                        }
                    }
                }
                echo 1;
            } else {
                echo "No se pudo Actualizar el Recibo de Pago. Actualice la página y vuelva a intentarlo.";
            }
        } catch (Exception $e) {
            echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
        }
    }

    public function obtenerListadosClientesCobro()
    {
        $dataClientes = $this->Clientes_model->obtenerNomClientesDir($this->config->item('cli_devol'), $this->config->item('cli_paz'));
        $usuario = $this->session->userdata('Codigo');
        $PerfilId = $this->session->userdata('PerfilId');
        $idPermiso = 105;
        $pageTodos = validarPermisoAcciones($idPermiso);
        $idPermiso = 106;
        $pageOtros = validarPermisoAcciones($idPermiso);
        $idPermiso = 107;
        $pagePropios = validarPermisoAcciones($idPermiso);
        $idPermiso = 108;
        $pageNinguno = validarPermisoAcciones($idPermiso);

        if (isset($dataClientes) && $dataClientes != false) {
            $d = array();
            $dataPagoPedido = array();
            $datosPagos = array();
            $dataPagosP = array();
            foreach ($dataClientes as $itemCliente) {
                $diaCobroPedido = "";
                $cliente = $itemCliente["Codigo"];
                $dataUserCliente = false;
                
                // if ($PerfilId == $this->config->item('usuDesarrollador')){
                //     $pageTodos = TRUE;
                //     $pageNinguno = FALSE;
                // }
                if ($pageNinguno) {
                    $dataUserCliente = false;
                } elseif ($pageTodos) {
                    $dataUserCliente = true;
                } else {
                    $dataUserCliente = $this->Clientes_model->ClienteUsuarioAsignado($cliente, $usuario);
                    if ($dataUserCliente == false and $pageOtros) {
                        $dataUserCliente = true;
                    } elseif ($dataUserCliente and !$pagePropios) {
                        $dataUserCliente = false;
                    }
                }
                
                if ($dataUserCliente) {
                    $dataPedido = $this->Pedidos_model->obtenerPedidosCliente($cliente);
                    if (isset($dataPedido) && $dataPedido != false) {
                        foreach ($dataPedido as $item) {
                            // var_dump($item);
                            // echo "<br>...<br>";
                            //Validacion si el pago está programado para hoy, mañana o pasado mañana
                            $hoy = date("Y-m-d");
                            $datetime = date("Y-m-d", strtotime($item["DiaCobro"]));
                            $datetime1 = date_create($datetime);
                            $datetime2 = date_create($hoy);
                            $interval = date_diff($datetime1, $datetime2);
                            $val = intval($interval->format('%R%a'));
                            if ($val == 0) {
                                $diaCobroPedido = "Hoy";
                            } elseif ($val == -1) {
                                $diaCobroPedido = "Mañana";
                            } elseif ($val == -2) {
                                $diaCobroPedido = "Pasado Mañana";
                            } elseif ($val == -3) {
                                $diaCobroPedido = "En 3 días";
                            } elseif ($val == -4) {
                                $diaCobroPedido = date("d/m/Y", strtotime($item["DiaCobro"]));
                            } elseif ($val == -5) {
                                $diaCobroPedido = date("d/m/Y", strtotime($item["DiaCobro"]));
                            } elseif ($val == 1) {
                                $diaCobroPedido = "Ayer";
                            } elseif ($val == 2) {
                                $diaCobroPedido = "Anteayer";
                            } elseif ($val >= 3 && $val <= 5) {
                                $diaCobroPedido = "Hace " . $val . " días.";
                            }

                            $i = intval($item['Codigo']);
                            $codCliente = $itemCliente["Codigo"];
                            $telefono = trim($itemCliente["Telefono1"] . "-" . $itemCliente["Telefono2"]);
                            if ($diaCobroPedido != "") {
                                $dataPagos = $this->Pagos_model->obtenerPagosPedido($i);
                                if (isset($dataPagos) && $dataPagos != false) {
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
                        }

                        foreach ($dataPedido as $item1) {
                            //var_dump($item1["PaginaFisica"]);
                            //echo "<br><br>";
                            $i = intval($item1['Codigo']);
                            if (array_key_exists($i, $dataPagoPedido)) {
                                $dataPagosP["Nombre"] = $itemCliente["Nombre"];
                                $direccion = $itemCliente["Dir"];
                                $direccion = ($itemCliente["Etapa"] != "") ? $direccion . " ET " . $itemCliente["Etapa"] : $direccion;
                                $direccion = ($itemCliente["Torre"] != "") ? $direccion . " TO " . $itemCliente["Torre"] : $direccion;
                                $direccion = ($itemCliente["Apartamento"] != "") ? $direccion . " AP " . $itemCliente["Apartamento"] : $direccion;
                                $direccion = ($itemCliente["Manzana"] != "") ? $direccion . " MZ " . $itemCliente["Manzana"] : $direccion;
                                $direccion = ($itemCliente["Interior"] != "") ? $direccion . " IN " . $itemCliente["Interior"] : $direccion;
                                $direccion = ($itemCliente["Casa"] != "") ? $direccion . " CA " . $itemCliente["Casa"] : $direccion;
                                $dataPagosP["Direccion"] = $direccion;
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
                                    $dataPagosP["UltimoPago"] = "0";
                                }
                                $dataPagosP["cuota"] = $cuota;
                                $dataPagosP["abonado"] = $abonado;
                                $dataPagosP["valor"] = $item1["Valor"];
                                $dataPagosP["saldo"] = intval($item1["Valor"]) - intval($abonado);
                                $dataPagosP["DiaCobro"] = $diaCobroPedido;
                                $dataPagosP["telefono"] = $telefono;
                                $dataPagosP["codCliente"] = $codCliente;
                                $dataPagosP["PaginaFisica"] = $item1["PaginaFisica"];

                                $datosPagos[$i] = $dataPagosP;
                            }
                        }
                    }
                }
            }
            $datosPagos = $this->valPagosGestion($datosPagos);
            return $datosPagos;
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos de Clientes.");
            return false;
        }
    }

    public function obtenerListadosClientesNoLlamadaCobro()
    {
        $dataClientes = $this->Clientes_model->obtenerNomClientesDir($this->config->item('cli_devol'), $this->config->item('cli_paz'));
        $usuario = $this->session->userdata('Codigo');
        $PerfilId = $this->session->userdata('PerfilId');
        if (isset($dataClientes) && $dataClientes != false) {
            $d = array();
            $dataPagoPedido = array();
            $datosPagos = array();
            $dataPagosP = array();
            foreach ($dataClientes as $itemCliente) {
                $diaCobroPedido = "";
                $cliente = $itemCliente["Codigo"];
                $dataUserCliente = false;
                if ($PerfilId >= 102) {
                    $dataUserCliente = $this->Clientes_model->ClienteUsuario($cliente, $usuario);
                } else {
                    $dataUserCliente = true;
                }
                if ($dataUserCliente != false) {
                    $dataPedido = $this->Pedidos_model->obtenerPedidosCliente($cliente);
                    if (isset($dataPedido) && $dataPedido != false) {
                        foreach ($dataPedido as $item) {
                            //Validacion si el pago está programado para hoy, mañana o pasado mañana
                            $hoy = date("Y-m-d");
                            $datetime = date("Y-m-d", strtotime($item["DiaCobro"]));
                            $datetime1 = date_create($datetime);
                            $datetime2 = date_create($hoy);
                            $interval = date_diff($datetime1, $datetime2);
                            $val = intval($interval->format('%R%a'));
                            if ($val == 1) {
                                $diaCobroPedido = "Ayer";
                            } elseif ($val == 2) {
                                $diaCobroPedido = "Anteayer";
                            } elseif ($val >= 3) {
                                if ($val % 30 == 0) {
                                    $d = $val / 30;
                                    $diaCobroPedido = "Hace " . $d . " meses.";
                                } else {
                                    $diaCobroPedido = "Hace " . $val . " días.";
                                }
                            }

                            $i = intval($item['Codigo']);
                            $codCliente = $itemCliente["Codigo"];
                            $telefono = trim($itemCliente["Telefono1"] . "-" . $itemCliente["Telefono2"]);
                            if ($diaCobroPedido != "") {
                                $dataPagos = $this->Pagos_model->obtenerPagosPedido($i);
                                if (isset($dataPagos) && $dataPagos != false) {
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
                        }

                        foreach ($dataPedido as $item1) {
                            $i = intval($item1['Codigo']);
                            if (array_key_exists($i, $dataPagoPedido)) {
                                $dataPagosP["Nombre"] = $itemCliente["Nombre"];
                                $direccion = $itemCliente["Dir"];
                                $direccion = ($itemCliente["Etapa"] != "") ? $direccion . " ET " . $itemCliente["Etapa"] : $direccion;
                                $direccion = ($itemCliente["Torre"] != "") ? $direccion . " TO " . $itemCliente["Torre"] : $direccion;
                                $direccion = ($itemCliente["Apartamento"] != "") ? $direccion . " AP " . $itemCliente["Apartamento"] : $direccion;
                                $direccion = ($itemCliente["Manzana"] != "") ? $direccion . " MZ " . $itemCliente["Manzana"] : $direccion;
                                $direccion = ($itemCliente["Interior"] != "") ? $direccion . " IN " . $itemCliente["Interior"] : $direccion;
                                $direccion = ($itemCliente["Casa"] != "") ? $direccion . " CA " . $itemCliente["Casa"] : $direccion;
                                $dataPagosP["Direccion"] = $direccion;
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
                                    $dataPagosP["UltimoPago"] = "0";
                                }
                                $dataPagosP["cuota"] = $cuota;
                                $dataPagosP["abonado"] = $abonado;
                                $dataPagosP["valor"] = $item1["Valor"];
                                $dataPagosP["saldo"] = intval($item1["Valor"]) - intval($abonado);
                                $dataPagosP["DiaCobro"] = $diaCobroPedido;
                                $dataPagosP["telefono"] = $telefono;
                                $dataPagosP["codCliente"] = $codCliente;
                                $dataPagosP["PaginaFisica"] = $item1["PaginaFisica"];

                                $datosPagos[$i] = $dataPagosP;
                            }
                        }
                    }
                }
            }
            $datosPagos = $this->valPagosGestion($datosPagos);
            //print_r($dataPagoPedido);
            return $datosPagos;
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos de Clientes.");
            return false;
        }
    }

    public function obtenerListadosClientesCobroJson()
    {
        $data = $this->obtenerListadosClientesCobro();
        $arreglo["data"] = [];

        if (isset($data) && $data != false) {
            $i = 0;
            foreach ($data as $item) {
                // var_dump($item);
                // echo "<br>-<br>";
                
                $btn1 = "";
                $btn2 = "";
                $btn3 = "";
                $btn4 = "";

                $idPermiso = 109;
                $action = validarPermisoAcciones($idPermiso);
                if ($action) {
                    $btn1 = '<a href = "#ModalCall" data-toggle = "modal" title = "Reportar Llamada" onclick = "DatosModal(\'' . $item["Pedidos"] . '\', \'' . $item["codCliente"] . '\', \'' . $item["Nombre"] . '\', \'' . $item["Direccion"] . '\', \'' . $item["telefono"] . '\');"><i class = "fa fa-phone" aria-hidden = "true" style = "padding:5px;"></i></a>';
                }

                if ($item["Color"]=="green"){
                    $idPermiso = 114;
                    $action = validarPermisoAcciones($idPermiso);
                    if ($action) {
                        $btn2 = '<a href = "' . base_url() . 'Pagos/Validar/' . $item["codPago"] . '/" target="_blank" title = "Ver Recibo de Pago"><i class = "fa fa-search" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    }  
                } else {
                    $idPermiso = 98;
                    $action = validarPermisoAcciones($idPermiso);
                    if ($action) {
                        $btn2 = '<a href = "' . base_url() . 'Pagos/Generar/' . $item["codCliente"] . '/" target="_blank" title = "Pagar"><i class = "fa fa-motorcycle" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    }
                }
                
                $idPermiso = 23;
                $action = validarPermisoAcciones($idPermiso);
                if ($action) {
                    $btn3 = '<a href = "' . base_url() . 'Clientes/Pagos/' . $item["Pedidos"] . '/"  title = "Pagos Realizados del Cliente"><i class = "fa fa-usd" aria-hidden = "true" style = "padding:5px;"></i></a>';
                }
                
                if ($btn1 == "" and $btn2 == "" and  $btn3 == "") {
                    $btn4 = "No tiene Permisos";
                }

                $arreglo["data"][$i] = array(
                    "Nombre" => $item["Nombre"],
                    "Direccion" => $item["Direccion"],
                    "telefono" => $item["telefono"],
                    "cuota" => $item["cuota"],
                    "saldo" => money_format("%.0n", $item["saldo"]),
                    "UltimoPago" => money_format("%.0n", $item["UltimoPago"]),
                    "DiaCobro" => $item["DiaCobro"],
                    "Ubicacion" => $item["PaginaFisica"],
                    "Motivo" => $item["Motivo"],
                    "Color" => $item["Color"],
                    "btn" => '<div class="btn-group text-center" style="margin: 0px auto;  width:100%;">' . $btn1 . $btn2 . $btn3 . $btn4 . '</div>'
                );
                $i++;
            }
        }
        echo json_encode($arreglo);
    }

    public function obtenerListadosClientesNoLlamadaCobroJson()
    {
        $data = $this->obtenerListadosClientesNoLlamadaCobro();
        $arreglo["data"] = [];

        if (isset($data) && $data != false) {
            $i = 0;
            foreach ($data as $item) {
                // var_dump($item);
                // echo "<br><br>";
                $btn1 = "";
                $btn2 = "";
                $btn3 = "";
                $btn4 = "";

                $idPermiso = 109;
                $action = validarPermisoAcciones($idPermiso);
                if ($action) {
                    $btn1 = '<a href = "#ModalCall" data-toggle = "modal" title = "Reportar Llamada" onclick = "DatosModal(\'' . $item["Pedidos"] . '\', \'' . $item["codCliente"] . '\', \'' . $item["Nombre"] . '\', \'' . $item["Direccion"] . '\', \'' . $item["telefono"] . '\');"><i class = "fa fa-phone" aria-hidden = "true" style = "padding:5px;"></i></a>';
                }

                
                if ($item["Color"]=="green"){
                    $idPermiso = 114;
                    $action = validarPermisoAcciones($idPermiso);
                    if ($action) {
                        $btn2 = '<a href = "' . base_url() . 'Pagos/Validar/' . $item["codPago"] . '/" target="_blank" title = "Ver Recibo de Pago"><i class = "fa fa-search" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    }

                    $idPermiso = 23;
                    $action = validarPermisoAcciones($idPermiso);
                    if ($action) {
                        $btn3 = '<a href = "' . base_url() . 'Pagos/Programados/' . $item["Pedidos"] . '/"  target="_blank" title = "Lista Recibos de Pago del Cliente"><i class = "fa fa-calculator" aria-hidden = "true" style = "padding:5px;"></i></a>';
                        $btn3 = $btn3  . '<a href = "' . base_url() . 'Clientes/Pagos/' . $item["Pedidos"] . '/"  target="_blank" title = "Pagos Realizados del Cliente"><i class = "fa fa-usd" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    }

                } else {
                    $idPermiso = 98;
                    $action = validarPermisoAcciones($idPermiso);
                    if ($action) {
                        $btn2 = '<a href = "' . base_url() . 'Pagos/Generar/' . $item["codCliente"] . '/" target="_blank" title = "Pagar"><i class = "fa fa-motorcycle" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    }
                    
                    $idPermiso = 23;
                    $action = validarPermisoAcciones($idPermiso);
                    if ($action) {
                        $btn3 = '<a href = "' . base_url() . 'Clientes/Pagos/' . $item["Pedidos"] . '/"  title = "Pagos Realizados del Cliente"><i class = "fa fa-usd" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    }
                } 

                if ($btn1 == "" and $btn2 == "" and  $btn3 == "") {
                    $btn4 = "No tiene Permisos";
                }
 
                $arreglo["data"][$i] = array(
                    "Nombre" => $item["Nombre"],
                    "Direccion" => $item["Direccion"],
                    "telefono" => $item["telefono"],
                    "cuota" => $item["cuota"],
                    "saldo" => money_format("%.0n", $item["saldo"]),
                    "UltimoPago" => money_format("%.0n", $item["UltimoPago"]),
                    "DiaCobro" => $item["DiaCobro"],
                    "Ubicacion" => $item["PaginaFisica"],
                    "Motivo" => $item["Motivo"],
                    "Color" => $item["Motivo"],
                    "Color" => $item["Color"],
                    "btn" => '<div class="btn-group text-center" style="margin: 0px auto;  width:100%;">' . $btn1 . $btn2 . $btn3 . $btn4 . '</div>'
                );
                $i++;
            }
        }
        echo json_encode($arreglo);
    }

    public function Consultar($pago)
    {
        $dataPago = $this->Pagos_model->obtenerPagosCod($pago);
        if (isset($dataPago) && $dataPago != false) {
            $dataPedido = $this->Pedidos_model->obtenerProductosPedidoClienteAll($dataPago[0]["Pedido"]);
            if (isset($dataPedido) && $dataPedido != false) {
                $dataClientes = $this->Clientes_model->obtenerClienteDir($dataPedido[0]["Cliente"]);
                if (isset($dataClientes) && $dataClientes != false) {
                    $dataCobradores = $this->Cobradores_model->obtenerCobrador($dataPago[0]["Cobrador"]);
                    if ($dataCobradores == false) {
                        $Cobradores = array(
                            "Codigo" => "1",
                            "Nombre" => "Sin Cobrador Asociado"
                        );
                        $dataCobradores [0] = $Cobradores;
                    }

                    $dataPagoPedido = array();
                    foreach ($dataPedido as $item) {
                        $i = intval($item['CodPedido']);
                        $dataPagos = $this->Pagos_model->obtenerPagosPedido($i);
                        if (isset($dataPagos) && $dataPagos != false) {
                            $dataPagoPedido[$i] = $dataPagos;
                        }
                    }

                    $datosPagos = array();
                    $dataPagosP = array();
                    foreach ($dataPedido as $item1) {
                        $i = intval($item1['CodPedido']);
                        if (array_key_exists($i, $dataPagoPedido)) {
                            $dataPagosP["Pedidos"] = $i;
                            $cuota = 0;
                            $abonado = 0;
                            $f2 = "2018-01-01 00:00:00";
                            foreach ($dataPagoPedido[$i] as $item) {
                                $cuota ++;
                                $abonado = $abonado + $item["Pago"];
                                $f1 = $item["FechaPago"];
                                if ($f1 > $f2) {
                                    $f2 = $f1;
                                }
                            }
                            $dataPagosP["cuota"] = $cuota;
                            $dataPagosP["abonado"] = $abonado;
                            $dataPagosP["valor"] = $item1["Valor"];
                            $dataPagosP["saldo"] = intval($item1["Valor"]) - intval($abonado);
                            $dataPagosP["fechaUltimoPago"] = $f2;
                            $ultimoPago = 0;

                            foreach ($dataPagoPedido[$i] as $item) {
                                if ($item["FechaPago"] == $f2) {
                                    if ($item["Pago"] > $ultimoPago) {
                                        $ultimoPago = $item["Pago"];
                                    }
                                }
                            }
                            $dataPagosP["UltimoPago"] = $ultimoPago;
                            $datosPagos[$i] = $dataPagosP;
                        } else {
                            $dataPagosP["Pedidos"] = $i;
                            $dataPagosP["cuota"] = "0";
                            $dataPagosP["abonado"] = "0";
                            $dataPagosP["valor"] = $item1["Valor"];
                            $dataPagosP["saldo"] = intval($item1["Saldo"]);
                            $dataPagosP["fechaUltimoPago"] = "--";
                            $ultimoPago = 0;
                            $dataPagosP["UltimoPago"] = 0;
                            $datosPagos[$i] = $dataPagosP;
                        }
                    }

                    $data = new stdClass();
                    $data->Controller = "Pagos";
                    $data->title = "Información del Pago";
                    $data->subtitle = "Confirmar Pago";
                    $data->contenido = $this->viewControl . '/Consultar';
                    $data->cliente = $dataPedido[0]["Cliente"];
                    $data->pedido = $dataPedido[0]["CodPedido"];
                    $data->valor = $dataPedido[0]["Valor"];
                    $data->codigo = $pago;
                    $data->ListaDatos = $dataPedido;
                    $data->ListaDatos2 = $dataClientes;
                    $data->ListaDatos3 = $datosPagos;
                    $data->dataPago = $dataPago;
                    $data->Lista1 = $dataCobradores;

                    $this->load->view('frontend', $data);
                } else {
                    $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>" . $dataPedido[0]["Cliente"] . "</b>");
                    redirect(base_url("/Clientes/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Pedido del Pago: <b>" . $dataPago[0]["Pedido"] . "</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos del Pago");
            redirect(base_url("/Pagos/Admin/"));
        }
    }

    public function Log($pedido)
    {
        if ($pedido == null || $pedido == "") {
            $this->session->set_flashdata("error", "No se encontró Pedido.");
            redirect(base_url("/Clientes/Admin/"));
        } else {
            $dataPedido = $this->Pedidos_model->obtenerProductosPedidoClienteAll($pedido);
            if (isset($dataPedido) && $dataPedido != false) {
                $cliente = $dataPedido[0]["Cliente"];
                $dataLog = $this->Pedidos_model->LogPedido($pedido);
                if (isset($dataLog) && $dataLog != false) {
                    $datacliente = $this->Clientes_model->obtenerCliente($cliente);

                    $data = new stdClass();
                    $data->Controller = "Log";
                    $data->title = "Historial de Pagos";
                    $data->subtitle = "Pagos del Pedido <b>" . $pedido . "</b>";
                    $data->contenido = $this->viewControl . '/Log';
                    $data->cliente = $cliente;
                    $data->ListaDatos = $dataLog;

                    foreach ($dataLog as $value) {
                        //Obtener Codigo del Pago y del Recibo de Pago
                        $len = strlen($value["Datos"]);
                        $pos = (strpos($value["Datos"], "Codigo:") + 8);
                        $cod = intval(substr($value["Datos"], $pos, $len));
                        if ($value["Tabla"] === "Pagos") {
                            $dataP = $this->Pagos_model->obtenerPagosCod($cod);
                        } elseif ($value["Tabla"] === "PagosProgramados") {
                            $dataP = $this->Pagos_model->obtenerPagosProgramaCod($cod);
                        }
                    }
                    if ($datacliente[0]["Estado"] == 123) {
                        $this->session->set_flashdata("msg", "El Cliente <b>" . $datacliente[0]["Nombre"] . "</b> pagó el total de la Biblia.");
                    }

                    $this->load->view('frontend', $data);
                } else {
                    $this->session->set_flashdata("error", "El Pedido no tiene Registros de Log");
                    redirect(base_url("/Clientes/Pagos/" . $cliente . "/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Pedido: <b>" . $pedido . "</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        }
    }

    public function VerLog($codigo)
    {
        $dataLog = $this->Log_model->obtenerLogPorCod($codigo);
        if (isset($dataLog) && $dataLog != false) {
            $data = new stdClass();
            $data->Controller = "Log";
            $data->title = "Log de Registros";
            $data->subtitle = "Registros número <b>" . $codigo . "</b>";
            $data->contenido = $this->viewControl . '/VerLog';
            $data->ListaDatos = $dataLog;
            $this->load->view('frontend', $data);
        } else {
            $this->session->set_flashdata("error", "El Registro <b>" . $codigo . "</b> no fue encontrado.");
            redirect(base_url("/Clientes/Admin/"));
        }
    }

    public function Historial($pedido)
    {
        if ($pedido == null || $pedido == "") {
            $this->session->set_flashdata("error", "No se encontró Pedido.");
            redirect(base_url("/Clientes/Admin/"));
        } else {
            $dataPedido = $this->Pedidos_model->obtenerProductosPedidoClienteAll($pedido);
            if (isset($dataPedido) && $dataPedido != false) {
                $cliente = $dataPedido[0]["Cliente"];
                $datacliente = $this->Clientes_model->obtenerCliente($cliente);

                $dataLog = $this->Pagos_model->obtenerHistorialPagosPedido($pedido);
                if (isset($dataLog) && $dataLog != false) {
                    $data = new stdClass();
                    $data->Controller = "Log";
                    $data->title = "Historial de Pagos";
                    $data->subtitle = "Pagos del Pedido <b>" . $pedido . "</b>";
                    $data->contenido = $this->viewControl . '/Historial';
                    $data->ListaDatos = $dataLog;
                    $data->cliente = $cliente;
                    if ($datacliente[0]["Estado"] == 123) {
                        $this->session->set_flashdata("msg", "El Cliente <b>" . $datacliente[0]["Nombre"] . "</b> pagó el total de la Biblia.");
                    }

                    $this->load->view('frontend', $data);
                } else {
                    $this->session->set_flashdata("error", "No hay Historial de Pagos");
                    redirect(base_url("/Clientes/Pagos/" . $cliente . "/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Pedido: <b>" . $pedido . "</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
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

    public function Morosos()
    {
        $idPermiso = 13;
        $page = validarPermisoPagina($idPermiso);

        $usuario = $this->session->userdata('Codigo');
        $PerfilId = $this->session->userdata('PerfilId');
        $dataPedidos = $this->Pedidos_model->obtenerPedidosDeben();
        $dataCobradores = $this->Cobradores_model->obtenerCobradores();

        if (isset($dataPedidos) && $dataPedidos != false) {
            $i = 0;
            foreach ($dataPedidos as $value) {
                $dataUserCliente = false;
                if ($PerfilId >= 102) {
                    $dataUserCliente = $this->Clientes_model->ClienteUsuario($value["Cliente"], $usuario);
                } else {
                    $dataUserCliente = true;
                }
                if ($dataUserCliente != false) {
                    $dataCliente = $this->Clientes_model->obtenerClienteDir($value["Cliente"]);
                    $dataPedidos[$i]["CodCliente"] = $dataCliente["0"]["Codigo"];
                    $dataPedidos[$i]["Nombre"] = $dataCliente["0"]["Nombre"];
                    $direccion = $dataCliente["0"]["Dir"];
                    $direccion = ($dataCliente["0"]["Etapa"] != "") ? $direccion . " ET " . $dataCliente["0"]["Etapa"] : $direccion;
                    $direccion = ($dataCliente["0"]["Torre"] != "") ? $direccion . " TO " . $dataCliente["0"]["Torre"] : $direccion;
                    $direccion = ($dataCliente["0"]["Apartamento"] != "") ? $direccion . " AP " . $dataCliente["0"]["Apartamento"] : $direccion;
                    $direccion = ($dataCliente["0"]["Manzana"] != "") ? $direccion . " MZ " . $dataCliente["0"]["Manzana"] : $direccion;
                    $direccion = ($dataCliente["0"]["Interior"] != "") ? $direccion . " IN " . $dataCliente["0"]["Interior"] : $direccion;
                    $direccion = ($dataCliente["0"]["Casa"] != "") ? $direccion . " CA " . $dataCliente["0"]["Casa"] : $direccion;
                    $dataPedidos[$i]["Direccion"] = $direccion;
                    $telefono = $dataCliente["0"]["Telefono1"];
                    $telefono = ($dataCliente["0"]["Telefono2"] != "") ? $telefono . " - " . $dataCliente["0"]["Telefono2"] : $telefono;
                    //$telefono = ($itemCliente["Telefono3"] != "") ? $telefono . " - " . $itemCliente["Telefono3"] : $telefono;
                    $dataPedidos[$i]["Telefono"] = $telefono;
                    $dataPedidos[$i]["Barrio"] = $dataCliente["0"]["Barrio"];
                    $dataPedidos[$i]["EstNombre"] = $dataCliente["0"]["EstNombre"];
                    $cuotas = $this->Pagos_model->obtenerPagosPorPedido($value["Codigo"]);
                    if ($cuotas != false) {
                        $cuotas = $cuotas[0]["Cuotas"];
                    }
                    $dataPedidos[$i]["Cuotas"] = $cuotas;
                    $dataPedidos[$i]["PaginaFisica"] = $value["PaginaFisica"];
                } else {
                    unset($dataPedidos[$i]);
                }
                $i++;
            }
            $this->session->set_flashdata("msg", "");
        } else {
            $this->session->set_flashdata("msg", "No hay Clientes Morosos");
        }

        if (count($dataPedidos) <= 0) {
            if ($PerfilId >= 102) {
                $this->session->set_flashdata("error", "Usted no tiene Clientes Asociados como Morosos.");
            } else {
                $this->session->set_flashdata("error", "No hay Clientes Morosos");
            }
        }
        $data = new stdClass();
        $data->Controller = "Clientes";
        $data->title = "Clientes Morosos";
        $data->subtitle = "Listado de Clientes Morosos";
        $data->contenido = $this->viewControl . '/Morosos';
        $data->ListaDatos = $dataPedidos;
        $data->Lista1 = $dataCobradores;

        $this->load->view('frontend', $data);
    }

    public function Datacredito()
    {
        $idPermiso = 14;
        $page = validarPermisoPagina($idPermiso);

        $dataPedidos = $this->Pedidos_model->obtenerPedidosDatacredito();
        $dataCobradores = $this->Cobradores_model->obtenerCobradores();
        if (isset($dataPedidos) && $dataPedidos != false) {
            $i = 0;
            $usuario = $this->session->userdata('Codigo');
            $PerfilId = $this->session->userdata('PerfilId');
            foreach ($dataPedidos as $value) {
                $dataUserCliente = false;
                if ($PerfilId >= 102) {
                    $dataUserCliente = $this->Clientes_model->ClienteUsuario($value["Cliente"], $usuario);
                } else {
                    $dataUserCliente = true;
                }
                if ($dataUserCliente != false) {
                    $dataCliente = $this->Clientes_model->obtenerClienteDir($value["Cliente"]);
                    $dataPedidos[$i]["Nombre"] = $dataCliente["0"]["Nombre"];
                    $direccion = $dataCliente["0"]["Dir"];
                    $direccion = ($dataCliente["0"]["Etapa"] != "") ? $direccion . " ET " . $dataCliente["0"]["Etapa"] : $direccion;
                    $direccion = ($dataCliente["0"]["Torre"] != "") ? $direccion . " TO " . $dataCliente["0"]["Torre"] : $direccion;
                    $direccion = ($dataCliente["0"]["Apartamento"] != "") ? $direccion . " AP " . $dataCliente["0"]["Apartamento"] : $direccion;
                    $direccion = ($dataCliente["0"]["Manzana"] != "") ? $direccion . " MZ " . $dataCliente["0"]["Manzana"] : $direccion;
                    $direccion = ($dataCliente["0"]["Interior"] != "") ? $direccion . " IN " . $dataCliente["0"]["Interior"] : $direccion;
                    $direccion = ($dataCliente["0"]["Casa"] != "") ? $direccion . " CA " . $dataCliente["0"]["Casa"] : $direccion;
                    $dataPedidos[$i]["Direccion"] = $direccion;
                    $telefono = $dataCliente["0"]["Telefono1"];
                    $telefono = ($dataCliente["0"]["Telefono2"] != "") ? $telefono . " - " . $dataCliente["0"]["Telefono2"] : $telefono;
                    //$telefono = ($itemCliente["Telefono3"] != "") ? $telefono . " - " . $itemCliente["Telefono3"] : $telefono;
                    $dataPedidos[$i]["Telefono"] = $telefono;
                    $dataPedidos[$i]["Barrio"] = $dataCliente["0"]["Barrio"];
                    $dataPedidos[$i]["EstNombre"] = $dataCliente["0"]["EstNombre"];
                    $cuotas = $this->Pagos_model->obtenerPagosPorPedido($value["Codigo"]);
                    if ($cuotas != false) {
                        $cuotas = $cuotas[0]["Cuotas"];
                    }
                    $dataPedidos[$i]["Cuotas"] = $cuotas;
                    $dataPedidos[$i]["PaginaFisica"] = $value["PaginaFisica"];
                } else {
                    unset($dataPedidos[$i]);
                }
                $i++;
            }
            $this->session->set_flashdata("msg", "");
        } else {
            $this->session->set_flashdata("msg", "No hay Clientes Para DataCrédito");
        }
        if (count($dataPedidos) <= 0) {
            if ($PerfilId >= 102) {
                $this->session->set_flashdata("error", "Usted no tiene Clientes Asociados en Datacrédito.");
            } else {
                $this->session->set_flashdata("error", "No hay Clientes Para DataCrédito.");
            }
        }

        $data = new stdClass();
        $data->Controller = "Clientes";
        $data->title = "Clientes con DataCrédito";
        $data->subtitle = "Listado de Clientes con DataCrédito";
        $data->contenido = $this->viewControl . '/Datacredito';
        $data->ListaDatos = $dataPedidos;
        $data->Lista1 = $dataCobradores;

        $this->load->view('frontend', $data);
    }

    public function ReportarData($pedido)
    {
        if ($pedido != "") {
            $dataPedido = $this->Pedidos_model->obtenerPedido($pedido);
            if ($dataPedido != false) {
                $estado = $dataPedido[0]["Estado"];
                //Se valida Estado 125 Datacrédito
                if ($estado == 125) {
                    //Datos Auditoría
                    $user = $this->session->userdata('Usuario');
                    $fecha = date("Y-m-d H:i:s");

                    $fecha1 = date("Y-m-d", strtotime($dataPedido[0]["DiaCobro"]));
                    $fecha2 = date("Y-m-d", strtotime($fecha));
                    $interval = date_diff(date_create($fecha1), date_create($fecha2));
                    $diferencia = intval($interval->format('%a'));
                    $signo = $interval->format('%R');
                    if ($diferencia >= 90 && $signo == "+") {
                        $observaciones = "Se Reporta a Datacrédito por falta de Pago después de " . $interval->format('%a') . " días.\n---\nEl Pago debió hacerse el día " . date("d/m/Y", strtotime($dataPedido[0]["DiaCobro"])) . ".\nSe envía carta de Cobro Prejuridico.";
                        $dataPed = array(
                            "Estado" => 127, //Reportado
                            "Observaciones" => $observaciones,
                            "UsuarioModificacion" => $user,
                            "FechaModificacion" => $fecha
                        );

                        $cliente = $dataPedido[0]["Cliente"];
                        $dataCli = array(
                            "Estado" => 126, //Reportado
                            "Observaciones" => $observaciones,
                            "UsuarioModificacion" => $user,
                            "FechaModificacion" => $fecha
                        );

                        try {
                            $dataOriginal = $dataPedido[0];
                            $dataNew = compararCambiosLog($dataOriginal, $dataPed);
                            if ($this->Pedidos_model->update($pedido, $dataNew)) {
                                $modulo = "Reportar Pedido";
                                $tabla = "Pedidos";
                                $accion = "Cambio Estado Pedido";
                                $llave = $pedido;
                                $cliente_log = $cliente;
                                $enlace = "Clientes|Consultar|" . $cliente;
                                $observaciones = "";
                                updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                                
                                $datacliente = $this->Clientes_model->obtenerCliente($cliente);                                
                                $dataOriginal = $datacliente[0];
                                $dataNew = compararCambiosLog($dataOriginal, $dataCli);                            
                                if ($this->Clientes_model->update($cliente, $dataNew)) {
                                    $modulo = "Reportar Pedido";
                                    $tabla = "Clientes";
                                    $accion = "Cambio Estado Cliente";
                                    $llave = $cliente;
                                    $cliente_log = $cliente;
                                    $enlace = "Clientes|Consultar|" . $cliente;
                                    $observaciones = "";
                                    updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                                
                                    echo 1; 
                                } else {
                                    $this->session->set_flashdata("error", "No se pudo Actualizar el Pedido.");
                                    redirect(base_url("/Pagos/Datacredito/"));
                                }
                            } else {
                                $this->session->set_flashdata("error", "No se pudo Actualizar el Pedido.");
                                redirect(base_url("/Pagos/Datacredito/"));
                            }
                        } catch (Exception $e) {
                            $this->session->set_flashdata("error", "Ha habido una excepción: " . $e->getMessage());
                            redirect(base_url("/Pagos/Datacredito/"));
                        }
                    } else {
                        $this->session->set_flashdata("error", "Los días del NO PAGO del Pedido " . $pedido . " no aplican para hacer el Reporte. " . $interval->format('%R%a') . " días.");
                        redirect(base_url("/Pagos/Datacredito/"));
                    }
                } else {
                    $this->session->set_flashdata("error", "El Pedido " . $pedido . " no está en Estado Datacrédito, por ende no puede hacer el Reporte");
                    redirect(base_url("/Pagos/Datacredito/"));
                }
            } else {
                $this->session->set_flashdata("error", "Debe indicar un Pedido válido para hacer el Reporte");
                redirect(base_url("/Pagos/Datacredito/"));
            }
        } else {
            $this->session->set_flashdata("error", "Debe indicar el Pedido del Cliente para hacer el Reporte");
            redirect(base_url("/Pagos/Datacredito/"));
        }
    }

    public function Revision($ReturnUrl = null)
    {
        $data = new stdClass();
        $data->Controller= "Pagos";
        $data->title = "Revisión de Clientes";
        $data->subtitle = "Revisión de Clientes";
        $data->contenido = $this->viewControl . '/Revision';

        $ReturnUrl = str_replace("%7C", "/", $ReturnUrl);
        $ReturnUrl = str_replace("|", "/", $ReturnUrl);
 
        $this->load->view('frontend', $data);
        
        $ret = valPagosProgramadosAll();
        if ($ret == 1) {
            $ret = Deuda();
            if ($ret == 1) {
                $this->session->set_flashdata("msg", "Se Actualizaron los estados de los Clientes.");
            }            
        }  
        redirect(base_url() . $ReturnUrl);
    }

    public function PagarMora($pedido)
    {
        $dataPedido = $this->Pedidos_model->obtenerPedidosClientePorPedido($pedido);
        if (isset($dataPedido) && $dataPedido != false) {
            $dataClientes = $this->Clientes_model->obtenerClienteDir($dataPedido[0]["Cliente"]);
            if (isset($dataClientes) && $dataClientes != false) {
                $cliente = $dataPedido[0]["Cliente"];
                $dataPagoPedido = array();
                foreach ($dataPedido as $item) {
                    $i = intval($item['Codigo']);
                    $dataPagos = $this->Pagos_model->obtenerPagosPedido($i);
                    if (isset($dataPagos) && $dataPagos != false) {
                        $dataPagoPedido[$i] = $dataPagos;
                    }
                }

                $datosPagos = array();
                $dataPagosP = array();
                foreach ($dataPedido as $item1) {
                    $i = intval($item1['Codigo']);
                    if (array_key_exists($i, $dataPagoPedido)) {
                        $dataPagosP["Pedidos"] = $i;
                        $cuota = 0;
                        $abonado = 0;
                        $f2 = "2018-01-01 00:00:00";
                        foreach ($dataPagoPedido[$i] as $item) {
                            $cuota ++;
                            $abonado = $abonado + $item["Pago"];
                            $f1 = $item["FechaPago"];
                            if ($f1 > $f2) {
                                $f2 = $f1;
                            }
                        }
                        $dataPagosP["cuota"] = $cuota;
                        $dataPagosP["abonado"] = $abonado;
                        $dataPagosP["valor"] = $item1["Valor"];
                        $dataPagosP["saldo"] = intval($item1["Valor"]) - intval($abonado);
                        $dataPagosP["fechaUltimoPago"] = $f2;
                        $ultimoPago = 0;

                        foreach ($dataPagoPedido[$i] as $item) {
                            if ($item["FechaPago"] == $f2) {
                                if ($item["Pago"] > $ultimoPago) {
                                    $ultimoPago = $item["Pago"];
                                }
                            }
                        }
                        $dataPagosP["UltimoPago"] = $ultimoPago;
                        $dataSaldo = $this->calcularSaldoMinimo($item1["Valor"], $item1["DiaCobro"], $item1["ValCuota"]);
                        $dataPagosP["PagoMin"] = $dataSaldo["PagoMin"];
                        $dataPagosP["DiasDiferencia"] = $dataSaldo["DiasDiferencia"];

                        $datosPagos[$i] = $dataPagosP;
                    } else {
                        $dataPagosP["Pedidos"] = $i;
                        $dataPagosP["cuota"] = "0";
                        $dataPagosP["abonado"] = "0";
                        $dataPagosP["valor"] = $item1["Valor"];
                        $dataPagosP["saldo"] = intval($item1["Valor"]);
                        $dataPagosP["fechaUltimoPago"] = "--";
                        $ultimoPago = 0;
                        $dataPagosP["UltimoPago"] = 0;
                        $dataSaldo = $this->calcularSaldoMinimo($item1["Valor"], $item1["DiaCobro"], $item1["ValCuota"]);
                        $dataPagosP["PagoMin"] = $dataSaldo["PagoMin"];
                        $dataPagosP["DiasDiferencia"] = $dataSaldo["DiasDiferencia"];

                        $datosPagos[$i] = $dataPagosP;
                    }
                }



                $data = new stdClass();
                $data->Controller = "Pagos";
                $data->title = "Pagar Mora";
                $data->subtitle = "Programar Pago a Cliente";
                $data->contenido = $this->viewControl . '/PagarMora';
                $data->cliente = $cliente;
                $data->ListaDatos = $dataPedido;
                $data->ListaDatos2 = $dataClientes;
                $data->ListaDatos3 = $datosPagos;

                $this->load->view('frontend', $data);
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>$cliente</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se encontraron pedidos del Cliente: <b>$cliente</b>");
            redirect(base_url("/Clientes/Admin/"));
        }
    }

    public function calcularSaldoMinimo($valorTotal, $DiaCobro, $cuota)
    {
        $fechaPago = date("Y-m-d", strtotime($DiaCobro));
        $fecha = date("Y-m-d");
        $interval = date_diff(date_create($fechaPago), date_create($fecha));
        $diferencia = intval($interval->format('%a'));
        $signo = $interval->format('%R');
        if ($signo == "+") {
            $mensualidad = 0;
            $dias = 0;
            if ($diferencia > 30) {
                $mensualidad = intval($diferencia / 30);
                $dias = $diferencia - ($mensualidad * 30);
            }
            $pagodia = intval($cuota / 30) * $dias;
            $pagomes = intval($cuota * $mensualidad);
            $pagomin = $pagomes + $pagodia;
            $pagomin = ceil($pagomin / 1000) * 1000;
        } else {
            $pagomin = 0;
            $diferencia = 0;
        }
        $dataSaldo = array(
            "PagoMin" => $pagomin,
            "DiasDiferencia" => $diferencia
        );

        return $dataSaldo;
    }

    public function AdminProg()
    {
        $dataUsuarios = $this->Usuarios_model->obtenerUsuariosEP();
        $fecha = date("Y-m-d");
        $user = "*";
        //$num = $this->numPagosProgramados($user, date("Y-m-d 00:00:00"), date("Y-m-d 23:59:59"));
        $dataCobradores = $this->Cobradores_model->obtenerCobradores();
        if (isset($dataCobradores) && $dataCobradores != false) {
            $data = new stdClass();
            $data->Controller = "Pagos";
            $data->title = "Recibos de Pago";
            $data->subtitle = "Recibos de Pago Filtro";
            $data->contenido = $this->viewControl . '/AdminProg';
            $data->ListaUsuarios = $dataUsuarios;
            $data->Lista1 = $dataCobradores;

            $this->load->view('frontend', $data);
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos de los Cobradores, por favor intentelo de nuevo.");
            redirect(base_url("/Cobradores/Admin/"));
        }
    }

    public function numPagosProgramados($user = "*", $fechaIni = "", $fechaFin = "")
    {
        if ($user == "") {
            $user = "*";
        }
        if ($fechaIni == "") {
            $fechaIni = date('Y-m-d') . " 00:00:00";
        }
        if ($fechaFin == "") {
            $fechaFin = date('Y-m-d') . " 23:59:59";
        }
        try {
            $dataProgramados = $this->Pagos_model->obtenerPagosProgramaFechaUser($user, $fechaIni, $fechaFin);
            return count($dataProgramados);
        } catch (Exception $e) {
            return 'Error';
        }
    }

    public function pagosProgramados()
    {
        $user = "*";
        $fechaIni = date('Y-m-d') . " 00:00:00";
        $fechaFin = date('Y-m-d') . " 23:59:59";


        try {
            $dataProgramados = $this->Pagos_model->obtenerPagosProgramaFechaUser($user, $fechaIni, $fechaFin);
            $arreglo["data"] = [];
            if (isset($dataProgramados) && $dataProgramados != false) {
                $i = 0;
                foreach ($dataProgramados as $item) {
                    $pedidosClientes = $this->Pedidos_model->obtenerClientePorPedido($item["Pedido"]);
                    $btn1 = "<a href='" . base_url() . "Pagos/Validar/" . $item['Codigo'] . "/' target='_blank' title='Ver Recibo de Pago'><i class='fa fa-search' aria-hidden='true' style='padding:5px;'></i></a>";
                    $btn2 = "";
                    $btn3 = "";
                    $btn4 = "";

                    if ($item["NomEstado"] == "Programado") {
                        $newline = '\n';
                        $item["Observaciones"] = str_replace("\n", $newline, $item["Observaciones"]);
                        $saldo = intval($pedidosClientes[0]["Saldo"]) - intval($item["Cuota"]);
                        $btn2 = html_entity_decode("<a href='#ModalConfirmarPago' data-toggle='modal' onclick='dataModalConfirmar(\"" . $item['Codigo'] . "\", \"" . money_format("%.0n", $item["Cuota"]) . "\", \"" . money_format("%.0n", $saldo) . "\", \"" . $item["Valor"] . "\", \"" . $pedidosClientes[0]['Cliente'] . "\", \"" . $item["Pedido"] . "\", \"" . $pedidosClientes[0]['Nombre'] . "\", \"" . $item["Observaciones"] . "\");' title='Confirmar Pago'><i class='fa fa-check' aria-hidden='true' style='padding:5px;'></i></a>");
                        $btn3 = html_entity_decode("<a href='#ModalDescartarPago' data-toggle='modal' onclick='dataModalDescartar(\"" . $item['Codigo'] . "\", \"" . $item["Pedido"] . "\", \"" . $pedidosClientes[0]['Cliente'] . "\", \"" . $pedidosClientes[0]['Nombre'] . "\", \"" . money_format("%.0n", $item["Cuota"]) . "\", \"" . money_format("%.0n", $pedidosClientes[0]["Saldo"]) . "\", \"" . $item["Valor"] . "\", \"" . $item["Observaciones"] . "\");' title='Descartar Pago'><i class='fa fa-close' aria-hidden='true' style='padding:5px;'></i></a>");
                        $f1 = strtotime($item["FechaImpresion"]);
                        $f2 = strtotime(date("d-m-Y 00:00:00", time()));

                        if ($item["Copias"] == 0) {
                            $btn4 = "<a href='#ModalPrintSolo' data-toggle='modal' title='Imprimir Recibo de Pago' onclick='dataModalSolo(\"1\",\"" . money_format("%.0n", $item["Cuota"]) . "\", \"" . $item['Codigo'] . "\", \"" . $item["Pedido"] . "\");'><i class='fa fa-print' aria-hidden='true' style='padding:5px;'></i></a>";
                        } else {
                            if (($item["Copias"] > 1 && $item["Copias"] < 3) && $f1 != $f2) {
                                $btn4 = "<a href='#ModalPrintSolo' data-toggle='modal' title='Imprimir Recibo de Pago' onclick='dataModalSolo(\"1\",\"" . money_format("%.0n", $item["Cuota"]) . "\", \"" . $item['Codigo'] . "\", \"" . $item["Pedido"] . "\");'><i class='fa fa-print' aria-hidden='true' style='padding:5px;'></i></a>";
                            }
                        }
                    }

                    if (strlen($item["Observaciones"]) > 30) {
                        $osb = substr($item["Observaciones"], 0, 30) . " (...)";
                    } else {
                        $osb = $item["Observaciones"];
                    }

                    $direccion = $pedidosClientes["0"]["Dir"];
                    $direccion = ($pedidosClientes["0"]["Etapa"] != "") ? $direccion . " ET " . $pedidosClientes["0"]["Etapa"] : $direccion;
                    $direccion = ($pedidosClientes["0"]["Torre"] != "") ? $direccion . " TO " . $pedidosClientes["0"]["Torre"] : $direccion;
                    $direccion = ($pedidosClientes["0"]["Apartamento"] != "") ? $direccion . " AP " . $pedidosClientes["0"]["Apartamento"] : $direccion;
                    $direccion = ($pedidosClientes["0"]["Manzana"] != "") ? $direccion . " MZ " . $pedidosClientes["0"]["Manzana"] : $direccion;
                    $direccion = ($pedidosClientes["0"]["Interior"] != "") ? $direccion . " IN " . $pedidosClientes["0"]["Interior"] : $direccion;
                    $direccion = ($pedidosClientes["0"]["Casa"] != "") ? $direccion . " CA " . $pedidosClientes["0"]["Casa"] : $direccion;
                    $direccion = $direccion . "-- Ubicación: " . $pedidosClientes["0"]["PaginaFisica"];
                    $telefono = $pedidosClientes["0"]["Telefono1"];
                    $telefono = ($pedidosClientes["0"]["Telefono2"] != "") ? $telefono . " - " . $pedidosClientes["0"]["Telefono2"] : $telefono;
                    $num = $this->Pagos_model->ultimaCuota($item["Pedido"]);
                    $numCuotas = $num[0]["Cuota"] + 1;

                    $arreglo["data"][$i] = array(
                        "pedido" => $pedidosClientes[0]["Nombre"],
                        "numCuota" => $numCuotas,
                        "saldo" => money_format("%.0n", $pedidosClientes["0"]["Saldo"]),
                        "cuota" => money_format("%.0n", $item["Cuota"]),
                        "fecha" => date("d/m/Y", strtotime($item["FechaProgramada"])),
                        "estado" => $item["NomEstado"],
                        "direccion" => $direccion,
                        "btn" => '<div class="btn-group text-center" style="margin: 0px auto;  width:100%;">' . $btn1 . $btn2 . $btn3 . $btn4 . '</div>'
                    );
                    $i++;
                }
            }
            echo json_encode($arreglo);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . "<br>";
        }
    }

    public function FiltroProg()
    {
        $user = trim($this->input->post('pag_usu'));
        $fechaIni = trim($this->input->post('pag_fec1'));
        $date = str_replace('/', '-', $fechaIni);
        $fechaIni = date('Y-m-d', strtotime($date)) . " 00:00:00";
        $fechaFin = trim($this->input->post('pag_fec2'));
        $date = str_replace('/', '-', $fechaFin);
        $fechaFin = date("Y-m-d", strtotime($date)) . " 23:59:59";

        try {
            $dataProgramados = $this->Pagos_model->obtenerPagosProgramaFechaUser($user, $fechaIni, $fechaFin);
            $arreglo["data"] = [];
            if (isset($dataProgramados) && $dataProgramados != false) {
                $i = 0;
                foreach ($dataProgramados as $item) {
                    $pedidosClientes = $this->Pedidos_model->obtenerClientePorPedido($item["Pedido"]);
                    $btn1 = "<a href='" . base_url() . "Pagos/Validar/" . $item['Codigo'] . "/' target='_blank' title='Ver Recibo de Pago'><i class='fa fa-search' aria-hidden='true' style='padding:5px;'></i></a>";
                    $btn2 = "";
                    $btn3 = "";
                    $btn4 = "";
                    if ($item["NomEstado"] == "Programado") {
                        $saldo = intval($pedidosClientes[0]["Saldo"]) - intval($item["Cuota"]);
                        $newline = '\n';
                        $item["Observaciones"] = str_replace("\n", $newline, $item["Observaciones"]);
                        $btn2 = html_entity_decode("<a href='#ModalConfirmarPago' data-toggle='modal' onclick='dataModalConfirmar(\"" . $item['Codigo'] . "\", \"" . money_format("%.0n", $item["Cuota"]) . "\", \"" . money_format("%.0n", $saldo) . "\", \"" . $item["Valor"] . "\", \"" . $pedidosClientes[0]['Cliente'] . "\", \"" . $item["Pedido"] . "\", \"" . $pedidosClientes[0]['Nombre'] . "\", \"" . $item["Observaciones"] . "\");' title='Confirmar Pago'><i class='fa fa-check' aria-hidden='true' style='padding:5px;'></i></a>");
                        $btn3 = html_entity_decode("<a href='#ModalDescartarPago' data-toggle='modal' onclick='dataModalDescartar(\"" . $item['Codigo'] . "\", \"" . $item["Pedido"] . "\", \"" . $pedidosClientes[0]['Cliente'] . "\", \"" . $pedidosClientes[0]['Nombre'] . "\", \"" . money_format("%.0n", $item["Cuota"]) . "\", \"" . money_format("%.0n", $pedidosClientes[0]["Saldo"]) . "\", \"" . $item["Valor"] . "\", \"" . $item["Observaciones"] . "\");' title='Descartar Pago'><i class='fa fa-close' aria-hidden='true' style='padding:5px;'></i></a>");
                        $f1 = strtotime($item["FechaImpresion"]);
                        $f2 = strtotime(date("d-m-Y 00:00:00", time()));

                        if ($item["Copias"] == 0) {
                            $btn4 = "<a href='#ModalPrintSolo' data-toggle='modal' title='Imprimir Recibo de Pago' onclick='dataModalSolo(\"1\",\"" . money_format("%.0n", $item["Cuota"]) . "\", \"" . $item['Codigo'] . "\", \"" . $item["Pedido"] . "\");'><i class='fa fa-print' aria-hidden='true' style='padding:5px;'></i></a>";
                        } else {
                            if (($item["Copias"] > 1 && $item["Copias"] < 3) && $f1 != $f2) {
                                $btn4 = "<a href='#ModalPrintSolo' data-toggle='modal' title='Imprimir Recibo de Pago' onclick='dataModalSolo(\"1\",\"" . money_format("%.0n", $item["Cuota"]) . "\", \"" . $item['Codigo'] . "\", \"" . $item["Pedido"] . "\");'><i class='fa fa-print' aria-hidden='true' style='padding:5px;'></i></a>";
                            }
                        }
                    }

                    if (strlen($item["Observaciones"]) > 50) {
                        $osb = substr($item["Observaciones"], 0, 50) . " (...)";
                    } else {
                        $osb = $item["Observaciones"];
                    }

                    $pedidosClientes = $this->Pedidos_model->obtenerClientePorPedido($item["Pedido"]);
                    $direccion = $pedidosClientes["0"]["Dir"];
                    $direccion = ($pedidosClientes["0"]["Etapa"] != "") ? $direccion . " ET " . $pedidosClientes["0"]["Etapa"] : $direccion;
                    $direccion = ($pedidosClientes["0"]["Torre"] != "") ? $direccion . " TO " . $pedidosClientes["0"]["Torre"] : $direccion;
                    $direccion = ($pedidosClientes["0"]["Apartamento"] != "") ? $direccion . " AP " . $pedidosClientes["0"]["Apartamento"] : $direccion;
                    $direccion = ($pedidosClientes["0"]["Manzana"] != "") ? $direccion . " MZ " . $pedidosClientes["0"]["Manzana"] : $direccion;
                    $direccion = ($pedidosClientes["0"]["Interior"] != "") ? $direccion . " IN " . $pedidosClientes["0"]["Interior"] : $direccion;
                    $direccion = ($pedidosClientes["0"]["Casa"] != "") ? $direccion . " CA " . $pedidosClientes["0"]["Casa"] : $direccion;
                    $direccion = $direccion . "-- Ubicación: " . $pedidosClientes["0"]["PaginaFisica"];
                    $telefono = $pedidosClientes["0"]["Telefono1"];
                    $telefono = ($pedidosClientes["0"]["Telefono2"] != "") ? $telefono . " - " . $pedidosClientes["0"]["Telefono2"] : $telefono;
                    $num = $this->Pagos_model->ultimaCuota($item["Pedido"]);
                    $numCuotas = $num[0]["Cuota"] + 1;

                    $arreglo["data"][$i] = array(
                        "pedido" => $pedidosClientes[0]["Nombre"],
                        "numCuota" => $numCuotas,
                        "saldo" => money_format("%.0n", $pedidosClientes["0"]["Saldo"]),
                        "cuota" => money_format("%.0n", $item["Cuota"]),
                        "fecha" => date("d/m/Y", strtotime($item["FechaProgramada"])),
                        "estado" => $item["NomEstado"],
                        "direccion" => $direccion,
                        "btn" => '<div class="btn-group text-center" style="margin: 0px auto;  width:100%;">' . $btn1 . $btn2 . $btn3 . $btn4 . '</div>'
                    );
                    $i++;
                }
            }
            echo json_encode($arreglo);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . "<br>";
        }
    }

    public function CartaData($pedido)
    {
        // Include the main TCPDF library (search for installation path).
        $this->load->library('EdiPdf');




        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Ediciones Católicas');
        $pdf->SetTitle('Notificación DataCrédito');
        $pdf->SetSubject('Carta Prejuridico');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------
        // set font
        $pdf->SetFont('times', 'BI', 20);

        // add a page
        $pdf->AddPage();

        // set some text to print
        $txt = "Esta es la carta de notificación a datacrédito (Solicitar formato y texto)";

        // print a block of text using Write()
        $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

        // ---------------------------------------------------------
        //Close and output PDF document
        $pdf->Output('Notificacion_DataCredito.pdf', 'I');
    }

    public function ImprimirRecibosPP()
    {
        $user = trim($this->input->post('pag_usu'));
        $fechaIni = trim($this->input->post('pag_fec1'));
        $date = str_replace('/', '-', $fechaIni);
        $fechaIni = date('Y-m-d', strtotime($date));
        $fechaFin = trim($this->input->post('pag_fec2'));
        $date = str_replace('/', '-', $fechaFin);
        $fechaFin = date("Y-m-d", strtotime($date));

        try {
            $dataProgramados = $this->Pagos_model->obtenerPagosProgramaFechaUser($user, $fechaIni, $fechaFin);
            if (isset($dataProgramados) && $dataProgramados != false) {
                $this->session->set_flashdata("dataProgramados", $dataProgramados);
                echo 1;
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . "<br>";
        }
    }

    public function ImprimirReciboSolo($pedido, $pagoProg, $margen)
    {
        if ($pedido != null && $pedido != "") {
            if ($pedido != null && $pedido != "") {
                $dataProgramados = $this->Pagos_model->obtenerPagosProgramaPedidoProg($pedido, $pagoProg);
                if (isset($dataProgramados) && $dataProgramados != false) {
                    $p = $this->Pedidos_model->obtenerPedido($pedido);
                    $pagina = $p[0]["PaginaFisica"];

                    //$copias = $this->agregarCopia($dataProgramados[0]["Codigo"]);
                    $copias = true;
                    echo $htmlEncabezado = '<!doctype html>
                            <html lang="es-co">
                                    <head>
                                        <meta charset="utf-8">
                                        <meta name="viewport" content="width=device-width,initial-scale=1">
                                        <title>Recibos de Pago - </title>
                                        <link rel="icon" type="image/png" href="' . base_url() . 'Public/images/logo/01.png"/>
                                    </head>
                                    <body>';
//                    var_dump($copias);
                    if ($copias != false) {
                        $num = $this->Pagos_model->ultimaCuota($pedido);
                        $numCuotas = $num[0]["Cuota"] + 1;
                        $dataProgramados[0]["numCuotas"] = $numCuotas;
                        $dataCliente = $this->Clientes_model->obtenerClienteDir($dataProgramados[0]["Cliente"]);
                        $user = $this->session->userdata('Usuario');
                        $dataAdmin = $this->Usuarios_model->obtenerAdminPorUsuario($user);

                        $sub = $this->config->item('subdominio');
                        if ($sub == "Plataforma") {
                            $html = $this->RecibosSoloInfoMundoKtolico($dataProgramados[0], $dataCliente[0], $dataAdmin, $pagina, $margen);
                        } elseif ($sub == "nelson") {
                            $html = $this->RecibosSoloInfoNelson($dataProgramados[0], $dataCliente[0], $dataAdmin, $pagina, $margen);
                        } elseif ($sub == "jose") {
                            echo '<script>alert("No tiene un formato configurado. Por favor comuniquese con su administrador");  window.open("","_parent",""); window.close(); </script>';
                        //$html = $this->RecibosSoloInfoJose($dataProgramados[0], $dataCliente[0], $dataAdmin, $pagina, $margen);
                        } elseif ($sub == "mundoktolico") {
                            $html = $this->RecibosSoloInfoMundoKtolico($dataProgramados[0], $dataCliente[0], $dataAdmin, $pagina, $margen);
                        } else {
                            echo '<script>alert("No tiene un formato configurado. Por favor comuniquese con su administrador");  window.open("","_parent",""); window.close(); </script>';
                            //$html = $this->TablaRecibo1($dataProgramados[0], $dataCliente[0], $dataAdmin, $pagina, $margen);
                        }
                        echo $html;
                    } else {
                        echo '<script>alert("Ya se imprimió el este recibo el día de hoy. Si desea reimprimirlos, debe comunicarse con el Administrador.");  window.open("","_parent",""); window.opener.location.reload(false); window.close(); </script>';
                    }
                    echo $htmlPie = '</body>
                                </html>';
                } else {
                    redirect(base_url("/Pagos/AdminProg/"));
                }
            } else {
                redirect(base_url("/Pagos/AdminProg/"));
            }
        } else {
            redirect(base_url("/Pagos/AdminProg/"));
        }
    }

    public function ImprimirRecibos($margen)
    {
        $data = $this->session->flashdata("dataProgramados");
        if ($data != null) {
            $i = 0;
            echo $htmlEncabezado = '<!doctype html>
                    <html lang="es-co">
                            <head>
                                <meta charset="utf-8">
                                <meta name="viewport" content="width=device-width,initial-scale=1">
                                <title>Recibos de Pago - </title>
                                <link rel="icon" type="image/png" href="' . base_url() . 'Public/images/logo/01.png"/>
                            </head>
                            <body>';
            $numRecibos = 0;
            foreach ($data as $value) {
                //$copias = $this->agregarCopia($value["Codigo"]);
                $copias = true;
                if ($copias != false) {
                    if ($numRecibos > 0) {
                        $saltoNuevoRecibo = "
                        <br>
                        <br>
                        <br>
                        ";
                        if ($sub == "Plataforma") {
                            echo "<p style='margin-bottom: 5px;'>.</p>";
                        } elseif ($sub == "nelson") {
                            echo "<p style='margin-bottom: -10px;'>.</p>";
                        } elseif ($sub == "mundoktolico") {
                            echo "<p style='margin-bottom: -5px;'>.</p>";
                        } elseif ($sub == "jose") {
                            echo "<p style='margin-bottom: -5px;'>.</p>";
                        }

                        echo $saltoNuevoRecibo;
                    }
                    $numRecibos ++;
                    $pagina = $data[$i]["Pagina"];

                    $num = $this->Pagos_model->ultimaCuota($value["Pedido"]);
                    $numCuotas = $num[0]["Cuota"] + 1;

                    $data[$i]["numCuotas"] = $numCuotas;
                    $dataCliente = $this->Clientes_model->obtenerClienteDir($value["Cliente"]);
                    $user = $this->session->userdata('Usuario');
                    $dataAdmin = $this->Usuarios_model->obtenerAdminPorUsuario($user);

                    $sub = $this->config->item('subdominio');
                    $html = "";
                    if ($sub == "Plataforma") {
                        $html = $this->RecibosSoloInfoMundoKtolico($data[$i], $dataCliente[0], $dataAdmin, $pagina, $margen);
                    } elseif ($sub == "nelson") {
                        $html = $this->RecibosSoloInfoNelson($data[$i], $dataCliente[0], $dataAdmin, $pagina, $margen);
                    } elseif ($sub == "jose") {
                        echo '<script>alert("No tiene un formato configurado. Por favor comuniquese con su administrador");  window.open("","_parent",""); window.close(); </script>';
                    //$html = $this->RecibosSoloInfoJose($data[$i], $dataCliente[0], $dataAdmin, $pagina, $margen);
                    } elseif ($sub == "mundoktolico") {
                        $html = $this->RecibosSoloInfoMundoKtolico($data[$i], $dataCliente[0], $dataAdmin, $pagina, $margen);
                    } else {
                        echo '<script>alert("No tiene un formato configurado. Por favor comuniquese con su administrador");  window.open("","_parent",""); window.close(); </script>';
                        //$html = $this->TablaRecibo1($data[$i], $dataCliente[0], $dataAdmin);
                    }
                    echo $html;

                    $i++;
                }
            }
            if ($numRecibos <= 0) {
                echo '<script>alert("Ya se imprimieron los recibos del día. Si desea reimprimirlos, debe comunicarse con el Administrador.");  window.open("","_parent",""); window.close(); </script>';
            }
            echo $htmlPie = '</body>
                    </html>';
        } else {
            redirect(base_url("/Pagos/AdminProg/"));
        }
    }

    public function agregarCopia($codigo)
    {
        if ($codigo != "") {
            $PagoPro = $this->Pagos_model->numCopias($codigo);
            if ($PagoPro != false) {
                $num = $PagoPro[0]["Copias"];
                if ($num < 3) {
                    $f1 = strtotime($PagoPro[0]["FechaImpresion"]);
                    $f2 = strtotime(date("d-m-Y 00:00:00", time()));

                    if ($f1 != $f2) {
                        $this->addCopia($codigo, $num);
                        return true;
                    } else {
                        $perfil = $this->session->userdata('PerfilId');
                        $coordi = $this->session->userdata('Coordi');
                        if ($perfil == $coordi) {
                            $this->addCopia($codigo, $num);
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function addCopia($codigo, $num)
    {
        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha = date("Y-m-d H:i:s");
        $fechaImpresion = date("Y-m-d");

        $dataPagoPro = array(
            "Copias" => $num + 1,
            "FechaImpresion" => $fechaImpresion,
            "UsuarioModificacion" => $user,
            "FechaModificacion" => $fecha
        );
 
        $pago = $this->Pagos_model->obtenerPagosProgramaCod($codigo);
        $pedido = $pago[0]["Pedido"];
        $dataOriginal = $pago[0];
        $dataNew = compararCambiosLog($dataOriginal, $dataPagoPro);
        if ($this->Pagos_model->updateProg($codigo, $dataNew)) {            
            $dataPedido = $this->Pedidos_model->obtenerPedido($pedido);
            $modulo = "Pagar Pedido";
            $accion = "Imprimir Recibo de Pago";
            $tabla = "PagosProgramados";
            $llave = $codigo;
            $cliente_log = $pedido[0]["Cliente"];
            $enlace = "Pagos|Programados|" . $pedido;
            $observaciones = "";
            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
        }
    }

    public function RecibosSoloInfoNelson($data, $cliente, $dataAdmin, $pagina, $margen)
    {
        setlocale(LC_ALL, "es_CO");
        $numeroRecibo = $data["Codigo"];
        $numeroRecibo = str_pad($numeroRecibo, 6, "0", STR_PAD_LEFT);
        $fecha = strftime("%d de %B del %Y", strtotime($data["FechaProgramada"])) . ' al ' . strftime("%d de %B del %Y", strtotime($data["FechaProgramada"] . "+ 8 days"));
        $nombreCliente = $cliente["Nombre"];
        $direccionCliente = $cliente["Dir"] . " Bar: " . $cliente["Barrio"];
        $etapa = $cliente["Etapa"];
        $torre = $cliente["Torre"];
        $apartamento = $cliente["Apartamento"];
        $manzana = $cliente["Manzana"];
        $interior = $cliente["Interior"];
        $casa = $cliente["Casa"];


        $valor = money_format("%.0n", $data["Valor"]);
        $abono = money_format("%.0n", $data["Cuota"]);
        $saldo = money_format("%.0n", $data["Saldo"] - $data["Cuota"]);
        $cuota = $data["numCuotas"];
        $observaciones = $data["Observaciones"];

        if ($margen == 1) {
            $margen = "margin-top: -3px;";
        } elseif ($margen == 2) {
            $margen = "margin-top: -5px;";
        } elseif ($margen == 3) {
            $margen = "margin-top: 3px;";
        } elseif ($margen == 4) {
            $margen = "margin-top: 5px;";
        } else {
            $margen = "";
        }

        $html = "
            <style>
                * {
                    color:#000;
                }
                p {
                    margin:0px;
                    padding:0px;
                }
                .letraPeque{
                    min-height: 40px; 
                    max-height: 40px; 
                    font-size: 13px; 
                    line-height: 80%;
                }
            </style>
            <p style='margin-left: 430px;font-weight: bold; margin-top: -2;'>" . $pagina . "</p>
            <br />
            <br />
            <div style='width: 490px;" . $margen . "'>
                <p style='margin-left: 400px;color: red;'>" . $numeroRecibo . "</p>
                <br />
                <br />
                <p style='margin-left: 110px; margin-top: -6px; '>" . $fecha . "</p>
                <p style='margin-left: 125px; margin-top: 3px; '>" . $nombreCliente . "</p>
                <p style='margin-left: 125px; margin-top: 0px; width: 215px; display: block; float: left;' class='letraPeque'>" . $direccionCliente . "</p>
                <p style='margin-left: 30px; margin-top: 0px; width: 115px; display: block; float: left;' class='letraPeque'>" . $cliente["Telefono1"] . ' - ' . $cliente["Telefono2"] . "</p>
                <br />
                <br />
                <br />
                <div style='display: block; margin: 10px 0px -5px;'>
                    <label style='margin-left: 30px;'>" . $etapa . "</label>
                    <label style='margin-left: 60px;'>" . $torre . "</label>
                    <label style='margin-left: 60px;'>" . $apartamento . "</label>
                    <label style='margin-left: 75px;'>" . $manzana . "</label>
                    <label style='margin-left: 75px;'>" . $interior . "</label>
                    <label style='margin-left: 60px;'>" . $casa . "</label>
                </div>
                <p style='margin-left: 100px; display: block; float: left;'>" . $valor . "</p>
                <br>
                <p style='margin-left: 100px; display: block; float: left;'>" . $abono . "</p>
                <p style='margin-left: 220px; display: block; float: left;'>" . $cuota . "</p>
                <br>
                <p style='margin-left: 100px; display: block; float: left;'>" . $saldo . "</p>
                <br>
                <br>
                <p style='margin-left: 30px; display: block; margin-top: 0px; width: auto;' class='letraPeque'>" . $observaciones . "</p>
            </div>
            ";

        return $html;
    }

    public function RecibosSoloInfoMundoKtolico($data, $cliente, $dataAdmin, $pagina, $margen)
    {
        setlocale(LC_ALL, "es_CO");
        $numeroRecibo = $data["Codigo"];
        $numeroRecibo = str_pad($numeroRecibo, 6, "0", STR_PAD_LEFT);
        $fecha = strftime("%d de %B del %Y", strtotime($data["FechaProgramada"])) . ' al ' . strftime("%d de %B del %Y", strtotime($data["FechaProgramada"] . "+ 8 days"));
        $nombreCliente = $cliente["Nombre"];
        $direccionCliente = $cliente["Dir"] . " Bar: " . $cliente["Barrio"];
        $etapa = $cliente["Etapa"];
        $torre = $cliente["Torre"];
        $apartamento = $cliente["Apartamento"];
        $manzana = $cliente["Manzana"];
        $interior = $cliente["Interior"];
        $casa = $cliente["Casa"];


        $valor = money_format("%.0n", $data["Valor"]);
        $abono = money_format("%.0n", $data["Cuota"]);
        $saldo = money_format("%.0n", $data["Saldo"] - $data["Cuota"]);
        $cuota = $data["numCuotas"];
        $observaciones = $data["Observaciones"];

        if ($margen == 1) {
            $margen = "margin-top: -3px;";
        } elseif ($margen == 2) {
            $margen = "margin-top: -5px;";
        } elseif ($margen == 3) {
            $margen = "margin-top: 3px;";
        } elseif ($margen == 4) {
            $margen = "margin-top: 5px;";
        } else {
            $margen = "";
        }

        $html = "
            <style>
                * {
                    color:#000;
                }
                p {
                    margin:0px;
                    padding:0px;
                }
                .letraPeque{
                    min-height: 40px; 
                    max-height: 40px; 
                    font-size: 13px; 
                    line-height: 80%;
                }
            </style>
            <br />
            <br />
            <br />
            <div style='width: 490px;" . $margen . "'>
                <br />
                <br />
                <br />
                <p style='margin-left: 110px; margin-top: -10px; '>" . $fecha . "</p>
                <p style='margin-left: 125px; margin-top: 2px; '>" . $nombreCliente . "</p>
                <p style='margin-left: 125px; margin-top: 1px; width: 330px; display: block; float: left;' class='letraPeque'>" . $direccionCliente . "</p>
                <br />
                <br />
                <div style='display: block; margin: 13px 0px 0px;'>
                    <label style='margin-left: 18px;'>" . $etapa . "</label>
                    <label style='margin-left: 3px;'>" . $torre . "</label>
                    <label style='margin-left: 60px;'>" . $apartamento . "</label>
                    <label style='margin-left: 70px;'>" . $manzana . "</label>
                    <label style='margin-left: 70px;'>" . $interior . "</label>
                    <label style='margin-left: 55px;'>" . $casa . "</label>
                </div>
                <p style='margin-left: 100px; margin-top: 0px; width: 190px; display: block; float: left;'>" . $valor . "</p>
                <p style='margin-left: 30px; margin-top: 0px; width: 115px; display: block; float: left;'>" . $abono . "</p>
                <br>
                <p style='margin-left: 100px; display: block; float: left;'>" . $saldo . "</p>
                <br>
                <br>
                <br>
                <p style='margin-left: 30px; display: block; margin-top: -7px; width: auto;' class='letraPeque'>" . $observaciones . "</p>
                <p style='margin-left: 30px; margin-top: 0px; width: 315px; display: block; float: left;'>" . $cliente["Telefono1"] . ' - ' . $cliente["Telefono2"] . "</p>                              
            </div>
            ";

        return $html;
    }

    public function RecibosSoloInfoJose($data, $cliente, $dataAdmin, $pagina, $margen)
    {
        setlocale(LC_ALL, "es_CO");
        $numeroRecibo = $data["Codigo"];
        $numeroRecibo = str_pad($numeroRecibo, 6, "0", STR_PAD_LEFT);
        $fecha = strftime("%d de %B del %Y", strtotime($data["FechaProgramada"])) . ' al ' . strftime("%d de %B del %Y", strtotime($data["FechaProgramada"] . "+ 8 days"));
        $nombreCliente = $cliente["Nombre"];
        $direccionCliente = $cliente["Dir"] . " Bar: " . $cliente["Barrio"];
        $etapa = $cliente["Etapa"];
        $torre = $cliente["Torre"];
        $apartamento = $cliente["Apartamento"];
        $manzana = $cliente["Manzana"];
        $interior = $cliente["Interior"];
        $casa = $cliente["Casa"];


        $valor = money_format("%.0n", $data["Valor"]);
        $abono = money_format("%.0n", $data["Cuota"]);
        $saldo = money_format("%.0n", $data["Saldo"] - $data["Cuota"]);
        $cuota = $data["numCuotas"];
        $observaciones = $data["Observaciones"];

        if ($margen == 1) {
            $margen = "margin-top: -3px;";
        } elseif ($margen == 2) {
            $margen = "margin-top: -5px;";
        } elseif ($margen == 3) {
            $margen = "margin-top: 3px;";
        } elseif ($margen == 4) {
            $margen = "margin-top: 5px;";
        } else {
            $margen = "";
        }
    }

    public function TablaRecibo1($data, $cliente, $dataAdmin)
    {
        if ($data["Estado"] == 116) {
            $tel3 = "";
            if ($dataAdmin[0]["Telefono3"] != "") {
                $tel3 = " - " . $dataAdmin[0]["Telefono3"];
            }

            $html = '<style>@font-face{font-family:"Yesteryear";src: url("' . base_url('Public') . '/assets/fonts/Yesteryear-Regular.ttf");}
            *{color:#2196f3;}p{margin:0px;padding:0px;color:black;}</style>
        <table style="border:1px solid;border-radius:5px;margin-left:30px;margin-bottom:20px;">
            <tr>
                <td>
                    <table style="border:none;margin:0px auto;">
                        <tr>
                            <td style="border:none;" rowspan="2">
                                <img src="' . base_url() . '/Public/images/logo/01.png" width="70">
                            </td>
                            <td style="border:none;vertical-align:middle;text-align:center;">
                                <img src="' . base_url() . '/Public/images/logo/02.png" width="70" style="">
                                <img src="' . base_url() . '/Public/images/logo/03.png" width="70" style="">
                            </td>
                            <td rowspan="2">&nbsp;&nbsp;</td>
                            <td style="border:none;vertical-align:top;">
                                <p style="text-align:center;color:#2196f3;font-family:Yesteryear;font-size:30px;">
                                    ' . $dataAdmin[0]["Nombre"] . '
                                </p>
                            </td>
                            <td style="border:none;" rowspan="3">
                                <img src="' . base_url() . '/Public/images/logo/07.png" width="85" >                    
                            </td>
                        </tr>
                        <tr>
                            <td style="border:none;">
                                <p style="text-align:center;color:#2196f3;font-family:Yesteryear;font-size:30px;">
                                   ' . $this->config->item('NAME_CEO') . '                                  
                                </p>
                            </td>
                            <td rowspan="2" style="border:none;vertical-align:top;">
                                <p style="text-align:center;color:#2196f3;">
                                    ' . strtoupper($dataAdmin[0]["Cargo"]) . '
                                </p>
                                <p style="text-align:center;color:#2196f3;">
                                    Teléfonos: ' . $dataAdmin[0]["Telefono1"] . '
                                </p>
                                <p style="text-align:center;color:#2196f3;">
                                    ' . $dataAdmin[0]["Telefono2"] . $tel3 . '
                                </p>
                            </td>
                        </tr>                        
                        <tr>
                            <td style="border:none;" colspan="3">
                                <p style="text-align:left;color:#2196f3;">
                                    ' . $this->config->item('NIT_COMPANY') . '  ' . $this->config->item('REG_COMPANY') . '
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table style="border:2px solid #2196f3;border-radius:10px;width:600px;">
                        <tr style="border:none;">
                            <td style="width:70px;height:20px;">
                                Fecha:
                            </td>
                            <td style="border-bottom:1px solid;max-height:20px;">
                                <p>&nbsp;&nbsp;' . date("d/m/Y", strtotime($data["FechaProgramada"])) . ' al ' . date("d/m/Y", strtotime($data["FechaProgramada"] . "+ 8 days")) . '</p> 
                            </td>
                        </tr>
                        <tr style="border:none;">
                            <td style="width:70px;">
                                Nombre: 
                            </td>
                            <td style="border-bottom:1px solid;">
                                <p>&nbsp;&nbsp;' . $cliente["Nombre"] . '</p>
                            </td>
                        </tr>
                        <tr style="border:none;">
                            <td style="width:70px;">
                                Dirección: 
                            </td>
                            <td style="border-bottom:1px solid;">
                                <p>&nbsp;&nbsp;' . $cliente["Dir"] . '</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table style="border:2px solid #2196f3;border-radius:10px;width:600px;">
                        <tr>
                            <td style="width:16.6%;">
                                ETAPA
                            </td>
                            <td style="width:16.6%;">
                                TORRE
                            </td>
                            <td style="width:16.6%;">
                                APTO
                            </td>
                            <td style="width:16.6%;">
                                MANZANA
                            </td>
                            <td style="width:16.6%;">
                                INT
                            </td>
                            <td style="width:16.6%;">
                                CASA
                            </td>
                        </tr>
                        <tr>
                            <td style="border:1px solid;padding:2px;text-align:center;">
                                <p style="margin:0px auto;">' . $cliente["Etapa"] . '</p>
                            </td>
                            <td style="border:1px solid;padding:2px;text-align:center;">
                                <p style="margin:0px auto;">' . $cliente["Torre"] . '</p>
                            </td>
                            <td style="border:1px solid;padding:2px;text-align:center;">
                                <p style="margin:0px auto;">' . $cliente["Apartamento"] . '</p>
                            </td>
                            <td style="border:1px solid;padding:2px;text-align:center;">
                                <p style="margin:0px auto;">' . $cliente["Manzana"] . '</p>
                            </td>
                            <td style="border:1px solid;padding:2px;text-align:center;">
                                <p style="margin:0px auto;">' . $cliente["Interior"] . '</p>
                            </td>
                            <td style="border:1px solid;padding:2px;text-align:center;">
                                <p style="margin:0px auto;">' . $cliente["Casa"] . '</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" style="text-align:center;">
                                VALOR: 
                            </td> 
                            <td colspan="2" style="border-bottom:1px solid;">
                                <p>&nbsp;&nbsp;&nbsp;' . money_format("%.0n", $data["Valor"]) . '</p>
                            </td>
                            <td colspan="1" style="text-align:center;">
                                ABONO: 
                            </td> 
                            <td colspan="2" style="border-bottom:1px solid;">
                                <p>&nbsp;&nbsp;&nbsp;' . money_format("%.0n", $data["Cuota"]) . '</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" style="text-align:center;">
                                SALDO: 
                            </td> 
                            <td colspan="2" style="border-bottom:1px solid;">
                                <p>&nbsp;&nbsp;&nbsp;' . money_format("%.0n", $data["Valor"] - $data["Cuota"]) . '</p>
                            </td>
                            <td colspan="3" style="text-align:center;">
                                <p><b>1 BIBLIA GRANDE</b></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table style="border:2px solid #2196f3;border-radius:10px;width:600px;">
                        <tr style="border:none;">
                            <td style="width:10%;">
                                BARRIO:
                            </td>
                            <td style="width:40%;">
                                <p>' . $cliente["Barrio"] . '</p> 
                            </td>
                            <td style="width:10%;">
                                CUOTA: 
                            </td>
                            <td style="width:5%;">
                                <p>' . $data["numCuotas"] . '</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table style="border:2px solid #2196f3;border-radius:10px;width:600px;">
                        <tr style="border:none;">
                            <td style="width:20%">TEL. CLIENTE:</td>
                            <td style="width:35%">
                                <p>' . $cliente["Telefono1"] . ' - ' . $cliente["Telefono2"] . '</p> 
                                <p>' . $cliente["Telefono3"] . '</p> 
                            </td>
                            <td style="width:20%">
                                FIRMA: 
                            </td>
                            <td style="width:25%"><br></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <p style="text-align:center;font-size:20px;"><b>E-mail: ' . $this->config->item('EMAIL1_COMPANY') . ' - ' . $this->config->item('EMAIL2_COMPANY') . '</b></p>
                </td>            
            </tr>
            <tr>
                <td colspan="4">
                    <p style="text-align:center;font-size:20px;"><b>' . $dataAdmin[0]["Direccion"] . ' - Teléfono: ' . $dataAdmin[0]["Telefono1"] . '</b></p>
                </td>          
            </tr>
        </table>';

            return $html . "<br>";
        }
    }

    public function Contador()
    {
        $f1 = date("Y-m-d 00:00:00");
        $f2 = date("Y-m-d 23:59:59");

        $Pagos = $this->ConteoPagos($f1, $f2);
        $data = new stdClass();
        $data->Controller = "Pagos";
        $data->title = "Conteo de Pagos";
        $data->subtitle = "Listado de Pagos por Estados";
        $data->contenido = $this->viewControl . '/Contador';
        $data->Pagos = $Pagos;

        $this->load->view('frontend', $data);
    }

    public function ConteoPagosPost()
    {
        $fecha1 = trim($this->input->post('pag_fec1') . " 00:00:00");
        $fecha1 = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fecha1);
        $fecha2 = trim($this->input->post('pag_fec2') . " 23:59:59");
        $fecha2 = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fecha2);
        $Pagos = $this->ConteoPagos($fecha1, $fecha2);

        echo json_encode($Pagos);
    }

    public function ConteoPagos($fecha1, $fecha2)
    {
        $Pagos = array();
        $progH = $this->Pagos_model->AllPayProg();
        $Pagos["ProgH"] = $progH[0]["Num"];
        $todos = $this->Pagos_model->AllPay();
        $Pagos["Todos"] = $todos[0]["Num"];
        $DescH = $this->Pagos_model->AllPayProgDesc();
        $Pagos["DescH"] = $DescH[0]["Num"];
        $NoPagoH = $this->Pagos_model->AllPayProgNoPago();
        $Pagos["NoPagoH"] = $NoPagoH[0]["Num"];
        //Filtro
        $Confir = $this->Pagos_model->PayOk($fecha1, $fecha2);
        $Pagos["Confir"] = $Confir[0]["Num"];
        $PagPro = $this->Pagos_model->PayProg($fecha1, $fecha2);
        $Pagos["PagPro"] = $PagPro[0]["Num"];
        $Desc = $this->Pagos_model->PayProgDesc($fecha1, $fecha2);
        $Pagos["Desc"] = $Desc[0]["Num"];
        $NoPago = $this->Pagos_model->PayProgNoPago($fecha1, $fecha2);
        $Pagos["NoPago"] = $NoPago[0]["Num"];
        //Llamadas Pedidos
        $Llamadas = $this->Pedidos_model->contarPedidos($fecha1, $fecha2);
        $Pagos["Llamadas"] = $Llamadas[0]["Num"];

        return $Pagos;
    }

    public function AdminPagos()
    {
        $dataUsuarios = $this->Usuarios_model->obtenerUsuariosEP();
        $fecha = date("Y-m-d");
        $user = "*";

        $data = new stdClass();
        $data->Controller = "Pagos";
        $data->title = "Pagos Confirmados";
        $data->subtitle = "Pagos Confirmados";
        $data->contenido = $this->viewControl . '/AdminPagos';
        $data->ListaUsuarios = $dataUsuarios;

        $this->load->view('frontend', $data);
    }

    public function pagosListado()
    {
        $user = "*";
        $fechaIni = date('Y-m-d') . " 00:00:00";
        $fechaFin = date('Y-m-d') . " 23:59:59";

        try {
            $dataProgramados = $this->Pagos_model->obtenerPagosFechaUser($user, $fechaIni, $fechaFin);
            $arreglo["data"] = [];
            if (isset($dataProgramados) && $dataProgramados != false) {
                $i = 0;
                foreach ($dataProgramados as $item) {
                    $btn1 = "<a href='" . base_url() . "Pagos/Consultar/" . $item['Codigo'] . "/' target='_blank' title='Ver Pago'><i class='fa fa-search' aria-hidden='true' style='padding:5px;'></i></a>";
                    $btn2 = "<a href='" . base_url() . "Pagos/Reversar/" . $item['Codigo'] . "/' target='_blank' title='Reversar Pago'><i class='fa fa-undo' aria-hidden='true' style='padding:5px;'></i></a>";
                    $btn3 = "";

                    if (strlen($item["Observaciones"]) > 30) {
                        $osb = substr($item["Observaciones"], 0, 30) . " (...)";
                    } else {
                        $osb = $item["Observaciones"];
                    }

                    $pedidosClientes = $this->Pedidos_model->obtenerClientePorPedido($item["Pedido"]);
                    $arreglo["data"][$i] = array(
                        "pedido" => $pedidosClientes[0]["Nombre"],
                        "cuota" => money_format("%.0n", $item["Pago"]),
                        "fecha" => date("d/m/Y", strtotime($item["FechaPago"])),
                        "observacion" => $osb,
                        "usuario" => $item["UsuarioCreacion"],
                        "btn" => '<div class="btn-group text-center" style="margin: 0px auto; width:100%;">' . $btn1 . $btn2 . $btn3 . '</div>'
                    );
                    $i++;
                }
            }
            echo json_encode($arreglo);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . "<br>";
        }
    }

    public function FiltroPagos()
    {
        $user = trim($this->input->post('pag_usu'));
        $fechaIni = trim($this->input->post('pag_fec1'));
        $date = str_replace('/', '-', $fechaIni);
        $fechaIni = date('Y-m-d', strtotime($date)) . " 00:00:00";
        $fechaFin = trim($this->input->post('pag_fec2'));
        $date = str_replace('/', '-', $fechaFin);
        $fechaFin = date("Y-m-d", strtotime($date)) . " 23:59:59";

        try {
            $dataProgramados = $this->Pagos_model->obtenerPagosFechaUser($user, $fechaIni, $fechaFin);
            $arreglo["data"] = [];
            if (isset($dataProgramados) && $dataProgramados != false) {
                $i = 0;
                foreach ($dataProgramados as $item) {
                    $btn1 = "<a href='" . base_url() . "Pagos/Consultar/" . $item['Codigo'] . "/' title='Ver Pago'><i class='fa fa-search' aria-hidden='true' style='padding:5px;'></i></a>";
                    $btn2 = "<a href='" . base_url() . "Pagos/Reversar/" . $item['Codigo'] . "/' title='Reversar Pago'><i class='fa fa-undo' aria-hidden='true' style='padding:5px;'></i></a>";
                    $btn3 = "";

                    if (strlen($item["Observaciones"]) > 30) {
                        $osb = substr($item["Observaciones"], 0, 30) . " (...)";
                    } else {
                        $osb = $item["Observaciones"];
                    }

                    $pedidosClientes = $this->Pedidos_model->obtenerClientePorPedido($item["Pedido"]);
                    $arreglo["data"][$i] = array(
                        "pedido" => $pedidosClientes[0]["Nombre"],
                        "cuota" => money_format("%.0n", $item["Pago"]),
                        "fecha" => date("d/m/Y", strtotime($item["FechaPago"])),
                        "observacion" => $osb,
                        "usuario" => $item["UsuarioCreacion"],
                        "btn" => '<div class="btn-group text-center" style="margin: 0px auto; width:100%;">' . $btn1 . $btn2 . $btn3 . '</div>'
                    );
                    $i++;
                }
            }
            echo json_encode($arreglo);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . "<br>";
        }
    }

    public function Reversar($pago)
    {
        $dataPago = $this->Pagos_model->obtenerPagosCod($pago);
        if (isset($dataPago) && $dataPago != false) {
            $dataPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($dataPago[0]["Pedido"]);
            if (isset($dataPedido) && $dataPedido != false) {
                $dataClientes = $this->Clientes_model->obtenerClienteDir($dataPedido[0]["Cliente"]);
                if (isset($dataClientes) && $dataClientes != false) {
                    $dataCobradores = $this->Cobradores_model->obtenerCobradores();
                    if (isset($dataCobradores) && $dataCobradores != false) {
                        $dataPagoPedido = array();
                        foreach ($dataPedido as $item) {
                            $i = intval($item['CodPedido']);
                            $dataPagos = $this->Pagos_model->obtenerPagosPedido($i);
                            if (isset($dataPagos) && $dataPagos != false) {
                                $dataPagoPedido[$i] = $dataPagos;
                            }
                        }

                        $datosPagos = array();
                        $dataPagosP = array();
                        foreach ($dataPedido as $item1) {
                            $i = intval($item1['CodPedido']);
                            if (array_key_exists($i, $dataPagoPedido)) {
                                $dataPagosP["Pedidos"] = $i;
                                $cuota = 0;
                                $abonado = 0;
                                $f2 = "2018-01-01 00:00:00";
                                foreach ($dataPagoPedido[$i] as $item) {
                                    $cuota ++;
                                    $abonado = $abonado + $item["Pago"];
                                    $f1 = $item["FechaPago"];
                                    if ($f1 > $f2) {
                                        $f2 = $f1;
                                    }
                                }
                                $dataPagosP["cuota"] = $cuota;
                                $dataPagosP["abonado"] = $abonado;
                                $dataPagosP["valor"] = $item1["Valor"];
                                $dataPagosP["saldo"] = intval($item1["Valor"]) - intval($abonado);
                                $dataPagosP["fechaUltimoPago"] = $f2;
                                $ultimoPago = 0;

                                foreach ($dataPagoPedido[$i] as $item) {
                                    if ($item["FechaPago"] == $f2) {
                                        if ($item["Pago"] > $ultimoPago) {
                                            $ultimoPago = $item["Pago"];
                                        }
                                    }
                                }
                                $dataPagosP["UltimoPago"] = $ultimoPago;
                                $datosPagos[$i] = $dataPagosP;
                            } else {
                                $dataPagosP["Pedidos"] = $i;
                                $dataPagosP["cuota"] = "0";
                                $dataPagosP["abonado"] = "0";
                                $dataPagosP["valor"] = $item1["Valor"];
                                $dataPagosP["saldo"] = intval($item1["Saldo"]);
                                $dataPagosP["fechaUltimoPago"] = "--";
                                $ultimoPago = 0;
                                $dataPagosP["UltimoPago"] = 0;
                                $datosPagos[$i] = $dataPagosP;
                            }
                        }

                        $data = new stdClass();
                        $data->Controller = "Pagos";
                        $data->title = "Reversar Pago";
                        $data->subtitle = "Reversar Pago";
                        $data->contenido = $this->viewControl . '/Reversar';
                        $data->cliente = $dataPedido[0]["Cliente"];
                        $data->pedido = $dataPedido[0]["CodPedido"];
                        $data->valor = $dataPedido[0]["Valor"];
                        $data->codigo = $pago;
                        $data->ListaDatos = $dataPedido;
                        $data->ListaDatos2 = $dataClientes;
                        $data->ListaDatos3 = $datosPagos;
                        $data->dataPago = $dataPago;
                        $data->Lista1 = $dataCobradores;

                        $this->load->view('frontend', $data);
                    } else {
                        $this->session->set_flashdata("error", "No se encontraron datos de los Cobradores.");
                        redirect(base_url("/Cobradores/Admin/"));
                    }
                } else {
                    $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>$cliente</b>");
                    redirect(base_url("/Clientes/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Pedido del Pago: <b>$cliente</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos de la Programación del Pago");
            redirect(base_url("/Pagos/Admin/"));
        }
    }

    public function Reverse()
    {
        //Datos Pago
        $pag_cod = trim($this->input->post('pag_cod'));
        $pag_cli = trim($this->input->post('pag_cli'));
        $pag_ped = trim($this->input->post('pag_ped'));
        $pag_cuo = trim($this->input->post('pag_cuo'));
        $pag_pag = trim($this->input->post('pag_pag'));
        $pag_obs = trim($this->input->post('pag_obs'));
        $pag_obsAnt = trim($this->input->post('pag_obsAnt'));
        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha = date("Y-m-d H:i:s");

        try {
            $Pag = $this->Pagos_model->obtenerPagosCod($pag_cod);
            if ($Pag) {
                $dataPago = array(
                    "Habilitado" => 0,
                    "Observaciones" => $pag_obsAnt . "\n---\nSe Reversa Pago:\n" . $pag_obs,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );
                if ($this->Pagos_model->update($pag_cod, $dataPago)) {
                    $dataPedido = $this->Pedidos_model->obtenerPedido($pag_ped);
                    $DiaCobro = $dataPedido[0]["DiaCobro"];
                    $DiaCobro = date("Y-m-d H:i:s", strtotime($DiaCobro . "- 1 month"));
                    $saldo = intval($dataPedido[0]["Saldo"]) - intval($pag_pag);
                    $pag_cuo = $pag_cuo - 1;
                    //Se Crea Historial Pago
                    $this->History($pag_cli, $pag_ped, $fecha, $user, "Reversar Pago", $dataPedido[0]["Saldo"], $pag_cuo, $saldo, $pag_pag, $pag_obs);
                    $dataActPedido = array(
                        "DiaCobro" => $DiaCobro,
                        "Saldo" => $saldo,
                        "Estado" => 110,
                        "FechaUltimoPago" => date("Y-m-d H:i:s", strtotime("2018-06-01 00:00:00")),
                        "Observaciones" => "Actualización de Saldo del Pedido:\nSaldo Anterior: " . money_format("%.0n", ($dataPedido[0]["Saldo"]))
                        . "\nSaldo Actual: " . money_format("%.0n", ($saldo)) . "\n\nObservación automática.",
                        "UsuarioModificacion" => $user,
                        "FechaModificacion" => $fecha
                    );
                
                    $dataPedido = $this->Pedidos_model->obtenerPedido($pag_ped);
                    $dataOriginal = $dataPedido[0];
                    $dataNew = compararCambiosLog($dataOriginal, $dataActPedido);
                    if ($this->Pedidos_model->update($pag_ped, $dataNew)) {
                        $modulo = "Pagar Pedido";
                        $accion = "Actualizar Saldo";
                        $tabla = "Pagos";
                        $llave = $pag_ped;
                        $cliente_log = $pag_cli;
                        $enlace = "Clientes|Consultar|" . $pag_cli;
                        $observaciones = "";
                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                           
                        $dataCli = array(
                            "Estado" => 104,
                            "Observaciones" => $pag_obsAnt . "\n---\nCambio de Estado por Reversion de Pago\n \nObservación automática.",
                            "UsuarioModificacion" => $user,
                            "FechaModificacion" => $fecha
                        );
                          
                        $dataCliente = $this->Clientes_model->obtenerCliente($pag_cli); 
                        $dataOriginal = $dataCliente[0];
                        $dataNew = compararCambiosLog($dataOriginal, $dataCli);
                        if ($this->Clientes_model->update($pag_cli, $dataCli)) {
                            $modulo = "Pagar Pedido";
                            $accion = "Estado Al día Cliente";
                            $tabla = "Clientes";
                            $llave = $pag_ped; 
                            $cliente_log = $pag_cli;
                            $enlace = "Clientes|Consultar|" . $pag_cli;
                            $observaciones = "nEstado: Al día\n---\nSe actualiza estado del Cliente\n \nObservación automática.";
                            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                     
                            echo 1;
                        } else {
                            echo "No se pudo Actualizar el Estado del Cliente. Actualice la página y vuelva a intentarlo.";
                        }
                    } else {
                        echo "No se pudo Actualizar el Saldo del Pedido. Actualice la página y vuelva a intentarlo.";
                    }
                } else {
                    echo "No se pudo actualizar el Pago. Actualice la página y vuelva a intentarlo.";
                }
            } else {
                echo "No se pudo encontrar el Pago. Actualice la página y vuelva a intentarlo.";
            }
        } catch (Exception $e) {
            echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
        }
    }

    public function valPagosGestion($dataPagos)
    {
        $fecha1 = date("Y-m-d") . " 23:59:59";
        $fecha2 = date("Y-m-d", strtotime($fecha1 . "- 15 days")) . " 00:00:00";
        $i = 0;
        foreach ($dataPagos as $value) {
            $pedido = $value["Pedidos"];
            $cliente = $value["codCliente"];
            $gest = $this->Cobradores_model->obtenerLlamadasPedidoFechas($pedido, $cliente, $fecha2, $fecha1);
            // var_dump($gest[0]);
            // echo "<br><br>";
            $Motivo = "Pendiente";
            $color = "";
            $codMotivo = "100";
            $codPago = null;

            if ($gest != false) {
                $Motivo = "Pendiente";
                $color = "";
                $codMotivo = "100";
                $codPago = null;
                foreach ($gest as $val) {
                    $Motivo = $val["nombreMotivo"];
                    $color = $val["color"];
                    $codMotivo = $val["codMotivo"];
                    $codPago = $val["Codigo"];
                }
                $dataPagos[$pedido]['Motivo'] = $Motivo;
                $dataPagos[$pedido]['Color'] = $color;
                $dataPagos[$pedido]['codPago'] = $codPago;
            } else {
                $dataPagos[$pedido]['Motivo'] = $Motivo;
                $dataPagos[$pedido]['Color'] = $color;
                $dataPagos[$pedido]['codPago'] = $codPago;
            }
            $dataPagos[$pedido]['CodMotivo'] = $codMotivo;
        }
        // foreach ($dataPagos as $val) {
        //     var_dump($val);
        //     echo "<br><br>";
        // }
        return $dataPagos;
    }
}
