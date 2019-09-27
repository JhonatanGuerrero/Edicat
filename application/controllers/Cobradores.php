<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cobradores extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->viewControl = 'Cobradores';
        $this->load->model('Cobradores_model');
        $this->load->model('Estados_model');
        $this->load->model('Clientes_model');
        $this->load->model('Pedidos_model');
        $this->load->model('Pagos_model');
        $this->load->model('Usuarios_model');
        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]);
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        }
    }

    public function index()
    {
        //redirect(site_url($this->viewControl . "/Admin/"));
    }

    public function Admin()
    {
    }

    //Sección llamadas
    public function AddCall()
    {
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
        
        $tempPago = $this->Pagos_model->obtenerPagosProgramaPedidoActivos($pedido);
        $validacionPago = $tempPago[0]["PagosActivos"];
        $errores = "1";
        $idPermiso = 109;
        $actionGestion = validarPermisoAcciones($idPermiso);

        if ($actionGestion) {
            if ($validacionPago > 0) {
                $errores = "El Cliente ya tiene Programado un Pago. No se puede gestionar otro.";
            } else {
                $idPermiso = 98;
                $action = validarPermisoAcciones($idPermiso);
                $dataLlamada = $this->Cobradores_model->obtenerLlamadasPedidoCliente($pedido, $cliente);
  
                if (isset($dataLlamada) && $dataLlamada != false) {
                    if ($motivo == 104) {
                        $gestion = $this->AddReturnCall($pedido, $cliente, $fechaGestion, $motivo, $fechaprogramada, $observaciones, $user, $fecha, true);
                        if ($gestion == false) {
                            $errores = "No se pudo programar la llamada para otro día, por favor intentelo de nuevo.";
                        }
                    } else {
                        if ($motivo == 101) {
                            if ($action) {
                                if ($valorPago == 0 || $valorPago == "" || $fechaPago == "") {
                                    $errores = "El motivo de 'Programar Pago', requiere una fecha en 'Programar Pago' y un valor en 'Valor del Pago'";
                                } else {
                                    $gestion = $this->AddGestionCall($pedido, $cliente, $fechaGestion, $motivo, $observaciones, $user, $fecha, true);
                                    if ($gestion == false) {
                                        $errores = "No se pudo gestionar el cliente por datos cruzados, por favor intentelo de nuevo.";
                                    }
                                }
                            } else {
                                $errores = "No tiene permisos para Programar Pago";
                            }
                        } else {
                            $gestion = $this->AddGestionCall($pedido, $cliente, $fechaGestion, $motivo, $observaciones, $user, $fecha, true);
                            if ($gestion == false) {
                                $errores = "No se pudo gestionar el cliente por datos cruzados, por favor intentelo de nuevo.";
                            }
                        }
                    }
                    if ($errores == "1") {
                        $dataOriginal = $dataLlamada[0];
                        $dataNew = compararCambiosLog($dataOriginal, $gestion);
                        $llave = $dataOriginal['Codigo'];
                        $cliente_log = $cliente;
                        $this->Cobradores_model->updateLlamada($llave, $dataNew);
                        $modulo = "Gestión Cliente";
                        $tabla = "Llamadas";
                        $accion = "Hacer Llamada a Cliente";
                        $enlace = "Cobradores|GestionLlamada|" . $pedido . "|" . $cliente . "|" . $llave;
                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                    }
                } else {
                    if ($motivo == 104) {
                        $gestion = $this->AddReturnCall($pedido, $cliente, $fechaGestion, $motivo, $fechaprogramada, $observaciones, $user, $fecha, false);
                        if ($gestion == false) {
                            $errores = "No se pudo programar la llamada para otro día, por favor intentelo de nuevo.";
                        }
                    } else {
                        if ($motivo == 101) {
                            if ($action) {
                                if ($valorPago == 0 || $valorPago == "" || $fechaPago == "") {
                                    $errores = "El motivo de 'Programar Pago', requiere una fecha en 'Programar Pago' y un valor en 'Valor del Pago'";
                                } else {
                                    $gestion = $this->AddGestionCall($pedido, $cliente, $fechaGestion, $motivo, $observaciones, $user, $fecha, false);
                                    if ($gestion == false) {
                                        $errores = "No se pudo gestionar el cliente por datos cruzados, por favor intentelo de nuevo.";
                                    }
                                }
                            } else {
                                $errores = "No tiene permisos para Programar Pago";
                            }
                        } else {
                            $gestion = $this->AddGestionCall($pedido, $cliente, $fechaGestion, $motivo, $observaciones, $user, $fecha, false);
                            if ($gestion == false) {
                                $errores = "No se pudo gestionar el cliente por datos cruzados, por favor intentelo de nuevo.";
                            }
                        }
                    }
                    if ($errores == "1") {
                        if ($this->Cobradores_model->saveLlamada($gestion)) {
                            $dataGestion = $this->Cobradores_model->obtenerLlamadasPedidoFecha($pedido, $cliente, $fechaGestion);
                            if ($dataGestion) {
                                $modulo = "Gestión Cliente";
                                $tabla = "Llamadas";
                                $accion = "Hacer Llamada a Cliente";
                                $llave = $dataGestion[0]['Codigo'];
                                $cliente_log = $cliente;
                                $enlace = "Cobradores|GestionLlamada|" . $pedido . "|" . $cliente . "|" . $llave;
                                $dataInsert = $gestion;
                                insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                            }
                        }
                    }
                }
        
                if ($errores == "1") {
                    if ($motivo == 101) {
                        if ($action) {
                            $errores = $this->programarPago($pedido, $valorPago, $fechaPago, $observaciones, null);
                        } else {
                            $errores = "No tiene permisos para Programar Pago";
                        }
                    }
                }
            }
        } else {
            $errores = "No tiene permisos para hacer gestiones a los Clientes";
        }
        echo $errores;
    }

    public function AddReturnCall($pedido, $cliente, $fechaGestion, $motivo, $fechaprogramada, $observaciones, $user, $fecha, $actual)
    {
        if ($fechaprogramada == "") {
            return "El motivo de 'Llamar otro día', requiere una fecha en 'Programar Llamada'";
        } else {
            if ($actual == false) {
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
            } else {
                $gestion = array(
                    "Fecha" => $fechaGestion,
                    "Motivo" => $motivo,
                    "FechaProgramada" => $fechaprogramada,
                    "Observaciones" => $observaciones,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );
            }
            
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
            if ($devol == false) {
                return false;
            } else {
                return $gestion;
            }
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
 
    public function AddReCall()
    {
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
        
        $idPermiso = 109;
        $action = validarPermisoAcciones($idPermiso);
        if ($action) {
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
                if ($motivo == 101) {
                    $idPermiso = 98;
                    $action = validarPermisoAcciones($idPermiso);
                    if ($action) {
                        $errores = $this->programarPago($pedido, $valorPago, $fechaPago, $observaciones);
                    } else {
                        $errores = "No tiene permisos para Programar Pagos";
                    }
                }

                if ($errores == "1") {
                    $dataOriginal = $this->Cobradores_model->obtenerLlamadaCampos($llamada, "Motivo, FechaProgramada, Observaciones");
                    $gestion["Observaciones"] = $dataOriginal[0]["Observaciones"] . "\n--\n" . $observaciones;
                    $dataNew = compararCambiosLog($dataOriginal[0], $gestion);
                    $this->Cobradores_model->updateLlamada($llamada, $dataNew);
                    $this->Cobradores_model->updateDevolucionLlamada($llamada, $dataNew);
                    $modulo = "Gestión Cliente";
                    $tabla = "DevolucionLlamadas";
                    $accion = "Llamada a Cliente";
                    $llave = $llamada;
                    $cliente_log = $cliente;
                    $enlace = "Cobradores|GestionLlamada|" . $pedido . "|" . $cliente . "|" . $llave;
                    $observaciones = "Gestión de Llamada:\n---\n" . $observaciones;
                    updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                }
                     
                echo $errores;
            } else {
                echo $errores;
            }
        } else {
            echo "No tiene permisos para hacer gestiones a los Clientes";
        }
    }

    public function GestionHis($pedido, $cliente)
    {
        if ($pedido == "" || $cliente == "") {
            $this->session->set_flashdata("error", "Se requieren datos del Cliente y del Pedido para ver las Gestiones.");
            redirect(base_url("Pagos/Admin/"));
        } else {
            $fecha = date("Y-m-d") . " 00:00:00";
            $f1 = date("Y-m-d");
            $dataLlamadas = $this->Cobradores_model->obtenerDevolucionLlamadasPedidoFechaCre($pedido, $cliente, $f1);
            if ($dataLlamadas == false) {
                $this->session->set_flashdata("error", "No se encontraron Gestiones en el Cliente y Pedido indicado el día de hoy.");
                redirect(base_url("Pagos/Admin/"));
            } else {
                $datacliente = $this->Clientes_model->obtenerClienteDir($cliente);
                if ($datacliente == false) {
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

    public function GestionHoy($pedido, $cliente)
    {
        if ($pedido == "" || $cliente == "") {
            $this->session->set_flashdata("error", "Se requieren datos del Cliente y del Pedido para ver las Gestiones.");
            redirect(base_url("Pagos/Admin/"));
        } else {
            $fecha1 = date("Y-m-d") . " 00:00:00";
            $fecha2 = date("Y-m-d", strtotime($fecha1 . "- 5 days")) . " 00:00:00";
            $dataLlamadas = $this->Cobradores_model->obtenerLlamadasPedidoFechas($pedido, $cliente, $fecha2, $fecha1);
            if ($dataLlamadas == false) {
                $this->session->set_flashdata("error", "No se encontraron Gestiones en el Cliente y Pedido en los últimos 5 días.");
                redirect(base_url("Pagos/Admin/"));
            } else {
                $datacliente = $this->Clientes_model->obtenerClienteDir($cliente);
                if ($datacliente == false) {
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
                    
                    $a = -1;
                    foreach ($dataLlamadas as $val) {
                        $a++;
                        if ($val["color"]=="green") {
                            $pagPro = $this->Pagos_model->obtenerPagosProgramaPedidoLast($pedido);
                            if ($pagPro == false) {
                                $dataLlamadas[$a]["PagoProgramado"] = "";
                            } else {
                                $dataLlamadas[$a]["PagoProgramado"] = $pagPro[0]["FechaProgramada"];
                            }
                        } else {
                            $dataLlamadas[$a]["PagoProgramado"] = "";
                        }
                    }
                    
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

    public function GestionLlamada($pedido, $cliente, $codigo)
    {
        if ($pedido == "" || $cliente == "" || $codigo == "") {
            $this->session->set_flashdata("error", "Se requieren datos del Cliente, Pedido y la gestión para ver los datos.");
            redirect(base_url("Pagos/Admin/"));
        } else {
            $dataLlamadas = $this->Cobradores_model->obtenerLlamadasPedidoCodigo($codigo);
            if ($dataLlamadas == false) {
                $this->session->set_flashdata("error", "No se encontraron Gestiones en el Cliente y Pedido en los últimos 5 días.");
                redirect(base_url("Pagos/Admin/"));
            } else {
                $datacliente = $this->Clientes_model->obtenerClienteDir($cliente);
                if ($datacliente == false) {
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

                    $a = -1;
                    foreach ($dataLlamadas as $val) {
                        $a++;
                        if ($val["color"]=="green") {
                            $pagPro = $this->Pagos_model->obtenerPagosProgramaPedidoLast($pedido);
                            if ($pagPro == false) {
                                $dataLlamadas[$a]["PagoProgramado"] = "";
                            } else {
                                $dataLlamadas[$a]["PagoProgramado"] = $pagPro[0]["FechaProgramada"];
                            }
                        } else {
                            $dataLlamadas[$a]["PagoProgramado"] = "";
                        }
                    }
                    
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

    public function Rellamar()
    {
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

    public function obtenerVolverLlamarJson()
    {
        $fecha = date("Y-m-d");
        echo $this->obtenerVolverLlamarJsonPara($fecha, $fecha);
    }

    public function obtenerVolverLlamarJsonPost()
    {
        $fechaIni = trim($this->input->post('pag_fec1'));
        $date = str_replace('/', '-', $fechaIni);
        $fechaIni = date('Y-m-d', strtotime($date));
        $fechaFin = trim($this->input->post('pag_fec2'));
        $date = str_replace('/', '-', $fechaFin);
        $fechaFin = date("Y-m-d", strtotime($date));

        $fechaIni = date("2018-09-01");
        $fechaFin = date("Y-m-d");

        echo $this->obtenerVolverLlamarJsonPara($fechaIni, $fechaFin);
    }

    public function obtenerVolverLlamarJsonPara($f1, $f2)
    {
        $data = $this->obtenerVolverLlamar($f1, $f2);
        $arreglo["data"] = [];

        if ($data != false) {
            $i = 0;
            foreach ($data as $item) {
                $fecha1 = trim($item["FechaCreacion"]);
                $dataPagos = $this->Pagos_model->obtenerPagosProgramadosPorPedido($item["Pedido"], $fecha1);
                if ($dataPagos[0]["Cuotas"] <= 0) {
                    $btn1 = "";
                    $btn2 = "";
                    $btn3 = "";
                    $btn4 = "";

                    $idPermiso = 109;
                    $action = validarPermisoAcciones($idPermiso);
                    if ($action) {
                        $btn1 = '<a href = "#ModalCall" data-toggle = "modal" title = "Reportar Llamada" onclick = "DatosModal(\'' . $item["Codigo"] . '\', \'' . $item["Pedido"] . '\', \'' . $item["Cliente"] . '\', \'' . $item["Nombre"] . '\', \'' . $item["Direccion"] . '\', \'' . $item["telefono"] . '\');"><i class = "fa fa-phone" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    }

                    $idPermiso = 98;
                    $action = validarPermisoAcciones($idPermiso);
                    if ($action) {
                        $btn2 = '<a href = "' . base_url() . 'Pagos/Generar/' . $item["Cliente"] . '/" target="_blank" title = "Pagar"><i class = "fa fa-motorcycle" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    }

                    $idPermiso = 23;
                    $action = validarPermisoAcciones($idPermiso);
                    if ($action) {
                        $btn3 = '<a href = "' . base_url() . 'Clientes/Pagos/' . $item["Pedido"] . '/"  title = "Pagos Realizados del Cliente"><i class = "fa fa-usd" aria-hidden = "true" style = "padding:5px;"></i></a>';
                    }
                
                    if ($btn1 == "" and $btn2 == "" and  $btn3 == "") {
                        $btn4 = "No tiene Permisos";
                    }

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
                        "Color" => $item["Color"],
                        "btn" => '<div class="btn-group text-center" style="margin: 0px auto;  width:100%;">' . $btn1 . $btn2 . $btn3 . $btn4 . '</div>'
                    );
                    $i++;
                }
            }
        }
        echo json_encode($arreglo);
    }

    public function obtenerVolverLlamar($f1, $f2)
    {
        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha1 = date($f1 . " 00:00:00");
        $fecha2 = date($f2 . " 23:59:59");
        $motivo = 104; //Llamar otro día
        $i = 0;
        $data = array();

        $dataVolver = $this->Cobradores_model->obtenerDevolucionLlamadasMotivoFechaPro($fecha1, $fecha2);
        if ($dataVolver == false) {
            return false;
//            $this->session->set_flashdata("error", "No se encontraron Clientes o Pedidos para VOLVER A LLAMAR el día de hoy.");
//            redirect(base_url("Pagos/Admin/"));
        } else {
            foreach ($dataVolver as $value) {
                if ($value["Motivo"] != 900) {
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
            }
            $data = $this->valPagosGestionReCall($data);

            return $data;
        }
    }

    public function valPagosGestion($dataPagos)
    {
        $fecha = date("Y-m-d") . " 00:00:00";
        $i = 0;
        foreach ($dataPagos as $value) {
            $pedido = $value["Pedido"];
            $cliente = $value["Cliente"];
            $gest = $this->Cobradores_model->obtenerLlamadasPedidoFecha($pedido, $cliente, $fecha);
            $Motivo = "Pendiente";
            $color = "";

            if ($gest != false) {
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

    public function valPagosGestionReCall($dataPagos)
    {
        $fecha = date("Y-m-d") . " 00:00:00";
        $i = 0;
        foreach ($dataPagos as $value) {
            $pedido = $value["Pedido"];
            $cliente = $value["Cliente"];
            $gest = $this->Cobradores_model->obtenerDevolucionLlamadasPedidoFechaPro($pedido, $cliente, $fecha);
            $Motivo = "Pendiente";
            $color = "";

            if ($gest != false) {
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

    public function programarPago($pag_ped, $pag_pag, $pag_fec, $pag_obs, $pag_cuo = null)
    {
        $idPermiso = 98;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $dataPedido = $this->Pedidos_model->obtenerPedidosClientePorPedido($pag_ped);
            if (isset($dataPedido) && $dataPedido == false) {
                return "El Pedido indicado no se encontró, por favor intentelo de nuevo.";
            } else {
                $pag_sal = $dataPedido[0]["Saldo"];
                $pag_cli = $dataPedido[0]["Cliente"];
                if ($pag_cuo == null) {
                    $pag_cuo = 0;
                } else {
                    $pag_cuo = $this->numCuotas($pag_ped);
                }

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
                            $modulo = "Gestión Cliente";
                            $tabla = "PagosProgramados";
                            $accion = "Programar Pago al Cliente";
                            $llave = $Pag[0]['Codigo'];
                            $cliente_log = $pag_cli;
                            $enlace = "Pagos|Validar|" . $llave;
                            $dataInsert = $dataPago;
                            $observaciones = "Cliente programa pago por un valor de " . money_format("%.0n", $pag_pag) . ".";
                            insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
 
                            //Se Crea Historial Pago
                            $this->History($pag_cli, $pag_ped, $fecha, $user, "Programar Pago", $pag_sal, $pag_cuo, (intval($pag_sal) - intval($pag_pag)), $pag_pag, $pag_obs);

                            return 1;
                        }
                    } else {
                        return "Ocurrió un problema al programar el pago, por favor intentelo de nuevo.";
                    }
                } catch (Exception $e) {
                    return 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
                }
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

    public function numCuotas($pedido)
    {
        $dataPagos = $this->Pagos_model->obtenerPagosPedido($pedido);
        $num = 1;
        if (isset($dataPagos) && $dataPagos != false) {
            for ($i = 0; $i < count($dataPagos); $i++) {
                $num++;
            }
        }
        return $num;
    }
}