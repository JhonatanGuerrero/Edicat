<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Clientes';
        $this->load->model('Clientes_model');
        $this->load->model('TiposDocumentos_model');
        $this->load->model('Estados_model');
        $this->load->model('Direcciones_model');
        $this->load->model('Productos_model');
        $this->load->model('Tarifas_model');
        $this->load->model('Vendedores_model');
        $this->load->model('Eventos_model');
        $this->load->model('Referencias_model');
        $this->load->model('Pedidos_model');
        $this->load->model('Pagos_model');
        $this->load->model('Cobradores_model');
        $this->load->model('Usuarios_model');

        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI] );
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        } else {
            Deuda();
        }
    }

    public function index() {
        redirect(site_url($this->viewControl . "/Admin/"));
    } 
      
    public function Admin() {
        $idPermiso = 12;
        $page = validarPermisoPagina($idPermiso);

        $dataCliente = $this->Clientes_model->obtenerClientesNR();
        $dataCobradores = $this->Cobradores_model->obtenerCobradores();
        
        $PerfilId = "";

        if ($dataCliente != false) {
            $i = 0;
            $usuario = $this->session->userdata('Codigo');
            $PerfilId = $this->session->userdata('PerfilId');
            $permisos = $this->SearchPermissions();  
            foreach ($dataCliente as $value) {
                $dataUserCliente = true;  
                if (!$permisos["TodosClientes"]) 
                    $dataUserCliente = $this->Clientes_model->ClienteUsuarioBool($value["Codigo"], $usuario);
                else
                    $dataUserCliente = true; 
 
                if ($dataUserCliente) { 
                    $pedido = $this->Pedidos_model->obtenerPedidoPorCliente($value["Codigo"]);
                    if ($pedido != false) {
                        $dataCliente[$i]["Pedido"] = $pedido[0]["Codigo"];
                        $dataCliente[$i]["Saldo"] = $pedido[0]["Saldo"];
                        $dataCliente[$i]["PaginaFisica"] = $pedido[0]["PaginaFisica"];
                        $dataCliente[$i]["Nombre"] = ucwords(strtolower($dataCliente[$i]["Nombre"]));
                        $cuotas = $this->Pagos_model->obtenerPagosPorPedido($pedido[0]["Codigo"]);
                        if ($cuotas != FALSE) {
                            $cuotas = $cuotas[0]["Cuotas"];
                        }
                        $dataCliente[$i]["Cuotas"] = $cuotas;
                    }
                } else {
                    unset($dataCliente[$i]);
                }
                $i++;
            }
        }
        else {
            if ($PerfilId >= 103) {
                $this->session->set_flashdata("error", "Usted no tiene Clientes Asociados.");
            } else {
                $this->session->set_flashdata("error", "Aun no hay Clientes/Pedidos creados.");
            }
        }

        $data = new stdClass();
        $data->Controller = "Clientes";
        $data->title = "Clientes al día";
        $data->subtitle = "Listado de Clientes";
        $data->contenido = $this->viewControl . '/Admin';
        $data->ListaDatos = $dataCliente;
        $data->Lista1 = $dataCobradores;

        $this->load->view('frontend', $data);
    }

    public function dataClienteHover() {
        $id = trim($this->input->post('id'));
        $dataClientes = $this->Clientes_model->obtenerClienteDir($id);
        //var_dump($dataClientes);
        if (isset($dataClientes) && $dataClientes != FALSE) {
            $output = '';
            foreach ($dataClientes as $cliente) {
                $direccion = $cliente["Dir"];
                $direccion = ($cliente["Etapa"] != "") ? $direccion . " ET " . $cliente["Etapa"] : $direccion;
                $direccion = ($cliente["Torre"] != "") ? $direccion . " TO " . $cliente["Torre"] : $direccion;
                $direccion = ($cliente["Apartamento"] != "") ? $direccion . " AP " . $cliente["Apartamento"] : $direccion;
                $direccion = ($cliente["Manzana"] != "") ? $direccion . " MZ " . $cliente["Manzana"] : $direccion;
                $direccion = ($cliente["Interior"] != "") ? $direccion . " IN " . $cliente["Interior"] : $direccion;
                $direccion = ($cliente["Casa"] != "") ? $direccion . " CA " . $cliente["Casa"] : $direccion;
                $telefono = $cliente["Telefono1"];
                $telefono = ($cliente["Telefono2"] != "") ? $telefono . " - " . $cliente["Telefono2"] : $telefono;
                $telefono = ($cliente["Telefono3"] != "") ? $telefono . " - " . $cliente["Telefono3"] : $telefono;

                $output = '<br><p>Nombre: ' . $cliente["Nombre"] . '</p>' .
                        '<p>Direccion: ' . $direccion . '</p>' .
                        '<p>Teléfono: ' . $telefono . '</p>' .
                        '<p>Barrio: ' . $cliente["Barrio"] . '</p>' .
                        '<p>Estado: ' . $cliente["EstNombre"] . '</p>';
            }

            echo $output;
        } else {
            echo "<p>Cliente No encontrado. Recarge la página.</p>";
        }
    }

    public function Buscar() {
        $idPermiso = 11;
        $page = validarPermisoPagina($idPermiso);

        $dataEstados = $this->Estados_model->obtenerEstadosPor(102);
        $dataCobradores = $this->Cobradores_model->obtenerCobradores();
        if (isset($dataCobradores) && $dataCobradores != FALSE) {
            $data = new stdClass();
            $data->Controller = "Clientes";
            $data->title = "Buscar Clientes";
            $data->subtitle = "Filtro de Búsqueda - Clientes";
            $data->contenido = $this->viewControl . '/Buscar';
            $data->Lista1 = $dataEstados;
            $data->Lista2 = $dataCobradores;

            $this->load->view('frontend', $data);
        } else {
            $this->session->set_flashdata("error", "No se encontraron datos de los Cobradores.");
            redirect(base_url());
        }
    }

    public function SearchJsonAsignado() {
        $nombre = ucwords(strtolower(trim($this->input->post('nombre'))));
        $estado = trim($this->input->post('estado'));
        $usuario = "100";// trim($this->input->post('usuario')); 

        $data = $this->Clientes_model->searchClienteAsignado($nombre, $estado, $usuario);
        $arreglo["data"] = [];
        $permisos = $this->SearchPermissions(); 

        if (isset($data) && $data != FALSE) {
            $i = 0;
            foreach ($data as $item) {
                $arreglo["data"][$i] = $this->crearArregloBuscar($item, $permisos);
                $i++;
            }
        }
        echo json_encode($arreglo);
    }

    public function SearchJson() {
        $nombre = ucwords(strtolower(trim($this->input->post('nombre'))));
        $cedula = trim($this->input->post('cedula'));
        $direccion = trim($this->input->post('direccion'));
        $telefono = trim($this->input->post('telefono'));
        $estado = trim($this->input->post('estado'));
        $ubicacion = trim($this->input->post('ubicacion'));

        $data = $this->Clientes_model->searchCliente($nombre, $cedula, $direccion, $telefono, $estado, $ubicacion);
        $arreglo["data"] = [];
        $permisos = $this->SearchPermissions();  

        if (isset($data) && $data != FALSE) {
            $i = 0;
            foreach ($data as $item) {
                $arreglo["data"][$i] = $this->crearArregloBuscar($item, $permisos);
                $i++;
            }
        }
        echo json_encode($arreglo);
    }

    public function SearchPermissions()
    { 
        $permisos = [];  

        //Consultar Cliente
        $idPermiso = 15;
        $permisos["Consultar"] = validarPermisoAcciones($idPermiso);  
        //Cambio de Fecha de Cobro
        $idPermiso = 16;
        $permisos["CambioFecha"] = validarPermisoAcciones($idPermiso);  
        //Cambio de Tarifa
        $idPermiso = 17;
        $permisos["CambioTarifa"] = validarPermisoAcciones($idPermiso);  
        //Hacer Recibo
        $idPermiso = 19;
        $permisos["Generar"] = validarPermisoAcciones($idPermiso);  
        //Pagos Realizados del Cliente
        $idPermiso = 23;
        $permisos["Pagos"] = validarPermisoAcciones($idPermiso);  
        //Devolución del Cliente
        $idPermiso = 90;
        $permisos["Devolucion"] = validarPermisoAcciones($idPermiso);  
        //Ver solo Clientes Propios
        $idPermiso = 103;
        $permisos["TodosClientes"] = validarPermisoAcciones($idPermiso);  
            
        return $permisos;
    }
    
    public function crearArregloBuscar($item, $permisos) 
    {  
        $btn1 = "";
        $btn2 = "";
        $btn3 = "";
        $btn4 = "";
        $btn5 = "";
        $btn6 = "";
        $btnOpciones = "Sin permisos";

        $direccion = $item["Dir"];
        $direccion = ($item["Etapa"] != "") ? $direccion . " ET " . $item["Etapa"] : $direccion;
        $direccion = ($item["Torre"] != "") ? $direccion . " TO " . $item["Torre"] : $direccion;
        $direccion = ($item["Apartamento"] != "") ? $direccion . " AP " . $item["Apartamento"] : $direccion;
        $direccion = ($item["Manzana"] != "") ? $direccion . " MZ " . $item["Manzana"] : $direccion;
        $direccion = ($item["Interior"] != "") ? $direccion . " IN " . $item["Interior"] : $direccion;
        $direccion = ($item["Casa"] != "") ? $direccion . " CA " . $item["Casa"] : $direccion;

        $telefono = $item["Telefono1"];
        $telefono = ($item["Telefono2"] != "") ? $telefono . " - " . $item["Telefono2"] : $telefono;
        $cuota = 0;
        $num = $this->Pagos_model->ultimaCuota($item['Pedido']);
        if ($num != FALSE) {
            $cuota = $num[0]["Cuota"];
        }

        $usuario = $this->session->userdata('Codigo');
        $PerfilId = $this->session->userdata('PerfilId');
        $dataPedidos = $this->Pedidos_model->obtenerPedidosDeben();
        $dataCobradores = $this->Cobradores_model->obtenerCobradores();
        $dataUserCliente = true;
         
        if (!$permisos["TodosClientes"]) 
            $dataUserCliente = $this->Clientes_model->ClienteUsuarioBool($item['Cliente'], $usuario); 
        else
            $dataUserCliente = true;
 
        if ($permisos["Consultar"])
            $btn1 = "<a href='" . base_url() . "Clientes/Consultar/" . $item['Cliente'] . "/' target='_blank' title='Consultar Cliente'><i class='fa fa-search' aria-hidden='true' style='padding:5px;'></i></a>";
        
        $dataUserCliente = true;
        if ($dataUserCliente) { 
            if ($permisos["CambioFecha"])
                $btn2 = "<a href='" . base_url() . "Clientes/CambioFecha/" . $item['Cliente'] . "/' target='_blank' title='Cambio de Fecha de Cobro'><i class='fa fa-calendar' aria-hidden='true' style='padding:5px;'></i></a>";
            if ($permisos["CambioTarifa"]) 
                $btn3 = "<a href='" . base_url() . "Clientes/CambioTarifa/" . $item['Cliente'] . "/' target='_blank' title='Cambio de Tarifa'><i class='fa fa-refresh' aria-hidden='true' style='padding:5px;'></i></a>";
            if ($permisos["Generar"]) 
                $btn4 = "<a href='" . base_url() . "Pagos/Generar/" . $item['Cliente'] . "/' target='_blank' title='Hacer Recibo'><i class='fa fa-motorcycle' aria-hidden='true' style='padding:5px;'></i></a>";
            if ($permisos["Pagos"])
                $btn5 = "<a href='" . base_url() . "Clientes/Pagos/" . $item['Cliente'] . "/' target='_blank' title='Pagos Realizados del Cliente'><i class='fa fa-usd' aria-hidden='true' style='padding:5px;'></i></a>";
            if ($permisos["Devolucion"])
                $btn6 = "<a href='#ModalDevol' data-toggle='modal' title='Devolución del Cliente' onclick='DatosModal(\"" . $item['Pedido'] . "\", \"" . $item['Cliente'] . "\", \"" . $item['Nombre'] . "\", \"" . $item['Saldo'] . "\", \"" . $cuota . "\");'><i class='fa fa-reply-all' aria-hidden='true' style='padding:5px;'></i></a>";
        }    

        $diacobro = "";
        if ($item["DiaCobro"] != NULL || $item["DiaCobro"] != "") {
            $diacobro = date("d/m/Y", strtotime($item["DiaCobro"]));
        } else {
            $diacobro = "Sin Fecha";
        }
 
        if (trim($btn1) != "" or trim($btn2) != "" or trim($btn3) != "" or trim($btn4) != "" or trim($btn5) != "" or trim($btn6) != "") { 
            $btnOpciones = $btn1 . $btn2 . $btn3 . $btn4 . $btn5 . $btn6;
        } 

        $arreglo = array(
            "Nombre" => $item["Nombre"],
            "Direccion" => $direccion,
            "telefono" => $telefono,
            "saldo" => money_format("%.0n", $item["Saldo"]),
            "DiaCobro" => $diacobro,
            "Estado" => $item["EstNombre"],
            "PaginaFisica" => $item["PaginaFisica"],
            "btn" => '<div class="btn-group text-center" style="margin: 0px auto;  width:100%;">' . $btnOpciones . '</div>'
        );

        return $arreglo;
    }

    public function Crear() {
        $idPermiso = 88;
        $page = validarPermisoPagina($idPermiso);

        $dataTipDoc = $this->TiposDocumentos_model->obtenerTiposDocumentos();
        if (isset($dataTipDoc) && $dataTipDoc != FALSE) {
            $dataTiposVivienda = $this->Direcciones_model->obtenerTiposVivienda();
            if (isset($dataTiposVivienda) && $dataTiposVivienda != FALSE) {
                $dataProductos = $this->Productos_model->obtenerProductos();
                if (isset($dataProductos) && $dataProductos != FALSE) {
                    $dataTarifas = $this->Tarifas_model->obtenerTarifas();
                    if (isset($dataTarifas) && $dataTarifas != FALSE) {
                        $dataVendedores = $this->Vendedores_model->obtenerVendedores();
                        if (isset($dataVendedores) && $dataVendedores != FALSE) {
                            $dataEventos = $this->Eventos_model->obtenerEventosIGLBARR();
                            if (isset($dataEventos) && $dataEventos != FALSE) {
                                $iglesias = array("");
                                foreach ($dataEventos as $value) {
                                    array_push($iglesias, $value["Iglesia"]);
                                }
                                $iglesias = array_unique($iglesias);
                                $barrios = array("");
                                foreach ($dataEventos as $value) {
                                    array_push($barrios, $value["Barrio"]);
                                }
                                $barrios = array_unique($barrios);

                                $data = new stdClass();
                                $data->Controller = "Clientes";
                                $data->title = "Creación de Cliente";
                                $data->subtitle = "Cliente/Pedido Nuevo";
                                $data->contenido = $this->viewControl . '/Crear';
                                $data->Lista1 = $dataTipDoc;
                                $data->Lista2 = $dataTiposVivienda;
                                $data->Lista4 = $dataProductos;
                                $data->Lista5 = $dataTarifas;
                                $data->Lista6 = $dataVendedores;
                                $data->Lista7 = json_encode($iglesias);
                                $data->Lista8 = json_encode($barrios);

                                $this->load->view('frontend', $data);
                            } else {
                                $this->session->set_flashdata("error", "No se tienen datos sobre 'Eventos'");
                                redirect(base_url("/Eventos/Admin/"));
                            }
                        } else {
                            $this->session->set_flashdata("error", "No se tienen datos sobre 'Vendedores'");
                            redirect(base_url("/Vendedores/Admin/"));
                        }
                    } else {
                        $this->session->set_flashdata("error", "No se tienen datos sobre 'Tarifas'");
                        redirect(base_url("/Tarifas/Admin/"));
                    }
                } else {
                    $this->session->set_flashdata("error", "No se tienen datos sobre 'Productos'");
                    redirect(base_url("/Mantenimiento/TiposVivienda/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se tienen datos sobre 'Tipos de Vivienda'");
                redirect(base_url("/Mantenimiento/TiposVivienda/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se tienen datos sobre 'Tipos de Documentos'");
            redirect(base_url("/Mantenimiento/TiposDocumentos/Admin/"));
        }
    }

    public function NewClient() {
        $idPermiso = 91;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            //Datos Personales
            $cli_nom = ucwords(strtolower(trim($this->input->post('cli_nom'))));
            $cli_tipdoc = trim($this->input->post('cli_tipdoc'));
            $cli_doc = trim($this->input->post('cli_doc'));
            //Ubicacion
            $cli_dir = ucwords(strtolower(trim($this->input->post('cli_dir'))));
            $cli_eta = trim($this->input->post('cli_eta'));
            $cli_tor = trim($this->input->post('cli_tor'));
            $cli_apto = trim($this->input->post('cli_apto'));
            $cli_manz = trim($this->input->post('cli_manz'));
            $cli_int = trim($this->input->post('cli_int'));
            $cli_casa = trim($this->input->post('cli_casa'));
            $cli_bar = ucwords(strtolower(trim($this->input->post('cli_bar'))));
            $cli_tipviv = trim($this->input->post('cli_tipviv'));
            //Telefonos
            $cli_tel1 = trim($this->input->post('cli_tel1'));
            $cli_tel2 = trim($this->input->post('cli_tel2'));
            $cli_tel3 = trim($this->input->post('cli_tel3'));
            //Referencias
            $cli_numRef = trim($this->input->post('cli_numRef'));
            $cli_nomrf1 = ucwords(strtolower(trim($this->input->post('cli_nomrf1'))));
            $cli_telrf1 = trim($this->input->post('cli_telrf1'));
            $cli_paren1 = ucwords(strtolower(trim($this->input->post('cli_paren1'))));
            $cli_nomrf2 = ucwords(strtolower(trim($this->input->post('cli_nomrf2'))));
            $cli_telrf2 = trim($this->input->post('cli_telrf2'));
            $cli_paren2 = ucwords(strtolower(trim($this->input->post('cli_paren2'))));
            $cli_nomrf3 = ucwords(strtolower(trim($this->input->post('cli_nomrf3'))));
            $cli_telrf3 = trim($this->input->post('cli_telrf3'));
            $cli_paren3 = ucwords(strtolower(trim($this->input->post('cli_paren3'))));
            //Productos Adquiridos        
            $cli_cant1 = trim($this->input->post('cli_cant1'));
            $cli_prod1 = trim($this->input->post('cli_prod1'));
            $cli_val1 = trim($this->input->post('cli_val1'));
            $cli_nomprod1 = trim($this->input->post('cli_nomprod1'));

            //Pago (Pedido)
            $cli_valtotal = trim($this->input->post('cli_valtotal'));
            $cli_priCobro = trim($this->input->post('cli_priCobro') . " 00:00:00");
            $cli_priCobro = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $cli_priCobro);
            $cli_tar1 = trim($this->input->post('cli_tar1'));
            $cli_nomTar = trim($this->input->post('cli_nomTar'));
            $cli_numCuo = trim($this->input->post('cli_numCuo'));
            $cli_valCuo = trim($this->input->post('cli_valCuo'));
            $cli_totalPag = trim($this->input->post('cli_totalPag'));
            $cli_abono = trim($this->input->post('cli_abono'));
            //Observaciones
            $cli_Ven = trim($this->input->post('cli_Ven'));
            $cli_IglEve = ucwords(strtolower(trim($this->input->post('cli_IglEve'))));
            $cli_BarEve = ucwords(strtolower(trim($this->input->post('cli_BarEve'))));
            $cli_FecEve = trim($this->input->post('cli_FecEve') . " 00:00:00");
            $cli_FecEve = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $cli_FecEve);
            $cli_PagEve = trim($this->input->post('cli_PagEve'));
            $cli_Obs = ucfirst(strtolower(trim($this->input->post('cli_Obs'))));

            //Datos Auditoría
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");
            $errores = 0;
            $lblErrores = "";

            //Crear Clientes
            $dataCliente = array(
                "Nombre" => $cli_nom,
                "TipoDocumento" => $cli_tipdoc,
                "Documento" => $cli_doc,
                "Direccion" => 1,
                "Telefono1" => $cli_tel1,
                "Telefono2" => $cli_tel2,
                "Telefono3" => $cli_tel3,
                "Estado" => 104,
                "Observaciones" => $cli_Obs,
                "Habilitado" => 1,
                "UsuarioCreacion" => $user,
                "FechaCreacion" => $fecha
            );
            //var_dump($dataCliente);
            try {
                if ($this->Clientes_model->save($dataCliente)) {
                    $Cli = $this->Clientes_model->obtenerClienteDoc($cli_doc, "DESC");
                    if ($Cli) {
                        $dataCliente['Codigo'] = $Cli[0]['Codigo'];
                        $modulo = "Creación Cliente";
                        $tabla = "Clientes";
                        $accion = "Crear Cliente";
                        $llave = $Cli[0]['Codigo'];
                        $sql = LogSave($dataCliente, $modulo, $tabla, $accion, $llave);
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


            if ($errores > 0) {
                echo $lblErrores;
            } else {
                //Crear Direccion
                $dataDireccion = array(
                    "Direccion" => $cli_dir,
                    "Etapa" => $cli_eta,
                    "Torre" => $cli_tor,
                    "Apartamento " => $cli_apto,
                    "Manzana" => $cli_manz,
                    "Interior" => $cli_int,
                    "Casa" => $cli_casa,
                    "Barrio" => $cli_bar,
                    "TipoVivienda" => $cli_tipviv,
                    "Habilitado" => 1,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );

                try {
                    if ($this->Direcciones_model->save($dataDireccion)) {
                        $dir = $this->Direcciones_model->obtenerDireccionPorDirUserFec($cli_dir, $user, $fecha);
                        if ($dir) {
                            $dataDireccion['Codigo'] = $dir[0]['Codigo'];

                            $dataTemp = array(
                                "Direccion" => $dataDireccion['Codigo']
                            );
                            $this->Clientes_model->update($Cli[0]['Codigo'], $dataTemp);

                            $modulo = "Creación Cliente";
                            $tabla = "Direcciones";
                            $accion = "Crear Dirección";
                            $llave = $Cli[0]['Codigo'];
                            $sql = LogSave($dataDireccion, $modulo, $tabla, $accion, $llave);
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
            }

            if ($errores > 0) {
                echo $lblErrores;
            } else {
                //Crear Referencias 1
                $dataReferencia1 = array(
                    "Nombres" => $cli_nomrf1,
                    "Telefono" => $cli_telrf1,
                    "Parentesco" => $cli_paren1,
                    "Habilitado" => 1,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );

                try {
                    if ($this->Referencias_model->save($dataReferencia1)) {
                        $Ref1 = $this->Referencias_model->obtenerReferenciasCodUserFec($cli_nomrf1, $user, $fecha);
                        if ($Ref1) {
                            $dataReferencia1['Codigo'] = $Ref1[0]['Codigo'];
                            $modulo = "Creación Cliente";
                            $tabla = "Referencias";
                            $accion = "Crear Referencia";
                            $llave = $Cli[0]['Codigo'];
                            $sql = LogSave($dataReferencia1, $modulo, $tabla, $accion, $llave);

                            $dataRefCliente1 = array(
                                "Cliente" => $Cli[0]['Codigo'],
                                "Referencia" => $Ref1[0]['Codigo'],
                                "Habilitado" => 1,
                                "UsuarioCreacion" => $user,
                                "FechaCreacion" => $fecha
                            );

                            if ($this->Referencias_model->saveRefCli($dataRefCliente1)) {
                                $RefCli1 = $this->Referencias_model->obtenerRefClienteCodUserFec($Cli[0]['Codigo'], $Ref1[0]['Codigo'], $user, $fecha);
                                if ($RefCli1) {
                                    $dataRefCliente1['Codigo'] = $RefCli1[0]['Codigo'];
                                    $modulo = "Creación Cliente";
                                    $tabla = "ReferenciasCliente";
                                    $accion = "Vincular Cliente y Referencia";
                                    $llave = $Cli[0]['Codigo'];
                                    $sql = LogSave($dataRefCliente1, $modulo, $tabla, $accion, $llave);
                                } else {
                                    $errores++;
                                    $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                                }
                            } else {
                                $errores++;
                                $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                            }
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
            }

            if ($errores > 0) {
                echo $lblErrores;
            } else {
                //Crear Referencias 2
                $dataReferencia2 = array(
                    "Nombres" => $cli_nomrf2,
                    "Telefono" => $cli_telrf2,
                    "Parentesco" => $cli_paren2,
                    "Habilitado" => 1,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );

                try {
                    if ($this->Referencias_model->save($dataReferencia2)) {
                        $Ref2 = $this->Referencias_model->obtenerReferenciasCodUserFec($cli_nomrf2, $user, $fecha);
                        if ($Ref2) {
                            $dataReferencia2['Codigo'] = $Ref2[0]['Codigo'];
                            $modulo = "Creación Cliente";
                            $tabla = "Referencias";
                            $accion = "Crear Referencia";
                            $llave = $Cli[0]['Codigo'];
                            $sql = LogSave($dataReferencia2, $modulo, $tabla, $accion, $llave);

                            $dataRefCliente2 = array(
                                "Cliente" => $Cli[0]['Codigo'],
                                "Referencia" => $Ref2[0]['Codigo'],
                                "Habilitado" => 1,
                                "UsuarioCreacion" => $user,
                                "FechaCreacion" => $fecha
                            );

                            if ($this->Referencias_model->saveRefCli($dataRefCliente2)) {
                                $RefCli2 = $this->Referencias_model->obtenerRefClienteCodUserFec($Cli[0]['Codigo'], $Ref2[0]['Codigo'], $user, $fecha);
                                if ($RefCli2) {
                                    $dataRefCliente2['Codigo'] = $RefCli2[0]['Codigo'];
                                    $modulo = "Creación Cliente";
                                    $tabla = "ReferenciasCliente";
                                    $accion = "Vincular Cliente y Referencia";
                                    $llave = $Cli[0]['Codigo'];
                                    $sql = LogSave($dataRefCliente2, $modulo, $tabla, $accion, $llave);
                                } else {
                                    $errores++;
                                    $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                                }
                            } else {
                                $errores++;
                                $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                            }
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
            }

            if ($errores > 0) {
                echo $lblErrores;
            } else {
                if ($cli_nomrf3 != "") {
                    //Crear Referencias 3
                    $dataReferencia3 = array(
                        "Nombres" => $cli_nomrf3,
                        "Telefono" => $cli_telrf3,
                        "Parentesco" => $cli_paren3,
                        "Habilitado" => 1,
                        "UsuarioCreacion" => $user,
                        "FechaCreacion" => $fecha
                    );

                    try {
                        if ($this->Referencias_model->save($dataReferencia3)) {
                            $Ref3 = $this->Referencias_model->obtenerReferenciasCodUserFec($cli_nomrf3, $user, $fecha);
                            if ($Ref3) {
                                $dataReferencia3['Codigo'] = $Ref3[0]['Codigo'];
                                $modulo = "Creación Cliente";
                                $tabla = "Referencias";
                                $accion = "Crear Referencia";
                                $llave = $Cli[0]['Codigo'];
                                $sql = LogSave($dataReferencia3, $modulo, $tabla, $accion, $llave);

                                $dataRefCliente3 = array(
                                    "Cliente" => $Cli[0]['Codigo'],
                                    "Referencia" => $Ref3[0]['Codigo'],
                                    "Habilitado" => 1,
                                    "UsuarioCreacion" => $user,
                                    "FechaCreacion" => $fecha
                                );

                                if ($this->Referencias_model->saveRefCli($dataRefCliente3)) {
                                    $RefCli3 = $this->Referencias_model->obtenerRefClienteCodUserFec($Cli[0]['Codigo'], $Ref3[0]['Codigo'], $user, $fecha);
                                    if ($RefCli3) {
                                        $dataRefCliente3['Codigo'] = $RefCli3[0]['Codigo'];
                                        $modulo = "Creación Cliente";
                                        $tabla = "ReferenciasCliente";
                                        $accion = "Vincular Cliente y Referencia";
                                        $llave = $Cli[0]['Codigo'];
                                        $sql = LogSave($dataRefCliente3, $modulo, $tabla, $accion, $llave);
                                    } else {
                                        $errores++;
                                        $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                                    }
                                } else {
                                    $errores++;
                                    $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                                }
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
                }
            }

            if ($errores > 0) {
                echo $lblErrores;
            } else {
                //Validar Evento
                $cli_Eve = 101;
                $evento = $this->Eventos_model->obtenerEventoVendIglBarFec($cli_Ven, $cli_IglEve, $cli_BarEve, $cli_FecEve);
                if (isset($evento) && $evento != FALSE) {
                    $cli_Eve = $evento[0]["Codigo"];
                } else {
                    $evento = array(
                        "Vendedor" => $cli_Ven,
                        "Iglesia" => $cli_IglEve,
                        "Barrio" => $cli_BarEve,
                        "Fecha" => $cli_FecEve,
                        "Habilitado" => 1,
                        "UsuarioCreacion" => $user,
                        "FechaCreacion" => $fecha
                    );
                    if ($this->Eventos_model->save($evento)) {
                        $dataEvento = $this->Eventos_model->obtenerEventoVendIglBarFec($cli_Ven, $cli_IglEve, $cli_BarEve, $cli_FecEve);
                        $cli_Eve = $dataEvento[0]["Codigo"];
                        $evento['Codigo'] = $dataEvento[0]["Codigo"];
                        $modulo = "Creación Cliente";
                        $tabla = "Eventos";
                        $accion = "Crear evento";
                        $llave = $Cli[0]['Codigo'];
                        $sql = LogSave($evento, $modulo, $tabla, $accion, $llave);
                    }
                }

                //Crear Pedido
                $dataPedido = array(
                    "Cliente" => $Cli[0]['Codigo'],
                    "Valor" => $cli_totalPag,
                    "Tarifa" => $cli_tar1,
                    "DiaCobro" => $cli_priCobro,
                    "Estado" => 110,
                    "Evento" => $cli_Eve,
                    "Vendedor" => $cli_Ven,
                    "FechaPedido" => $fecha,
                    "Saldo" => $cli_totalPag,
                    "PaginaFisica" => $cli_PagEve,
                    "Observaciones" => "Se crea Pedido desde el módulo de Clientes. \nCliente: " . $cli_nom . "\nTarifa Aplicada: " . $cli_nomTar
                    . "\nTotal a Pagar: " . money_format("%.0n", $cli_totalPag) . "\nCuotas: " . $cli_numCuo
                    . "\nValor Cuotas: " . money_format("%.0n", $cli_valCuo) . "\nPrimer Pago: " . $cli_priCobro . "\n "
                    . "\nObservación automática.",
                    "Habilitado" => 1,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );

                try {
                    if ($this->Pedidos_model->save($dataPedido)) {
                        $ped = $this->Pedidos_model->obtenerPedidoCliValUserFec($Cli[0]['Codigo'], $cli_priCobro, $user, $fecha);
                        if ($ped) {
                            $dataPedido['Codigo'] = $ped[0]['Codigo'];
                            $modulo = "Creación Cliente";
                            $tabla = "Pedidos";
                            $accion = "Crear Pedido";
                            $llave = $Cli[0]['Codigo'];
                            $sql = LogSave($dataPedido, $modulo, $tabla, $accion, $llave);
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
            }

            if ($errores > 0) {
                echo $lblErrores;
            } else {
                //Crear ProductosPedido
                $dataPedidoPro1 = array(
                    "Pedido" => $ped[0]['Codigo'],
                    "Cantidad" => $cli_cant1,
                    "Producto" => $cli_prod1,
                    "Valor" => $cli_val1,
                    "Habilitado" => 1,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );

                try {
                    if ($this->Pedidos_model->saveProPed($dataPedidoPro1)) {
                        $pedPro1 = $this->Pedidos_model->obtenerPedidoProUserFec($ped[0]['Codigo'], $cli_prod1, $user, $fecha);
                        if ($pedPro1) {
                            $dataPedidoPro1['Codigo'] = $pedPro1[0]['Codigo'];
                            $dataPedidoPro1['Observaciones'] = "Se vincula el producto: " . $cli_nomprod1 . " al Pedido " . $pedPro1[0]['Codigo']
                                    . " del Cliente " . $cli_nom . ". \n"
                                    . "Cantidad del Producto: " . $cli_cant1 . ". \n"
                                    . "Valor del Producto: " . money_format("%.0n", $cli_prod1) . ". \n"
                                    . "\nObservación automática.";
                            $modulo = "Creación Cliente";
                            $tabla = "ProductosPedidos";
                            $accion = "Vincular Productos y Pedido";
                            $llave = $Cli[0]['Codigo'];
                            $sql = LogSave($dataPedidoPro1, $modulo, $tabla, $accion, $llave);
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
            }

            if ($errores > 0) {
                echo $lblErrores;
            } else {
                if ($cli_abono > 0 && $cli_abono != "") {
                    //Si hay abono
                    $saldo = (intval($cli_totalPag) - intval($cli_abono));
                    $dataAbono = array(
                        "Cliente" => $Cli[0]['Codigo'],
                        "Pedido" => $ped[0]['Codigo'],
                        "Cuota" => 1,
                        "Pago" => $cli_abono,
                        "FechaPago" => $fecha,
                        "TotalPago" => $cli_totalPag,
                        "Observaciones" => "Abono por valor de: " . money_format("%.0n", $cli_abono) . "\n"
                        . "Abono realizado al momento del pedido.\n"
                        . "Saldo Actual: " . money_format("%.0n", ($saldo))
                        . "\nObservación automática.",
                        "Habilitado" => 1,
                        "UsuarioCreacion" => $user,
                        "FechaCreacion" => $fecha
                    );

                    try {
                        if ($this->Pagos_model->save($dataAbono)) {
                            $dataActPedido = array(
                                "Saldo" => (intval($cli_totalPag) - intval($cli_abono)),
                                "Estado" => 111,
                                "FechaUltimoPago" => $fecha,
                                "UsuarioModificacion" => $user,
                                "FechaModificacion" => $fecha
                            );
                            if ($this->Pedidos_model->update($ped[0]['Codigo'], $dataActPedido)) {
                                $abono = $this->Pagos_model->obtenerPagosPedidoUserFec($Cli[0]['Codigo'], $ped[0]['Codigo'], $user, $fecha);
                                if ($abono) {
                                    $dataAbono['Codigo'] = $abono[0]['Codigo'];
                                    $modulo = "Pagar Pedido";
                                    $tabla = "Pagos";
                                    $accion = "Crear Abono";
                                    $llave = $ped[0]['Codigo'];
                                    //Se Crea Historial Pago
                                    $this->History($Cli[0]['Codigo'], $ped[0]['Codigo'], $fecha, $user, "Primer Abono", intval($cli_totalPag), 1, (intval($cli_totalPag) - intval($cli_abono)), intval($cli_abono), $dataAbono["Observaciones"]);
                                    $sql = LogSave($dataAbono, $modulo, $tabla, $accion, $llave);
                                } else {
                                    $errores++;
                                    $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                                }
                            } else {
                                $errores++;
                                $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                            }
                        } else {
                            $errores++;
                            $lblErrores = "No se pudo guardar, por favor intentelo de nuevo.";
                        }
                        $this->asignarClienteUsuario($this->session->userdata('Codigo'), $Cli[0]['Codigo']);
                    } catch (Exception $e) {
                        $errores++;
                        $lblErrores = 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
                    }
                }
            }
            $this->asignarClienteUsuario($this->session->userdata('Codigo'), $Cli[0]['Codigo']);
            if ($errores > 0) {
                echo $lblErrores;
            } else {
                echo 1;
            }
        } else {
            echo "No tiene permisos para Crear un Cliente/Pedido";
        }
    }

    public function asignarClienteUsuario($usuario, $cliente) {
        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha = date("Y-m-d H:i:s");

        $dataCliUsu = array(
            "Usuario" => $usuario,
            "Cliente" => $cliente,
            "Habilitado" => 1,
            "UsuarioCreacion" => $user,
            "FechaCreacion" => $fecha
        );

        ($this->Clientes_model->saveCliUsu($dataCliUsu));
    }

    public function Consultar($cliente) {
        $idPermiso = 15;
        $page = validarPermisoPagina($idPermiso);

        if (isset($cliente)) {
            $dataClientes = $this->Clientes_model->obtenerClienteDir($cliente);
            if (isset($dataClientes) && $dataClientes != FALSE) {
                $dataTipDoc = $this->TiposDocumentos_model->obtenerTiposDocumentoCod($dataClientes[0]["TipoDocumento"]);
                if (isset($dataTipDoc) && $dataTipDoc != FALSE) {
                    $dataTiposVivienda = $this->Direcciones_model->obtenerTiposVivienda();
                    if (isset($dataTiposVivienda) && $dataTiposVivienda != FALSE) {
                        $dataReferencias = $this->Referencias_model->obtenerRefClienteData($cliente);
                        $dataRef = array();
                        if (isset($dataReferencias) && $dataReferencias != FALSE) {
                            //Referencias (Encadenar)
                            $i = 0;
                            foreach ($dataReferencias as $value) {
                                switch ($i) {
                                    case 0:
                                        $dataRef["cod1"] = $value[0]["Codigo"];
                                        $dataRef["nom1"] = $value[0]["Nombres"];
                                        $dataRef["tel1"] = $value[0]["Telefono"];
                                        $dataRef["par1"] = $value[0]["Parentesco"];
                                        $i++;
                                        break;
                                    case 1:
                                        $dataRef["cod2"] = $value[0]["Codigo"];
                                        $dataRef["nom2"] = $value[0]["Nombres"];
                                        $dataRef["tel2"] = $value[0]["Telefono"];
                                        $dataRef["par2"] = $value[0]["Parentesco"];
                                        $i++;
                                        break;
                                    case 2:
                                        $dataRef["cod3"] = $value[0]["Codigo"];
                                        $dataRef["nom3"] = $value[0]["Nombres"];
                                        $dataRef["tel3"] = $value[0]["Telefono"];
                                        $dataRef["par3"] = $value[0]["Parentesco"];
                                        $i++;
                                        break;

                                    default:
                                        break;
                                }
                            }
                        }

                        $dataPedido = $this->Pedidos_model->obtenerPedidosCliente($cliente);
                        if (isset($dataPedido) && $dataPedido != FALSE) {
                            $pedido = $dataPedido[0]["Codigo"];
                            $dataProdPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
                            if (isset($dataProdPedido) && $dataProdPedido != FALSE) {
                                $dataVendedores = $this->Vendedores_model->obtenerVendedor($dataPedido[0]["Vendedor"]);
                                if (isset($dataVendedores) && $dataVendedores != FALSE) {
                                    $dataEventos = $this->Eventos_model->obtenerEvento($dataPedido[0]["Evento"]);
                                    if (isset($dataEventos) && $dataEventos != FALSE) {

                                        $data = new stdClass();
                                        $data->Controller = "Clientes";
                                        $data->title = "Datos Cliente";
                                        $data->subtitle = "Cliente";
                                        $data->contenido = $this->viewControl . '/Consultar';
                                        $data->cliente = $cliente;
                                        $data->pedido = $pedido;

                                        $data->Listadatos = $dataClientes;
                                        $data->Lista1 = $dataTipDoc;
                                        $data->Lista2 = $dataTiposVivienda;
                                        $data->Lista3 = $dataProdPedido;
                                        $data->Lista4 = $dataRef;
                                        $data->Lista5 = $dataVendedores;
                                        $data->Lista6 = $dataEventos;
                                        $data->PaginaFisica = $dataPedido[0]["PaginaFisica"];

                                        $this->load->view('frontend', $data);
                                    } else {
                                        $this->session->set_flashdata("error", "No se tienen datos sobre 'Eventos'");
                                        redirect(base_url("/Eventos/Admin/"));
                                    }
                                } else {
                                    $this->session->set_flashdata("error", "No se tienen datos sobre 'Vendedores'");
                                    redirect(base_url("/Vendedores/Admin/"));
                                }
                            } else {
                                $this->session->set_flashdata("error", "No se tienen datos sobre los Productos del Cliente");
                                redirect(base_url("/Clientes/Admin/"));
                            }
                        } else {
                            $this->session->set_flashdata("error", "No se tienen datos sobre el Pedido del Cliente");
                            redirect(base_url("/Clientes/Admin/"));
                        }
                    } else {
                        $this->session->set_flashdata("error", "No se tienen datos sobre 'Tipos de Vivienda'");
                        redirect(base_url("/Mantenimiento/TiposVivienda/Admin/"));
                    }
                } else {
                    $this->session->set_flashdata("error", "No se tienen datos sobre 'Tipos de Documentos'");
                    redirect(base_url("/Mantenimiento/TiposDocumentos/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>$cliente</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a los datos del Cliente");
            redirect(base_url() . "Clientes/Admin/");
        }
    }

    public function UpdateClientDataP() {
        $idPermiso = 92;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $cli_cod = trim($this->input->post('Codigo'));
            $cli_nom = trim($this->input->post('Nombre'));
            $cli_doc = trim($this->input->post('Documento'));

            $dataClientes = $this->Clientes_model->obtenerClienteDir($cli_cod);
            if (isset($dataClientes) && $dataClientes != FALSE) {
                //Datos Auditoría
                $user = $this->session->userdata('Usuario');
                $fecha = date("Y-m-d H:i:s");

                //Crear Direccion
                $data = array(
                    "Nombre" => $cli_nom,
                    "Documento" => $cli_doc,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );

                if ($this->Clientes_model->update($cli_cod, $data)) {
                    $modulo = "Actualizar Clientes";
                    $tabla = "Clientes";
                    $accion = "Modificar Nombre y Documento";
                    $data = compararCambiosLog($dataClientes, $data);
                    //var_dump($data);
                    if (count($data) > 2) {
                        $data['Codigo'] = $cli_cod;
                        $sql = LogSave($data, $modulo, $tabla, $accion, $cli_cod);
                    }

                    echo 1;
                } else {
                    echo "No se pudo guardar la Dirección. Recargue la página e intente de nuevo.";
                }
            } else {
                echo "No se puede acceder a los datos del Cliente. Recargue la página e intente de nuevo.";
            }
        } else {
            echo "No tiene permisos para Modificar los datos principales del Cliente";
        }
    }

    public function UpdateClientDir() {
        $idPermiso = 93;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $cli_cod = trim($this->input->post('cli_cod'));
            $dataClientes = $this->Clientes_model->obtenerClienteDir($cli_cod);
            if (isset($dataClientes) && $dataClientes != FALSE) {
                $cod_dir = $dataClientes[0]["Direccion"];
                $dataDireccion = $this->Direcciones_model->obtenerDireccionPorCod($cod_dir);
                if (isset($dataDireccion) && $dataDireccion != FALSE) {
                    //Ubicacion
                    $cli_dir = ucwords(strtolower(trim($this->input->post('cli_dir'))));
                    $cli_eta = trim($this->input->post('cli_eta'));
                    $cli_tor = trim($this->input->post('cli_tor'));
                    $cli_apto = trim($this->input->post('cli_apto'));
                    $cli_manz = trim($this->input->post('cli_manz'));
                    $cli_int = trim($this->input->post('cli_int'));
                    $cli_casa = trim($this->input->post('cli_casa'));
                    $cli_bar = ucwords(strtolower(trim($this->input->post('cli_bar'))));
                    $cli_tipviv = trim($this->input->post('cli_tipviv'));
                    //Datos Auditoría
                    $user = $this->session->userdata('Usuario');
                    $fecha = date("Y-m-d H:i:s");

                    //Crear Direccion
                    $data = array(
                        "Direccion" => $cli_dir,
                        "Etapa" => $cli_eta,
                        "Torre" => $cli_tor,
                        "Apartamento " => $cli_apto,
                        "Manzana" => $cli_manz,
                        "Interior" => $cli_int,
                        "Casa" => $cli_casa,
                        "Barrio" => $cli_bar,
                        "TipoVivienda" => $cli_tipviv,
                        "Habilitado" => 1,
                        "UsuarioModificacion" => $user,
                        "FechaModificacion" => $fecha
                    );

                    if ($this->Direcciones_model->update($cod_dir, $data)) {
                        $modulo = "Actualizar Clientes";
                        $tabla = "Direcciones";
                        $accion = "Modificar Dirección";
                        $data = compararCambiosLog($dataDireccion, $data);
                        //var_dump($data);
                        if (count($data) > 2) {
                            $data['Direccion'] = $cli_dir;
                            $sql = LogSave($data, $modulo, $tabla, $accion, $cli_cod);
                        }

                        echo 1;
                    } else {
                        echo "No se pudo guardar la Dirección. Recargue la página e intente de nuevo.";
                    }
                } else {
                    echo "No se puede acceder a los datos de la Direccion. Recargue la página e intente de nuevo.";
                }
            } else {
                echo "No se puede acceder a los datos del Cliente. Recargue la página e intente de nuevo.";
            }
        } else {
            echo "No tiene permisos para Modificar los datos del Cliente";
        }
    }

    public function UpdateClientTel() {
        $idPermiso = 93;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $cli_cod = trim($this->input->post('cli_cod'));
            $dataClientes = $this->Clientes_model->obtenerCliente($cli_cod);
            if (isset($dataClientes) && $dataClientes != FALSE) {
                //Telefonos
                $cli_tel1 = trim($this->input->post('cli_tel1'));
                $cli_tel2 = trim($this->input->post('cli_tel2'));
                $cli_tel3 = trim($this->input->post('cli_tel3'));
                //Datos Auditoría
                $user = $this->session->userdata('Usuario');
                $fecha = date("Y-m-d H:i:s");

                //$data = $dataClientes;
                $data["Telefono1"] = $cli_tel1;
                $data["Telefono2"] = $cli_tel2;
                $data["Telefono3"] = $cli_tel3;
                $data["UsuarioModificacion"] = $user;
                $data["FechaModificacion"] = $fecha;

                if ($this->Clientes_model->update($cli_cod, $data)) {
                    $modulo = "Actualizar Clientes";
                    $tabla = "Clientes";
                    $accion = "Modificar Teléfonos";
                    $data = compararCambiosLog($dataClientes, $data);
                    //var_dump($data);
                    if (count($data) > 2) {
                        $data['Codigo'] = $cli_cod;
                        $sql = LogSave($data, $modulo, $tabla, $accion, $cli_cod);
                    }

                    echo 1;
                } else {
                    echo "No se pudo guardar los Teléfonos. Recargue la página e intente de nuevo.";
                }
            } else {
                echo "No se puede acceder a los datos del Cliente. Recargue la página e intente de nuevo.";
            }
        } else {
            echo "No tiene permisos para Modificar los datos del Cliente";
        }
    }

    public function UpdateClientRef() {
        $idPermiso = 93;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $cli_cod = trim($this->input->post('cli_cod'));
            $cli_nom = trim($this->input->post('cli_nom'));
            $dataClientes = $this->Clientes_model->obtenerCliente($cli_cod);
            if (isset($dataClientes) && $dataClientes != FALSE) {
                //Referencias
                $cli_codrf1 = trim($this->input->post('cli_codrf1'));
                $cli_nomrf1 = ucwords(strtolower(trim($this->input->post('cli_nomrf1'))));
                $cli_telrf1 = trim($this->input->post('cli_telrf1'));
                $cli_paren1 = ucwords(strtolower(trim($this->input->post('cli_paren1'))));
                $cli_codrf2 = trim($this->input->post('cli_codrf2'));
                $cli_nomrf2 = ucwords(strtolower(trim($this->input->post('cli_nomrf2'))));
                $cli_telrf2 = trim($this->input->post('cli_telrf2'));
                $cli_paren2 = ucwords(strtolower(trim($this->input->post('cli_paren2'))));
                $cli_codrf3 = trim($this->input->post('cli_codrf3'));
                $cli_nomrf3 = ucwords(strtolower(trim($this->input->post('cli_nomrf3'))));
                $cli_telrf3 = trim($this->input->post('cli_telrf3'));
                $cli_paren3 = ucwords(strtolower(trim($this->input->post('cli_paren3'))));

                //Datos Auditoría
                $user = $this->session->userdata('Usuario');
                $fecha = date("Y-m-d H:i:s");

                $errores = 0;
                //Referencia 1
                if ($cli_codrf1 != "") {
                    //Actualizar Referencia 1
                    $data = array(
                        "Nombres" => $cli_nomrf1,
                        "Telefono" => $cli_telrf1,
                        "Parentesco" => $cli_paren1,
                        "UsuarioModificacion" => $user,
                        "FechaModificacion" => $fecha
                    );
                    if ($this->Referencias_model->update($cli_codrf1, $data)) {
                        $modulo = "Actualizar Clientes";
                        $tabla = "Referencias";
                        $accion = "Modificar Referencias";
                        //$data = compararCambiosLog($dataClientes, $data);
                        //var_dump($data);
                        if (count($data) > 2) {
                            $data['Codigo'] = $cli_codrf1;
                            $data['Observaciones'] = "Se actualiza la referencia " . $cli_codrf1 . " del Cliente: " . $cli_nom . "\n\nObservación automática";
                            $sql = LogSave($data, $modulo, $tabla, $accion, $cli_cod);
                        }
                    } else {
                        $errores++;
                        echo "No se pudieron guardar las Referencias. Recargue la página e intente de nuevo.";
                    }
                } else {
                    if ($cli_nomrf1 != "" && $cli_telrf1 != "" && $cli_paren1 != "") {
                        //Crear Referencias 1
                        $dataReferencia1 = array(
                            "Nombres" => $cli_nomrf1,
                            "Telefono" => $cli_telrf1,
                            "Parentesco" => $cli_paren1,
                            "Habilitado" => 1,
                            "UsuarioCreacion" => $user,
                            "FechaCreacion" => $fecha
                        );

                        try {
                            if ($this->Referencias_model->save($dataReferencia1)) {
                                $Ref1 = $this->Referencias_model->obtenerReferenciasCodUserFec($cli_nomrf1, $user, $fecha);
                                if ($Ref1) {
                                    $dataReferencia1['Codigo'] = $Ref1[0]['Codigo'];
                                    $modulo = "Actualizar Clientes";
                                    $tabla = "Referencias";
                                    $accion = "Crear Referencia";
                                    $llave = $cli_cod;
                                    $sql = LogSave($dataReferencia1, $modulo, $tabla, $accion, $llave);

                                    $dataRefCliente1 = array(
                                        "Cliente" => $cli_cod,
                                        "Referencia" => $Ref1[0]['Codigo'],
                                        "Habilitado" => 1,
                                        "UsuarioCreacion" => $user,
                                        "FechaCreacion" => $fecha
                                    );

                                    if ($this->Referencias_model->saveRefCli($dataRefCliente1)) {
                                        $RefCli1 = $this->Referencias_model->obtenerRefClienteCodUserFec($cli_cod, $Ref1[0]['Codigo'], $user, $fecha);
                                        if ($RefCli1) {
                                            $dataRefCliente1['Codigo'] = $RefCli1[0]['Codigo'];
                                            $modulo = "Actualizar Clientes";
                                            $tabla = "ReferenciasCliente";
                                            $accion = "Vincular Cliente y Referencia";
                                            $llave = $cli_cod;
                                            $sql = LogSave($dataRefCliente1, $modulo, $tabla, $accion, $llave);
                                        } else {
                                            $errores++;
                                            echo "No se pudo guardar, por favor intentelo de nuevo.";
                                        }
                                    } else {
                                        $errores++;
                                        echo "No se pudo guardar, por favor intentelo de nuevo.";
                                    }
                                } else {
                                    $errores++;
                                    echo "No se pudo guardar, por favor intentelo de nuevo.";
                                }
                            } else {
                                $errores++;
                                echo "No se pudo guardar, por favor intentelo de nuevo.";
                            }
                        } catch (Exception $e) {
                            $errores++;
                            echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
                        }
                    }
                }
                //Referencia 2
                if ($cli_codrf2 != "") {
                    //Actualizar Referencia 2
                    $data = array(
                        "Nombres" => $cli_nomrf2,
                        "Telefono" => $cli_telrf2,
                        "Parentesco" => $cli_paren2,
                        "UsuarioModificacion" => $user,
                        "FechaModificacion" => $fecha
                    );
                    if ($this->Referencias_model->update($cli_codrf2, $data)) {
                        $modulo = "Actualizar Clientes";
                        $tabla = "Referencias";
                        $accion = "Modificar Referencias";
                        if (count($data) > 2) {
                            $data['Codigo'] = $cli_codrf2;
                            $data['Observaciones'] = "Se actualiza la referencia " . $cli_codrf2 . " del Cliente: " . $cli_nom . "\n\nObservación automática";
                            $sql = LogSave($data, $modulo, $tabla, $accion, $cli_cod);
                        }
                    } else {
                        $errores++;
                        echo "No se pudieron guardar las Referencias. Recargue la página e intente de nuevo.";
                    }
                } else {
                    if ($cli_nomrf2 != "" && $cli_telrf2 != "" && $cli_paren2 != "") {
                        //Crear Referencias 2
                        $dataReferencia2 = array(
                            "Nombres" => $cli_nomrf2,
                            "Telefono" => $cli_telrf2,
                            "Parentesco" => $cli_paren2,
                            "Habilitado" => 1,
                            "UsuarioCreacion" => $user,
                            "FechaCreacion" => $fecha
                        );

                        try {
                            if ($this->Referencias_model->save($dataReferencia2)) {
                                $Ref2 = $this->Referencias_model->obtenerReferenciasCodUserFec($cli_nomrf2, $user, $fecha);
                                if ($Ref2) {
                                    $dataReferencia2['Codigo'] = $Ref2[0]['Codigo'];
                                    $modulo = "Actualizar Clientes";
                                    $tabla = "Referencias";
                                    $accion = "Crear Referencia";
                                    $llave = $cli_cod;
                                    $sql = LogSave($dataReferencia2, $modulo, $tabla, $accion, $llave);

                                    $dataRefCliente2 = array(
                                        "Cliente" => $cli_cod,
                                        "Referencia" => $Ref2[0]['Codigo'],
                                        "Habilitado" => 1,
                                        "UsuarioCreacion" => $user,
                                        "FechaCreacion" => $fecha
                                    );

                                    if ($this->Referencias_model->saveRefCli($dataRefCliente2)) {
                                        $RefCli2 = $this->Referencias_model->obtenerRefClienteCodUserFec($cli_cod, $Ref2[0]['Codigo'], $user, $fecha);
                                        if ($RefCli2) {
                                            $dataRefCliente2['Codigo'] = $RefCli2[0]['Codigo'];
                                            $modulo = "Actualizar Clientes";
                                            $tabla = "ReferenciasCliente";
                                            $accion = "Vincular Cliente y Referencia";
                                            $llave = $cli_cod;
                                            $sql = LogSave($dataRefCliente2, $modulo, $tabla, $accion, $llave);
                                        } else {
                                            $errores++;
                                            echo "No se pudo guardar, por favor intentelo de nuevo.";
                                        }
                                    } else {
                                        $errores++;
                                        echo "No se pudo guardar, por favor intentelo de nuevo.";
                                    }
                                } else {
                                    $errores++;
                                    echo "No se pudo guardar, por favor intentelo de nuevo.";
                                }
                            } else {
                                $errores++;
                                echo "No se pudo guardar, por favor intentelo de nuevo.";
                            }
                        } catch (Exception $e) {
                            $errores++;
                            echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
                        }
                    }
                }
                //Referencia 3
                if ($cli_codrf3 != "") {
                    //Actualizar Referencia 3
                    $data = array(
                        "Nombres" => $cli_nomrf3,
                        "Telefono" => $cli_telrf3,
                        "Parentesco" => $cli_paren3,
                        "UsuarioModificacion" => $user,
                        "FechaModificacion" => $fecha
                    );
                    if ($this->Referencias_model->update($cli_codrf3, $data)) {
                        $modulo = "Actualizar Clientes";
                        $tabla = "Referencias";
                        $accion = "Modificar Referencias";
                        if (count($data) > 2) {
                            $data['Codigo'] = $cli_codrf3;
                            $data['Observaciones'] = "Se actualiza la referencia " . $cli_codrf3 . " del Cliente: " . $cli_nom . "\n\nObservación automática";
                            $sql = LogSave($data, $modulo, $tabla, $accion, $cli_cod);
                        }
                    } else {
                        $errores++;
                        echo "No se pudieron guardar las Referencias. Recargue la página e intente de nuevo.";
                    }
                } else {
                    if ($cli_nomrf3 != "" && $cli_telrf3 != "" && $cli_paren3 != "") {
                        //Crear Referencias 3
                        $dataReferencia3 = array(
                            "Nombres" => $cli_nomrf3,
                            "Telefono" => $cli_telrf3,
                            "Parentesco" => $cli_paren3,
                            "Habilitado" => 1,
                            "UsuarioCreacion" => $user,
                            "FechaCreacion" => $fecha
                        );

                        try {
                            if ($this->Referencias_model->save($dataReferencia3)) {
                                $Ref3 = $this->Referencias_model->obtenerReferenciasCodUserFec($cli_nomrf3, $user, $fecha);
                                if ($Ref3) {
                                    $dataReferencia3['Codigo'] = $Ref3[0]['Codigo'];
                                    $modulo = "Actualizar Clientes";
                                    $tabla = "Referencias";
                                    $accion = "Crear Referencia";
                                    $llave = $cli_cod;
                                    $sql = LogSave($dataReferencia3, $modulo, $tabla, $accion, $llave);

                                    $dataRefCliente3 = array(
                                        "Cliente" => $cli_cod,
                                        "Referencia" => $Ref3[0]['Codigo'],
                                        "Habilitado" => 1,
                                        "UsuarioCreacion" => $user,
                                        "FechaCreacion" => $fecha
                                    );

                                    if ($this->Referencias_model->saveRefCli($dataRefCliente3)) {
                                        $RefCli3 = $this->Referencias_model->obtenerRefClienteCodUserFec($cli_cod, $Ref3[0]['Codigo'], $user, $fecha);
                                        if ($RefCli3) {
                                            $dataRefCliente3['Codigo'] = $RefCli3[0]['Codigo'];
                                            $modulo = "Actualizar Clientes";
                                            $tabla = "ReferenciasCliente";
                                            $accion = "Vincular Cliente y Referencia";
                                            $llave = $cli_cod;
                                            $sql = LogSave($dataRefCliente3, $modulo, $tabla, $accion, $llave);
                                        } else {
                                            $errores++;
                                            echo "No se pudo guardar, por favor intentelo de nuevo.";
                                        }
                                    } else {
                                        $errores++;
                                        echo "No se pudo guardar, por favor intentelo de nuevo.";
                                    }
                                } else {
                                    $errores++;
                                    echo "No se pudo guardar, por favor intentelo de nuevo.";
                                }
                            } else {
                                $errores++;
                                echo "No se pudo guardar, por favor intentelo de nuevo.";
                            }
                        } catch (Exception $e) {
                            $errores++;
                            echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
                        }
                    }
                }
                if ($errores <= 0) {
                    echo 1;
                }
            } else {
                echo "No se puede acceder a los datos del Cliente. Recargue la página e intente de nuevo.";
            }
        } else {
            echo "No tiene permisos para Modificar los datos del Cliente";
        }
    }

    public function UpdateClientObs() {
        $idPermiso = 93;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $cli_cod = trim($this->input->post('cli_cod'));
            $cli_ped = trim($this->input->post('cli_ped'));
            $cli_pag = trim($this->input->post('cli_pag'));
            $cli_obs = ucfirst(strtolower(trim($this->input->post('cli_obs'))));
            //Datos Auditoría
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");

            $dataClientes = $this->Clientes_model->obtenerCliente($cli_cod);
            if (isset($dataClientes) && $dataClientes != FALSE) {
                $obsActual = $dataClientes[0]["Observaciones"];
                $obsNueva = $obsActual . "\n---\n" . $cli_obs;

                $dataObs = array(
                    "Observaciones" => $obsNueva,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );

                $dataPag = array(
                    "PaginaFisica" => $cli_pag,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );
                if ($this->Pedidos_model->update($cli_ped, $dataPag)) {
                    if (strlen($cli_obs) <= 0) {
                        echo 1;
                    } else {
                        if ($this->Clientes_model->update($cli_cod, $dataObs)) {
                            $dataObs["NuevaObservacion"] = $cli_obs;
                            $modulo = "Actualizar Clientes";
                            $tabla = "Clientes";
                            $accion = "Modificar Observaciones";
                            //var_dump($data);
                            if (count($dataObs) > 2) {
                                $dataObs['Codigo'] = $cli_cod;
                                $sql = LogSave($dataObs, $modulo, $tabla, $accion, $cli_cod);
                            }

                            echo 1;
                        } else {
                            echo "No se pudieron guardar las Observaciones. Recargue la página e intente de nuevo.";
                        }
                    }
                } else {
                    echo "No se pudo guardar la ubicación física. Recargue la página e intente de nuevo.";
                }
            } else {
                echo "No se puede acceder a los datos del Cliente. Recargue la página e intente de nuevo.";
            }
        } else {
            echo "No tiene permisos para Modificar los datos del Cliente";
        }
    }

    public function Pagos($cliente) {
        if (isset($cliente)) {
            redirect(base_url("Pagos/Cliente/" . $cliente . "/"));
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a los pagos del Cliente");
            redirect(base_url() . "Clientes/Admin/");
        }
    }

    public function Log($cliente) {
        if ($cliente == null || $cliente == "") {
            $this->session->set_flashdata("error", "No se encontró Cliente.");
            redirect(base_url("/Clientes/Admin/"));
        } else {
            $dataClientes = $this->Clientes_model->obtenerClienteDir($cliente);
            if (isset($dataClientes) && $dataClientes != FALSE) {
                $dataLog = $this->Clientes_model->LogCliente($cliente);
                if (isset($dataLog) && $dataLog != FALSE) {
                    $data = new stdClass();
                    $data->Controller = "Log";
                    $data->title = "Log del Cliente";
                    $data->subtitle = "Historial de <b>" . $dataClientes[0]['Nombre'] . "</b>";
                    $data->contenido = $this->viewControl . '/Log';
                    $data->ListaDatos = $dataLog;
                    $this->load->view('frontend', $data);
                } else {
                    $this->session->set_flashdata("error", "El Cliente no tiene Registros de Log");
                    redirect(base_url("/Clientes/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>" . $cliente . "</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        }
    }

    public function VerLog($codigo) {
        $dataLog = $this->Log_model->obtenerLogPorCod($codigo);
        if (isset($dataLog) && $dataLog != FALSE) {
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

    public function CambioFecha($cliente) {
        $idPermiso = 16;
        $page = validarPermisoPagina($idPermiso);

        if (isset($cliente)) {
            $dataClientes = $this->Clientes_model->obtenerClienteDir($cliente);
            if (isset($dataClientes) && $dataClientes != FALSE) {
                $dataPedido = $this->Pedidos_model->obtenerPedidosCliente($cliente);
                if (isset($dataPedido) && $dataPedido != FALSE) {
                    $pedido = $dataPedido[0]["Codigo"];
                    $dataProdPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
                    if (isset($dataProdPedido) && $dataProdPedido != FALSE) {

                        $data = new stdClass();
                        $data->Controller = "Clientes";
                        $data->title = "Fecha de Pago";
                        $data->subtitle = $dataClientes[0]["Nombre"];
                        $data->contenido = $this->viewControl . '/CambioFecha';
                        $data->cliente = $cliente;
                        $data->pedido = $pedido;

                        $data->Listadatos = $dataClientes;
                        $data->Lista1 = $dataProdPedido;

                        $this->load->view('frontend', $data);
                    } else {
                        $this->session->set_flashdata("error", "No se tienen datos sobre los Productos del Cliente");
                        redirect(base_url("/Clientes/Admin/"));
                    }
                } else {
                    $this->session->set_flashdata("error", "No se tienen datos sobre el Pedido del Cliente");
                    redirect(base_url("/Clientes/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>$cliente</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a los datos del Cliente");
            redirect(base_url() . "Clientes/Admin/");
        }
    }

    public function ChangePayDate() {
        $idPermiso = 95;
        $accion = validarPermisoAcciones($idPermiso);
        if ($accion) {
            $cli_ped = trim($this->input->post('cli_ped'));
            $cli_fec = trim($this->input->post('cli_fec') . " 00:00:00");
            $cli_fec = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $cli_fec);

            //Datos Auditoría
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");

            $dataP = array(
                "DiaCobro" => date("Y-m-d H:i:s", strtotime($cli_fec)),
                "UsuarioModificacion" => $user,
                "FechaModificacion" => $fecha
            );

            try {
                if ($this->Pedidos_model->update($cli_ped, $dataP)) {
                    $modulo = "Fecha Pago Pedido";
                    $tabla = "Pedidos";
                    $accion = "Cambio de Fecha de Pago";
                    $data = $dataP;
                    if (count($data) > 2) {
                        $data['Codigo'] = $cli_ped;
                        $data['Observaciones'] = "Se cambia Fecha de Pago\nNueva fecha de Cobro: " . $cli_fec . "\n \nObservación automática.";
                        $llave = $cli_ped;
                        $sql = LogSave($data, $modulo, $tabla, $accion, $llave);
                        echo 1;
                    }
                } else {
                    echo "No se pudo Actualizar la Fecha de Pago. Actualice la página y vuelva a intentarlo.";
                }
            } catch (Exception $e) {
                echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
            }
        } else {
            echo "No tiene permisos para Modificar la Fecha de Pago del Cliente";
        }
    }

    public function CambioTarifa($cliente) {
        $idPermiso = 17;
        $page = validarPermisoPagina($idPermiso);

        if (isset($cliente)) {
            $dataClientes = $this->Clientes_model->obtenerClienteDir($cliente);
            if (isset($dataClientes) && $dataClientes != FALSE) {
                $dataPedido = $this->Pedidos_model->obtenerPedidosCliente($cliente);
                if (isset($dataPedido) && $dataPedido != FALSE) {
                    $pedido = $dataPedido[0]["Codigo"];
                    $dataProdPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
                    if (isset($dataProdPedido) && $dataProdPedido != FALSE) {
                        $Producto = $dataProdPedido[0]["Producto"];
                        $dataTarifa = $this->Tarifas_model->obtenerTarifaPorProducto($Producto);
                        if (isset($dataTarifa) && $dataTarifa != FALSE) {
                            $pago = $dataPedido[0]["Valor"] - $dataPedido[0]["Saldo"];

                            $data = new stdClass();
                            $data->Controller = "Clientes";
                            $data->title = "Cambio de Tarifa";
                            $data->subtitle = "Tarifa";
                            $data->contenido = $this->viewControl . '/CambioTarifa';
                            $data->cliente = $cliente;
                            $data->pedido = $pedido;

                            $data->Listadatos = $dataClientes;
                            $data->Lista1 = $dataProdPedido;
                            $data->Lista2 = $dataTarifa;
                            $data->Tarifa = $dataPedido[0]["Tarifa"];
                            $data->Pago = $pago;

                            $this->load->view('frontend', $data);
                        } else {
                            $this->session->set_flashdata("error", "No se tienen datos sobre las Tarifas");
                            redirect(base_url("/Tarifas/Admin/"));
                        }
                    } else {
                        $this->session->set_flashdata("error", "No se tienen datos sobre el Pedido del Cliente");
                        redirect(base_url("/Clientes/Admin/"));
                    }
                } else {
                    $this->session->set_flashdata("error", "No se tienen datos sobre el Pedido del Cliente");
                    redirect(base_url("/Clientes/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se encontraron datos del Cliente: <b>$cliente</b>");
                redirect(base_url("/Clientes/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a los datos del Cliente");
            redirect(base_url() . "Clientes/Admin/");
        }
    }

    public function changeRate() {
        $idPermiso = 96;
        $accion = validarPermisoAcciones($idPermiso);
        if ($accion) {
            $tar_ped = trim($this->input->post('tar_ped'));
            $tar_tar = trim($this->input->post('tar_tar'));
            $tar_nom = trim($this->input->post('tar_nom'));
            $tar_tot = trim($this->input->post('tar_tot'));
            $tar_num = trim($this->input->post('tar_num'));
            $tar_cuo = trim($this->input->post('tar_cuo'));
            $tar_sal = trim($this->input->post('tar_sal'));
            //Datos Auditoría
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");

            //echo $tar_ped . " - " . $tar_tar . " - " . $tar_tot . " - " . $tar_num . " - " . $tar_cuo . " - " . $tar_sal;
            $dataPedido = array(
                "Valor" => $tar_tot,
                "Tarifa" => $tar_tar,
                "Saldo" => $tar_sal,
                "UsuarioModificacion" => $user,
                "FechaModificacion" => $fecha
            );

            try {
                if ($this->Pedidos_model->update($tar_ped, $dataPedido)) {
                    $modulo = "Tarifa Pedido";
                    $tabla = "Pedidos";
                    $accion = "Cambio de Tarifa";
                    $data = $dataPedido;
                    if (count($data) > 2) {
                        $data['Codigo'] = $tar_ped;
                        $data['Observaciones'] = "Se cambia la tarifa de Pago\nNueva Tarifa: " . $tar_nom . "\n \nObservación automática.";
                        $llave = $tar_ped;
                        $sql = LogSave($data, $modulo, $tabla, $accion, $llave);
                    }

                    $dataPagos = $this->Pagos_model->obtenerPagosPedido($tar_ped);
                    if (isset($dataPagos) && $dataPagos != FALSE) {
                        foreach ($dataPagos as $value) {
                            $pago = array(
                                "TotalPago" => $tar_tot,
                                "Observaciones" => $value["Observaciones"] . "\n---\nSe actualiza Tarifa: " . $tar_tot,
                                "UsuarioModificacion" => $user,
                                "FechaModificacion" => $fecha
                            );

                            if ($this->Pagos_model->update($value["Codigo"], $pago)) {
                                $modulo = "Tarifa/Pago Pedido";
                                $tabla = "Pagos";
                                $accion = "Cambio de Tarifa";
                                $data = $pago;
                                if (count($data) > 2) {
                                    $data['Codigo'] = $value["Codigo"];
                                    $data['Observaciones'] = "Se cambia la tarifa de Pago\nNueva Tarifa: " . $tar_nom . "\n \nObservación automática.";
                                    $llave = $value["Codigo"];
                                    $sql = LogSave($data, $modulo, $tabla, $accion, $llave);
                                }
                            }
                        }
                    }
                    echo 1;
                } else {
                    echo "No se pudo Actualizar la Tarifa. Actualice la página y vuelva a intentarlo.";
                }
            } catch (Exception $e) {
                echo 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
            }
        } else {
            echo "No tiene permisos para Modificar la Tarifa del Cliente";
        }
    }

    public function Contador() {
        $f1 = date("Y-m-d 00:00:00");
        $f2 = date("Y-m-d 23:59:59");

        $Clientes = $this->ConteoClientes($f1, $f2);
        $data = new stdClass();
        $data->Controller = "Clientes";
        $data->title = "Conteo de Clientes";
        $data->subtitle = "Listado de Clientes por Estados";
        $data->contenido = $this->viewControl . '/Contador';
        $data->Clientes = $Clientes;

        $this->load->view('frontend', $data);
    }

    public function ConteoClientesPost() {
        $fecha1 = trim($this->input->post('pag_fec1') . " 00:00:00");
        $fecha1 = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fecha1);
        $fecha2 = trim($this->input->post('pag_fec2') . " 23:59:59");
        $fecha2 = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fecha2);
        $Clientes = $this->ConteoClientes($fecha1, $fecha2);

        echo json_encode($Clientes);
    }

    public function ConteoClientes($fecha1, $fecha2) {
        $Clientes = array();
        $Registrados = $this->Clientes_model->AllClients();
        $Clientes["Registrados"] = $Registrados[0]["Num"];
        $Eliminados = $this->Clientes_model->ClientsDelete();
        $Clientes["Eliminados"] = $Eliminados[0]["Num"];
        $Aldía = $this->Clientes_model->ClientsOk();
        $Clientes["Aldía"] = $Aldía[0]["Num"];
        $Deben = $this->Clientes_model->ClientsDeb();
        $Clientes["Deben"] = $Deben[0]["Num"];
        $Mora = $this->Clientes_model->ClientsMora();
        $Clientes["Mora"] = $Mora[0]["Num"];
        $dataC = $this->Clientes_model->ClientsData();
        $Clientes["dataC"] = $dataC[0]["Num"];
        $Reportados = $this->Clientes_model->ClientsReports();
        $Clientes["Reportados"] = $Reportados[0]["Num"];
        $Paz = $this->Clientes_model->ClientsPeace();
        $Clientes["Paz"] = $Paz[0]["Num"];
        $Nuevo = $this->Clientes_model->ClientsNew($fecha1, $fecha2);
        $Clientes["Nuevo"] = $Nuevo[0]["Num"];

        return $Clientes;
    }

    public function Asignados() {
        $dataCliente = $this->Clientes_model->obtenerClientesAsignados();
        $dataEstados = $this->Estados_model->obtenerEstadosPor(102);
        $dataUsuarios = $this->Usuarios_model->obtenerUsuariosEP();

        $data = new stdClass();
        $data->Controller = "Clientes";
        $data->title = "Clientes Asignados";
        $data->subtitle = "Listado de Clientes Asignados";
        $data->contenido = $this->viewControl . '/Asignados';
        $data->ListaDatos = $dataCliente;
        $data->Lista1 = $dataEstados;
        $data->Lista2 = $dataUsuarios;

        $this->load->view('frontend', $data);
    }

    public function Productos($pedido) {
        if (isset($pedido)) {
            $dataProdPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
            if (isset($dataProdPedido) && $dataProdPedido != FALSE) {
                $dataProductos = $this->Productos_model->obtenerProductos();
                if (isset($dataProductos) && $dataProductos != FALSE) {
                    $data = new stdClass();
                    $data->Controller = "Clientes";
                    $data->title = "Productos Cliente";
                    $data->subtitle = "Productos del Cliente";
                    $data->contenido = $this->viewControl . '/Productos';
                    $data->cliente = $dataProdPedido[0]["Cliente"];
                    $data->pedido = $pedido;
                    $data->NombreCliente = $dataProdPedido[0]["NombreCliente"];

                    $data->ListaProductos = $dataProdPedido;
                    $data->LProducto = $dataProductos;

                    $this->load->view('frontend', $data);
                } else {
                    $this->session->set_flashdata("error", "No se puede acceder a los productos del Sistema");
                    redirect(base_url() . "Clientes/Admin/");
                }
            } else {
                $this->session->set_flashdata("error", "No se puede acceder a los productos del Pedido del Cliente");
                redirect(base_url() . "Clientes/Admin/");
            }
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a los datos del Pedido del Cliente");
            redirect(base_url() . "Clientes/Admin/");
        }
    }

    public function AddProducto() {
        $pedido = trim($this->input->post('pedido'));
        $producto = trim($this->input->post('producto'));
        $nombre = trim($this->input->post('nombre'));
        $tarifa = trim($this->input->post('tarifa'));
        $cantidad = trim($this->input->post('cantidad'));
        $valor = trim($this->input->post('valor'));
        $valor = str_replace("$ ", "", $valor);
        $valor = str_replace(".", "", $valor);
        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha = date("Y-m-d H:i:s");

        if (isset($pedido)) {
            $dataProdPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
            if (isset($dataProdPedido) && $dataProdPedido != FALSE) {
                $dataProductos = $this->Productos_model->obtenerProductos();
                if (isset($dataProductos) && $dataProductos != FALSE) {
                    $nuevoProducto = 0;
                    foreach ($dataProdPedido as $item) {
                        if ($item["CodPro"] == $producto) {
                            $nuevoProducto++;
                        }
                    }

                    if ($nuevoProducto == 0) {
                        foreach ($dataProdPedido as $item) {
                            $dataProductoPedido = array(
                                "Pedido" => $pedido,
                                "Cantidad" => $cantidad,
                                "Producto" => $producto,
                                "Habilitado" => 1,
                                "Valor" => $valor,
                                "UsuarioCreacion" => $user,
                                "FechaCreacion" => $fecha
                            );
                            if ($this->Pedidos_model->saveProPed($dataProductoPedido)) {
                                $PedidoPro = $this->Pedidos_model->obtenerPedidoProUserFec($pedido, $producto, $user, $fecha);
                                if ($PedidoPro) {
                                    $dataProductoPedido['Codigo'] = $PedidoPro[0]['Codigo'];
                                    $modulo = "Pagar Pedido";
                                    $tabla = "Pagos";
                                    $accion = "Confirmar Pago";
                                    $llave = $pedido;
                                    $sql = LogSave($dataProductoPedido, $modulo, $tabla, $accion, $llave);
                                }
                            }
                            break;
                        }
                    } else {
                        foreach ($dataProdPedido as $item) {
                            if ($item["CodPro"] == $producto) {
                                $dataProductoPedido = array(
                                    "Cantidad" => $item["Cantidad"] + $cantidad,
                                    "Valor" => $item["ValPP"] + $valor,
                                    "UsuarioModificacion" => $user,
                                    "FechaModificacion" => $fecha
                                );

                                if ($this->Pedidos_model->updateProPedidoxPedido($pedido, $producto, $dataProductoPedido)) {
                                    $ProPedido = $this->Pedidos_model->obtenerProductosPedidosxPedido($pedido);
                                    $modulo = "Agregar Producto";
                                    $tabla = "ProductosPedidos";
                                    $accion = "Actualizar Producto";
                                    $data = compararCambiosLog($ProPedido, $dataProductoPedido);
                                    if (count($data) > 2) {
                                        $data['Codigo'] = $pedido;
                                        $llave = $pedido;
                                        $sql = LogSave($data, $modulo, $tabla, $accion, $llave);
                                    }
                                }
                                break;
                            }
                        }
                    }

                    try {
                        $dataPedido = array(
                            "Valor" => $item["Valor1"] + $valor,
                            "Saldo" => $item["Saldo"] + $valor,
                            "UsuarioModificacion" => $user,
                            "FechaModificacion" => $fecha
                        );

                        if ($this->Pedidos_model->update($pedido, $dataPedido)) {
                            $dataPed = $this->Pedidos_model->obtenerPedido($pedido);
                            $modulo = "Agregar Producto";
                            $tabla = "Pedidos";
                            $accion = "Actualizar Pedido";
                            $data = compararCambiosLog($dataPed, $dataPedido);
                            if (count($data) > 2) {
                                $data['Codigo'] = $pedido;
                                $llave = $pedido;
                                $sql = LogSave($data, $modulo, $tabla, $accion, $llave);
                            }

                            $obs = "Se agregó  " . $cantidad . " unidad(es) de <b>" . $nombre . "</b> al Cliente <b>" . $item["NombreCliente"] . "</b> con un valor de <b>" . money_format("%.0n", $valor) . "</b>";
                            $this->History($item["Cliente"], $pedido, $fecha, $user, "Agregar Producto", $item["Saldo"], 0, $item["Saldo"] + $valor, $valor, $obs);
                            echo 1;
                        }
                    } catch (Exception $e) {
                        echo "Ha habido una excepción: " . $e->getMessage();
                    }
                } else {
                    echo "No se puede acceder a los productos del Sistema";
                }
            } else {
                echo "No se puede acceder a los productos del Pedido del Cliente";
            }
        } else {
            echo "No se puede acceder a los datos del Pedido del Cliente";
        }
    }

}

?>
