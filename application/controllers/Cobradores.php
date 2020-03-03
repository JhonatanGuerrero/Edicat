<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cobradores extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Cobradores';
        $this->load->model('Cobradores_model');
        $this->load->model('Estados_model');
        $this->load->model('Clientes_model');
        $this->load->model('Pedidos_model');
        $this->load->model('Pagos_model');
        $this->load->model('Usuarios_model');
        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI] );
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        }
    }

    public function index() {
        //redirect(site_url($this->viewControl . "/Admin/"));
    }

    public function Admin() {
        
    }

    //Sección llamadas
    public function AddCall() {
        $pedido = trim($this->input->post('pedido'));
        $cliente = trim($this->input->post('cliente'));
        $motivo = trim($this->input->post('motivo'));
        $nombremotivo = trim($this->input->post('nombremotivo'));
        $fechaprogramada = trim($this->input->post('fechaprogramada') . " 00:00:00");
        $fechaprogramada = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fechaprogramada);
        $fechaPago = trim($this->input->post('fechaPago') . " 00:00:00");
        $fechaPago = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fechaPago);
        $valorPago = trim($this->input->post('valorPago'));
        $observaciones = trim($this->input->post('observaciones'));
        $fechaGestion = date("Y-m-d");

        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha = date("Y-m-d H:i:s");

        $errores = 0;
        if ($motivo == 104) {
            if ($fechaprogramada == "") {
                $errores++;
                echo "El motivo de 'Llamar otro día', requiere una fecha en 'Programar Llamada'";
            } else {
                $gestion = array(
                    "Pedido" => $pedido,
                    "Cliente" => $cliente,
                    "Fecha" => $fechaGestion,
                    "Motivo" => $motivo,
                    "FechaProgramada" => $fechaprogramada,
                    "Observaciones" => $observaciones,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );

                $devolverLlamada = array(
                    "Pedido" => $pedido,
                    "Cliente" => $cliente,
                    "Fecha" => $fechaprogramada,
                    "Motivo" => '100',
                    "Devolucion" => 0,
                    "Observaciones" => $observaciones,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );

                $devol = $this->Cobradores_model->saveDevolucionLlamada($devolverLlamada);
                if ($devol != 1) {
                    $errores++;
                    echo "No se pudo planear programar la llamada";
                }
            }
        } else {
            if ($motivo == 101) {
                if ($valorPago == 0 || $valorPago == "" || $fechaPago == "") {
                    $errores++;
                    echo "El motivo de 'Programar Pago', requiere una fecha en 'Programar Pago' y un valor en 'Valor del Pago'";
                } else {
                    $gestion = array(
                        "Pedido" => $pedido,
                        "Cliente" => $cliente,
                        "Fecha" => $fechaGestion,
                        "Motivo" => $motivo,
                        "Observaciones" => $observaciones,
                        "UsuarioCreacion" => $user,
                        "FechaCreacion" => $fecha
                    );
                }
            } else {
                $gestion = array(
                    "Pedido" => $pedido,
                    "Cliente" => $cliente,
                    "Fecha" => $fechaGestion,
                    "Motivo" => $motivo,
                    "Observaciones" => $observaciones,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );
            }
        }

        if ($errores == 0) {
            if ($this->Cobradores_model->saveLlamada($gestion)) {
                $dataGestion = $this->Cobradores_model->obtenerLlamadasPedidoFecha($pedido, $cliente, $fechaGestion);
                if ($dataGestion) {
                    $gestion ["Codigo"] = $dataGestion[0]['Codigo'];
                    $gestion ['Observaciones'] = "Gestión de Llamada: Recibo de Pago\n---\n" . $observaciones;
                    $modulo = "Gestión Cliente";
                    $tabla = "Llamada";
                    $accion = "Llamada a Cliente";
                    $llave = $dataGestion[0]['Cliente'];
                    $sql = LogSave($gestion, $modulo, $tabla, $accion, $llave);
                }
                $errores = "1";
                if ($motivo == 101) {
                    $errores = $this->programarPago($pedido, $valorPago, $fechaPago, $observaciones);
                }
                echo $errores;
            }
        } else {
            echo $errores;
        }
    }

    public function AddReCall() {
        $llamada = trim($this->input->post('llamada'));
        $pedido = trim($this->input->post('pedido'));
        $cliente = trim($this->input->post('cliente'));
        $motivo = trim($this->input->post('motivo'));
        $nombremotivo = trim($this->input->post('nombremotivo'));
        $fechaprogramada = trim($this->input->post('fechaprogramada') . " 00:00:00");
        $fechaprogramada = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fechaprogramada);
        $fechaPago = trim($this->input->post('fechaPago') . " 00:00:00");
        $fechaPago = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fechaPago);
        $valorPago = trim($this->input->post('valorPago'));
        $observaciones = trim($this->input->post('observaciones'));
        $fechaGestion = date("Y-m-d");

        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha = date("Y-m-d H:i:s");

        $errores = 0;
        if ($motivo == 104) {
            if ($fechaprogramada == "" || trim($fechaprogramada) == "00:00:00") {
                $errores++;
                echo "El motivo de 'Llamar otro día', requiere una fecha en 'Programar Llamada'";
            } else {
                $gestion = array(
                    "Motivo" => $motivo,
                    "FechaProgramada" => $fechaprogramada,
                    "Observaciones" => $observaciones,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );

                $devolverLlamada = array(
                    "Pedido" => $pedido,
                    "Cliente" => $cliente,
                    "Fecha" => $fechaprogramada,
                    "Motivo" => '100',
                    "Devolucion" => 0,
                    "Observaciones" => $observaciones,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );

                $devol = $this->Cobradores_model->saveDevolucionLlamada($devolverLlamada);
                if ($devol != 1) {
                    $errores++;
                    echo "No se pudo planear programar la llamada";
                }
            }
        } else {
            if ($motivo == 101) {
                if ($valorPago == 0 || $valorPago == "" || $fechaPago == "") {
                    $errores++;
                    echo "El motivo de 'Programar Pago', requiere una fecha en 'Programar Pago' y un valor en 'Valor del Pago'";
                } else {
                    $gestion = array(
                        "Fecha" => $fechaGestion,
                        "Motivo" => $motivo,
                        "Observaciones" => $observaciones,
                        "UsuarioModificacion" => $user,
                        "FechaModificacion" => $fecha
                    );
                }
            } else {
                $gestion = array(
                    "Fecha" => $fechaGestion,
                    "Motivo" => $motivo,
                    "Observaciones" => $observaciones,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );
            }
        }

        if ($errores == 0) {
            $errores = "1";
            if ($this->Cobradores_model->updateDevolucionLlamada($llamada, $gestion)) {
                $dataGestion = $this->Cobradores_model->obtenerDevolucionLlamadasPedidoFecha($pedido, $cliente, $fechaGestion);
                if ($dataGestion) {
                    $gestion ["Codigo"] = $dataGestion[0]['Codigo'];
                    $gestion ['Observaciones'] = "Gestión de Llamada:\n---\n" . $observaciones;
                    $modulo = "Gestión Cliente";
                    $tabla = "DevolucionLlamadas";
                    $accion = "Llamada a Cliente";
                    $llave = $dataGestion[0]['Cliente'];
                    $sql = LogSave($gestion, $modulo, $tabla, $accion, $llave);
                }

                if ($errores == "1") {
                    if ($motivo == 101) {
                        $errores = $this->programarPago($pedido, $valorPago, $fechaPago, $observaciones);
                    }
                }

                echo $errores;
            }
        } else {
            echo $errores;
        }
    }

    public function GestionHis($pedido, $cliente) {
        if ($pedido == "" || $cliente == "") {
            $this->session->set_flashdata("error", "Se requieren datos del Cliente y del Pedido para ver las Gestiones.");
            redirect(base_url("Pagos/Admin/"));
        } else {
            $fecha = date("Y-m-d") . " 00:00:00";
            $f1 = date("Y-m-d");
            $dataLlamadas = $this->Cobradores_model->obtenerDevolucionLlamadasPedidoFechaCre($pedido, $cliente, $f1);
            if ($dataLlamadas == FALSE) {
                $this->session->set_flashdata("error", "No se encontraron Gestiones en el Cliente y Pedido indicado el día de hoy.");
                redirect(base_url("Pagos/Admin/"));
            } else {
                $datacliente = $this->Clientes_model->obtenerClienteDir($cliente);
                if ($datacliente == FALSE) {
                    $this->session->set_flashdata("error", "No se encontraron datos del Cliente indicado para las gestiones.");
                    redirect(base_url("Pagos/Admin/"));
                } else {
                    $direccion = $datacliente[0]["Dir"];
                    $direccion = ($datacliente[0]["Etapa"] != "") ? $direccion . " ET " . $datacliente[0]["Etapa"] : $direccion;
                    $direccion = ($datacliente[0]["Torre"] != "") ? $direccion . " TO " . $datacliente[0]["Torre"] : $direccion;
                    $direccion = ($datacliente[0]["Apartamento"] != "") ? $direccion . " AP " . $datacliente[0]["Apartamento"] : $direccion;
                    $direccion = ($datacliente[0]["Manzana"] != "") ? $direccion . " MZ " . $datacliente[0]["Manzana"] : $direccion;
                    $direccion = ($datacliente[0]["Interior"] != "") ? $direccion . " IN " . $datacliente[0]["Interior"] : $direccion;
                    $direccion = ($datacliente[0]["Casa"] != "") ? $direccion . " CA " . $datacliente[0]["Casa"] : $direccion;
                    $datacliente[0]["Direccion"] = $direccion;
                    $telefono = trim($datacliente[0]["Telefono1"] . " - " . $datacliente[0]["Telefono2"]);
                    $datacliente[0]["Telefono"] = $telefono;

                    $data = new stdClass();
                    $data->Controller = "Cobradores";
                    $data->title = "Gestión de Llamadas";
                    $data->subtitle = "Gestión de Llamadas";
                    $data->contenido = $this->viewControl . '/Gestion';
                    $data->pedido = $pedido;
                    $data->cliente = $cliente;
                    $data->ListaDatos = $dataLlamadas;
                    $data->DatosCliente = $datacliente[0];

                    $this->load->view('frontend', $data);
                }
            }
        }
    }

    public function GestionHoy($pedido, $cliente) {
        if ($pedido == "" || $cliente == "") {
            $this->session->set_flashdata("error", "Se requieren datos del Cliente y del Pedido para ver las Gestiones.");
            redirect(base_url("Pagos/Admin/"));
        } else {
            $fecha1 = date("Y-m-d") . " 00:00:00";
            $fecha2 = date("Y-m-d", strtotime($fecha1 . "- 5 days")) . " 00:00:00";
            $dataLlamadas = $this->Cobradores_model->obtenerLlamadasPedidoFechas($pedido, $cliente, $fecha2, $fecha1);
            if ($dataLlamadas == FALSE) {
                $this->session->set_flashdata("error", "No se encontraron Gestiones en el Cliente y Pedido en los últimos 5 días.");
                redirect(base_url("Pagos/Admin/"));
            } else {
                $datacliente = $this->Clientes_model->obtenerClienteDir($cliente);
                if ($datacliente == FALSE) {
                    $this->session->set_flashdata("error", "No se encontraron datos del Cliente indicado para las gestiones.");
                    redirect(base_url("Pagos/Admin/"));
                } else {
                    $direccion = $datacliente[0]["Dir"];
                    $direccion = ($datacliente[0]["Etapa"] != "") ? $direccion . " ET " . $datacliente[0]["Etapa"] : $direccion;
                    $direccion = ($datacliente[0]["Torre"] != "") ? $direccion . " TO " . $datacliente[0]["Torre"] : $direccion;
                    $direccion = ($datacliente[0]["Apartamento"] != "") ? $direccion . " AP " . $datacliente[0]["Apartamento"] : $direccion;
                    $direccion = ($datacliente[0]["Manzana"] != "") ? $direccion . " MZ " . $datacliente[0]["Manzana"] : $direccion;
                    $direccion = ($datacliente[0]["Interior"] != "") ? $direccion . " IN " . $datacliente[0]["Interior"] : $direccion;
                    $direccion = ($datacliente[0]["Casa"] != "") ? $direccion . " CA " . $datacliente[0]["Casa"] : $direccion;
                    $datacliente[0]["Direccion"] = $direccion;
                    $telefono = trim($datacliente[0]["Telefono1"] . " - " . $datacliente[0]["Telefono2"]);
                    $datacliente[0]["Telefono"] = $telefono;

                    $data = new stdClass();
                    $data->Controller = "Cobradores";
                    $data->title = "Gestión de Llamadas";
                    $data->subtitle = "Gestión de Llamadas";
                    $data->contenido = $this->viewControl . '/Gestion';
                    $data->pedido = $pedido;
                    $data->cliente = $cliente;
                    $data->ListaDatos = $dataLlamadas;
                    $data->DatosCliente = $datacliente[0];

                    $this->load->view('frontend', $data);
                }
            }
        }
    }

    public function Rellamar() {
        $dataUsuarios = $this->Usuarios_model->obtenerUsuariosEP();
        $datosMotivos = $this->Cobradores_model->obtenerMotivosLlamadas();

        $data = new stdClass();
        $data->Controller = "Cobradores";
        $data->title = "Volver a Llamar";
        $data->subtitle = "Clientes para gestión de Cobro";
        $data->contenido = $this->viewControl . '/Rellamar';
        $data->ListaUsuarios = $dataUsuarios;
        $data->Lista1 = $datosMotivos;

        $this->load->view('frontend', $data);
    }

    public function obtenerVolverLlamarJson() {
        $fecha = date("Y-m-d");
        echo $this->obtenerVolverLlamarJsonPara($fecha, $fecha);
    }

    public function obtenerVolverLlamarJsonPost() {
        $fechaIni = trim($this->input->post('pag_fec1'));
        $date = str_replace('/', '-', $fechaIni);
        $fechaIni = date('Y-m-d', strtotime($date));
        $fechaFin = trim($this->input->post('pag_fec2'));
        $date = str_replace('/', '-', $fechaFin);
        $fechaFin = date("Y-m-d", strtotime($date));

//        $fechaIni = date("2019-05-07");
//        $fechaFin = date("2019-05-07");

        echo $this->obtenerVolverLlamarJsonPara($fechaIni, $fechaFin);
    }

    public function obtenerVolverLlamarJsonPara($f1, $f2) {
        $data = $this->obtenerVolverLlamar($f1, $f2);
        $arreglo["data"] = [];

        if ($data != FALSE) {
            $i = 0;
            foreach ($data as $item) {
                $fecha1 = trim($item["FechaCreacion"]);
                $dataPagos = $this->Pagos_model->obtenerPagosProgramadosPorPedido($item["Pedido"], $fecha1);
                if ($dataPagos[0]["Cuotas"] <= 0) 
                    {
                    $btn1 = '<a href = "#ModalCall" data-toggle = "modal" title = "Reportar Llamada" onclick = "DatosModal(\'' . $item["Codigo"] . '\', \'' . $item["Pedido"] . '\', \'' . $item["Cliente"] . '\', \'' . $item["Nombre"] . '\', \'' . $item["Direccion"] . '\', \'' . $item["telefono"] . '\');"><i class = "fa fa-phone" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    $btn2 = '<a href = "' . base_url() . 'Cobradores/GestionHoy/' . $item["Pedido"] . '/' . $item["Cliente"] . '/" target="_blank" title = "Gestión de Llamada"><i class = "fa fa-list-ul" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    $btn3 = '<a href = "' . base_url() . 'Pagos/Generar/' . $item["Cliente"] . '/" target="_blank" title = "Pagar"><i class = "fa fa-motorcycle" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    //var_dump($item);
                    $arreglo["data"][$i] = array(
                        "Nombre" => $item["Nombre"],
                        "Direccion" => $item["Direccion"],
                        "telefono" => $item["telefono"],
                        "cuota" => $item["cuota"],
                        "saldo" => money_format("%.0n", $item["saldo"]),
                        "UltimoPago" => $item["UltimoPago"],
                        "Fecha" => $item["Fecha"],
                        "Ubicacion" => $item["PaginaFisica"],
                        "Motivo" => $item["Motivo"],
                        "btn" => '<div class="btn-group text-center" style="margin: 0px auto;  width:100%;">' . $btn1 . $btn2 . $btn3 . '</div>'
                    );
                    $i++;
                }
            }
        }
        echo json_encode($arreglo);
    }

    public function obtenerVolverLlamar($f1, $f2) {
        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha1 = date($f1 . " 00:00:00");
        $fecha2 = date($f2 . " 23:59:59");
        $motivo = 104; //Llamar otro día
        $i = 0;
        $data = array();

        $dataVolver = $this->Cobradores_model->obtenerDevolucionLlamadasMotivoFechaPro($fecha1, $fecha2);
        if ($dataVolver == FALSE) {
            return false;
//            $this->session->set_flashdata("error", "No se encontraron Clientes o Pedidos para VOLVER A LLAMAR el día de hoy.");
//            redirect(base_url("Pagos/Admin/"));
        } else {
            foreach ($dataVolver as $value) {
                //var_dump($value);
                $dataPedido = $this->Pedidos_model->obtenerPedidosCliente($value["Cliente"]);
                $datacliente = $this->Clientes_model->obtenerClienteDir($value["Cliente"]);
                $dataCuotas = $this->Pagos_model->obtenerPagosPorPedido($value["Pedido"]);

                $direccion = $datacliente[0]["Dir"];
                $direccion = ($datacliente[0]["Etapa"] != "") ? $direccion . " ET " . $datacliente[0]["Etapa"] : $direccion;
                $direccion = ($datacliente[0]["Torre"] != "") ? $direccion . " TO " . $datacliente[0]["Torre"] : $direccion;
                $direccion = ($datacliente[0]["Apartamento"] != "") ? $direccion . " AP " . $datacliente[0]["Apartamento"] : $direccion;
                $direccion = ($datacliente[0]["Manzana"] != "") ? $direccion . " MZ " . $datacliente[0]["Manzana"] : $direccion;
                $direccion = ($datacliente[0]["Interior"] != "") ? $direccion . " IN " . $datacliente[0]["Interior"] : $direccion;
                $direccion = ($datacliente[0]["Casa"] != "") ? $direccion . " CA " . $datacliente[0]["Casa"] : $direccion;
                $datacliente[0]["Direccion"] = $direccion;
                $telefono = trim($datacliente[0]["Telefono1"] . " - " . $datacliente[0]["Telefono2"]);
                $datacliente[0]["Telefono"] = $telefono;
                $ultimoPago = "";
                if ($dataPedido[0]["FechaUltimoPago"] == null || $dataPedido[0]["FechaUltimoPago"] == "") {
                    $ultimoPago = "0";
                } else {
                    $ultimoPago = date("d/m/Y", strtotime($dataPedido[0]["FechaUltimoPago"]));
                }

                $datos = array(
                    "Codigo" => $value["Codigo"],
                    "Pedido" => $value["Pedido"],
                    "Cliente" => $value["Cliente"],
                    "Nombre" => $datacliente[0]["Nombre"],
                    "Direccion" => $datacliente[0]["Direccion"],
                    "telefono" => $datacliente[0]["Telefono"],
                    "cuota" => $dataCuotas[0]["Cuotas"],
                    "saldo" => $dataPedido[0]["Saldo"],
                    "UltimoPago" => $ultimoPago,
                    "Fecha" => date("d/m/Y", strtotime($value["Fecha"])),
                    "FechaCreacion" => $value["FechaCreacion"],
                    "Motivo" => $value["Motivo"],
                    "PaginaFisica" => $dataPedido[0]["PaginaFisica"]
                );
                $data[$i] = $datos;
                $i++;
            }
            $data = $this->valPagosGestionReCall($data);

            return $data;
        }
    }

    public function valPagosGestion($dataPagos) {
        $fecha = date("Y-m-d") . " 00:00:00";
        $i = 0;
        foreach ($dataPagos as $value) {
            $pedido = $value["Pedido"];
            $cliente = $value["Cliente"];
            $gest = $this->Cobradores_model->obtenerLlamadasPedidoFecha($pedido, $cliente, $fecha);
            $Motivo = "Pendiente";
            $color = "";

            if ($gest != FALSE) {
                $Motivo = "Pendiente";
                $color = "";
                foreach ($gest as $val) {
                    $Motivo = $val["nombreMotivo"];
                    $color = $val["color"];
                }
                $dataPagos[$i]['Motivo'] = $Motivo;
                $dataPagos[$i]['Color'] = $color;
            } else {
                $dataPagos[$i]['Motivo'] = $Motivo;
                $dataPagos[$i]['Color'] = $color;
            }
            $dataPagos[$i]['CodMotivo'] = $value["Motivo"];
            $i++;
        }

        return $dataPagos;
    }

    public function valPagosGestionReCall($dataPagos) {
        $fecha = date("Y-m-d") . " 00:00:00";
        $i = 0;
        foreach ($dataPagos as $value) {
            $pedido = $value["Pedido"];
            $cliente = $value["Cliente"];
            $gest = $this->Cobradores_model->obtenerDevolucionLlamadasPedidoFechaPro($pedido, $cliente, $fecha);
            $Motivo = "Pendiente";
            $color = "";

            if ($gest != FALSE) {
                $Motivo = "Pendiente";
                $color = "";
                foreach ($gest as $val) {
                    $Motivo = $val["nombreMotivo"];
                    $color = $val["color"];
                }
                $dataPagos[$i]['Motivo'] = $Motivo;
                $dataPagos[$i]['Color'] = $color;
            } else {
                $dataPagos[$i]['Motivo'] = $Motivo;
                $dataPagos[$i]['Color'] = $color;
            }
            $dataPagos[$i]['CodMotivo'] = $value["Motivo"];
            $i++;
        }

        return $dataPagos;
    }

    public function programarPago($pag_ped, $pag_pag, $pag_fec, $pag_obs) {
        $dataPedido = $this->Pedidos_model->obtenerPedidosClientePorPedido($pag_ped);
        if (isset($dataPedido) && $dataPedido == FALSE) {
            return "No se pudo Programar Pago. Por favor hacerlo manualmente.";
        } else {
            $pag_sal = $dataPedido[0]["Saldo"];
            $pag_cli = $dataPedido[0]["Cliente"];
            $pag_cuo = $this->numCuotas($pag_ped);

            //Datos Auditoría
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");

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
                        $dataPago['Codigo'] = $Pag[0]['Codigo'];
                        $dataPago['Observaciones'] = "Estado: Recibo de Pago\n---\n" . $dataPago['Observaciones'];
                        $modulo = "Pagar Pedido";
                        $tabla = "PagosProgramados";
                        $accion = "Programar Pago";
                        $llave = $pag_ped;
                        //Se Crea Historial Pago
                        //$this->History($pag_cli, $pag_ped, $fecha, $user, "Programar Pago", $pag_sal, 0, $pag_sal, $pag_pag, $pag_obs);
                        $this->History($pag_cli, $pag_ped, $fecha, $user, "Programar Pago", $pag_sal, $pag_cuo, (intval($pag_sal) - intval($pag_pag)), $pag_pag, $pag_obs);

                        $sql = LogSave($dataPago, $modulo, $tabla, $accion, $llave);
                        return 1;
                    } else {
                        return "No se pudo guardar, por favor intentelo de nuevo.";
                    }
                } else {
                    return "No se pudo guardar, por favor intentelo de nuevo.";
                }
            } catch (Exception $e) {
                return 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
            }
        }
    }

    public function History($cliente, $pedido, $fecha, $usuario, $accion, $saldoAnt, $cuota, $saldoNue, $abono, $obs) {
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

    public function numCuotas($pedido) {
        $dataPagos = $this->Pagos_model->obtenerPagosPedido($pedido);
        $num = 1;
        if (isset($dataPagos) && $dataPagos != FALSE) {
            for ($i = 0; $i < count($dataPagos); $i++) {
                $num++;
            }
        }
        return $num;
    }

}

?>