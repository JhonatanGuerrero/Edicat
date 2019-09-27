<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Clientes extends CI_Controller
{
    public function __construct()
    {
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
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]);
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
        echo "<h4>
                <marquee>
                    <ul>
                        <li>Probar todos los procesos -> sigue Clientes/CambioTarifa/{id}</li>
                        <li>Cliente Productos</li>
                        <li>Permisos Pagos Y Clientes Moroso y Datacredito</li>
                        <li>Permisos Llamadas del día y Clientes sin llamar</li>
                        <li>Permisos de Cobradores/Pagos</li>
                        <li>Forzar Cambio de Contraseña desde el perfil del Usuario</li>
                        <li>Permisos del Usuarios/Permisos/Configuraciones</li>
                        <li>Quitar comentario en obtenerListadosClientesCobroJson por programador</li>
                        <li>Cambio de tarifa con varios productos. No da el saldo</li>                     
                    </ul> 
                </marquee>
            </h4>";
        $idPermiso = 12;
        $page = validarPermisoPagina($idPermiso);
        $permisoAplicado = 104;

        $dataCliente = $this->Clientes_model->obtenerClientesNR();
        $dataCobradores = $this->Cobradores_model->obtenerCobradores();

        if ($dataCliente != false) {
            $i = 0;
            $flagPermiso = 0;
            $usuario = $this->session->userdata('Codigo');
            $PerfilId = $this->session->userdata('PerfilId');

            
            $idPermiso = 104;
            $page = validarPermisoAcciones($idPermiso);
            if ($page && $flagPermiso == 0) {
                $permisoAplicado = $idPermiso;
                $flagPermiso = 1;
                $dataCliente = null;
            }

            $idPermiso = 101;
            $page = validarPermisoAcciones($idPermiso);
            if ($page && $flagPermiso == 0) {
                $permisoAplicado = $idPermiso;
                $flagPermiso = 1;
                $dataCliente = $this->InformacionTodos($dataCliente);
            }
            
            $idPermiso = 102;
            $page = validarPermisoAcciones($idPermiso);
            if ($page && $flagPermiso == 0) {
                $permisoAplicado = $idPermiso;
                $flagPermiso = 1;
                $dataCliente = $this->InformacionOtrosClientes($dataCliente);
            }

            $idPermiso = 103;
            $page = validarPermisoAcciones($idPermiso);
            if ($page && $flagPermiso == 0) {
                $permisoAplicado = $idPermiso;
                $flagPermiso = 1;
                $dataCliente = $this->InformacionSoloClientesPropios($dataCliente);
            }
        }
             
        if (count($dataCliente) <= 0) {
            if ($permisoAplicado == 104) {
                $this->session->set_flashdata("error", "Usted no tiene Permisos para ver Clientes.");
            } else {
                if ($permisoAplicado == 103) {
                    $this->session->set_flashdata("error", "Usted no tiene Clientes Asociados.");
                } else {
                    $this->session->set_flashdata("error", "Aun no hay Clientes/Pedidos creados.");
                }
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

    public function InformacionTodos($dataCliente)
    {
        $i = 0;
        foreach ($dataCliente as $value) {
            //$dataUserCliente = $this->Clientes_model->ClienteUsuario($value["Codigo"], $usuario);
            $pedido = $this->Pedidos_model->obtenerPedidoPorCliente($value["Codigo"]);
            
            if ($pedido != false) {
                $dataCliente[$i]["Pedido"] = $pedido[0]["Codigo"];
                $dataCliente[$i]["Saldo"] = $pedido[0]["Saldo"];
                $dataCliente[$i]["PaginaFisica"] = $pedido[0]["PaginaFisica"];
                $dataCliente[$i]["Nombre"] = ucwords(strtolower($dataCliente[$i]["Nombre"]));
                $dataCliente[$i]["TodasOpciones"] = true;
                $cuotas = $this->Pagos_model->obtenerPagosPorPedido($pedido[0]["Codigo"]);
                if ($cuotas != false) {
                    $cuotas = $cuotas[0]["Cuotas"];
                }
                $dataCliente[$i]["Cuotas"] = $cuotas;
            } else {
                unset($dataCliente[$i]);
            }
            $i++;
        }

        return $dataCliente;
    }

    public function InformacionOtrosClientes($dataCliente)
    {
        $i = 0;
        foreach ($dataCliente as $value) {
            $usuario = $this->session->userdata('Codigo');
            $dataUserCliente = $this->Clientes_model->ClienteUsuario($value["Codigo"], $usuario);
            $pedido = $this->Pedidos_model->obtenerPedidoPorCliente($value["Codigo"]);
            
            if ($pedido != false) {
                $dataCliente[$i]["Pedido"] = $pedido[0]["Codigo"];
                $dataCliente[$i]["Saldo"] = $pedido[0]["Saldo"];
                $dataCliente[$i]["PaginaFisica"] = $pedido[0]["PaginaFisica"];
                $dataCliente[$i]["Nombre"] = ucwords(strtolower($dataCliente[$i]["Nombre"]));
                if ($dataUserCliente == false) {
                    $dataCliente[$i]["TodasOpciones"] = false;
                } else {
                    $dataCliente[$i]["TodasOpciones"] = true;
                }
                $cuotas = $this->Pagos_model->obtenerPagosPorPedido($pedido[0]["Codigo"]);
                if ($cuotas != false) {
                    $cuotas = $cuotas[0]["Cuotas"];
                }
                $dataCliente[$i]["Cuotas"] = $cuotas;
            } else {
                unset($dataCliente[$i]);
            }
            $i++;
        }

        return $dataCliente;
    }

    public function InformacionSoloClientesPropios($dataCliente)
    {
        $i = 0;
        foreach ($dataCliente as $value) {
            $usuario = $this->session->userdata('Codigo');
            $dataUserCliente = $this->Clientes_model->ClienteUsuario($value["Codigo"], $usuario);
            
            if ($dataUserCliente) {
                $pedido = $this->Pedidos_model->obtenerPedidoPorCliente($value["Codigo"]);
                
                if ($pedido != false) {
                    $dataCliente[$i]["Pedido"] = $pedido[0]["Codigo"];
                    $dataCliente[$i]["Saldo"] = $pedido[0]["Saldo"];
                    $dataCliente[$i]["PaginaFisica"] = $pedido[0]["PaginaFisica"];
                    $dataCliente[$i]["Nombre"] = ucwords(strtolower($dataCliente[$i]["Nombre"]));
                    $dataCliente[$i]["TodasOpciones"] = true;
                    $cuotas = $this->Pagos_model->obtenerPagosPorPedido($pedido[0]["Codigo"]);
                    if ($cuotas != false) {
                        $cuotas = $cuotas[0]["Cuotas"];
                    }
                    $dataCliente[$i]["Cuotas"] = $cuotas;
                } else {
                    unset($dataCliente[$i]);
                }
            } else {
                unset($dataCliente[$i]);
            }
            $i++;
        }

        return $dataCliente;
    }


    public function dataClienteHover()
    {
        $id = trim($this->input->post('id'));
        $dataClientes = $this->Clientes_model->obtenerClienteDir($id);
        //var_dump($dataClientes);
        if (isset($dataClientes) && $dataClientes != false) {
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

    public function Buscar()
    {
        $idPermiso = 11;
        $page = validarPermisoPagina($idPermiso);

        $dataEstados = $this->Estados_model->obtenerEstadosPor(102);
        $dataCobradores = $this->Cobradores_model->obtenerCobradores();
        if (isset($dataCobradores) && $dataCobradores != false) {
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

    public function SearchJsonAsignado()
    {
        $nombre =  ucwords(strtolower(trim($this->input->post('nombre'))));
        $estado = trim($this->input->post('estado'));
        $usuario = trim($this->input->post('usuario'));
        $selectNoEstados = trim($this->input->post('selectNoEstados'));
        
        $data = $this->Clientes_model->searchClienteAsignado($nombre, $estado, $usuario, $selectNoEstados);
        $arreglo["data"] = [];

        if (isset($data) && $data != false) {
            $i = 0;
            foreach ($data as $item) {
                $arreglo["data"][$i] = $this->crearArregloBuscar($item);
                $i++;
            }
        }
        echo json_encode($arreglo);
    }

    public function SearchJson()
    {
        $nombre = ucwords(strtolower(trim($this->input->post('nombre'))));
        $direccion = trim($this->input->post('direccion'));
        $telefono = trim($this->input->post('telefono'));
        $estado = trim($this->input->post('estado'));
        $ubicacion = trim($this->input->post('ubicacion'));

        $data = $this->Clientes_model->searchCliente($nombre, $direccion, $telefono, $estado, $ubicacion);
        $arreglo["data"] = [];

        if (isset($data) && $data != false) {
            $i = 0;
            foreach ($data as $item) {
                $arreglo["data"][$i] = $this->crearArregloBuscar($item);
                $i++;
            }
        }
        echo json_encode($arreglo);
    }

    public function crearArregloBuscar($item)
    {
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
        if ($num != false) {
            $cuota = $num[0]["Cuota"];
        }

        $usuario = $this->session->userdata('Codigo');
        $PerfilId = $this->session->userdata('PerfilId');
        $dataPedidos = $this->Pedidos_model->obtenerPedidosDeben();
        $dataCobradores = $this->Cobradores_model->obtenerCobradores();
        $dataUserCliente = false;
        if ($PerfilId >= 103) {
            $dataUserCliente = $this->Clientes_model->ClienteUsuario($item['Cliente'], $usuario);
        } else {
            $dataUserCliente = true;
        }
        if ($dataUserCliente != false) {
            $btn2 = "<a href='" . base_url() . "Clientes/CambioFecha/" . $item['Cliente'] . "/' target='_blank' title='Cambio de Fecha de Cobro'><i class='fa fa-calendar' aria-hidden='true' style='padding:5px;'></i></a>";
            $btn3 = "<a href='" . base_url() . "Clientes/CambioTarifa/" . $item['Cliente'] . "/' target='_blank' title='Cambio de Tarifa'><i class='fa fa-refresh' aria-hidden='true' style='padding:5px;'></i></a>";
            $btn4 = "<a href='" . base_url() . "Pagos/Generar/" . $item['Cliente'] . "/' target='_blank' title='Hacer Recibo'><i class='fa fa-motorcycle' aria-hidden='true' style='padding:5px;'></i></a>";
            $btn5 = "<a href='" . base_url() . "Clientes/Pagos/" . $item['Cliente'] . "/' target='_blank' title='Pagos Realizados del Cliente'><i class='fa fa-usd' aria-hidden='true' style='padding:5px;'></i></a>";
            $btn6 = "<a href='#ModalDevol' data-toggle='modal' title='Devolución del Cliente' onclick='DatosModal(\"" . $item['Pedido'] . "\", \"" . $item['Cliente'] . "\", \"" . $item['Nombre'] . "\", \"" . $item['Saldo'] . "\", \"" . $cuota . "\");'><i class='fa fa-reply-all' aria-hidden='true' style='padding:5px;'></i></a>";
        } else {
            $btn2 = "";
            $btn3 = "";
            $btn4 = "";
            $btn5 = "";
            $btn6 = "";
        }
        $btn1 = "<a href='" . base_url() . "Clientes/Consultar/" . $item['Cliente'] . "/' target='_blank' title='Consultar Cliente'><i class='fa fa-search' aria-hidden='true' style='padding:5px;'></i></a>";

        $diacobro = "";
        if ($item["DiaCobro"] != null || $item["DiaCobro"] != "") {
            $diacobro = date("d/m/Y", strtotime($item["DiaCobro"]));
        } else {
            $diacobro = "Sin Fecha";
        }

        $arreglo = array(
            "Nombre" => $item["Nombre"],
            "Direccion" => $direccion,
            "telefono" => $telefono,
            "saldo" => money_format("%.0n", $item["Saldo"]),
            "DiaCobro" => $diacobro,
            "Estado" => $item["EstNombre"],
            "PaginaFisica" => $item["PaginaFisica"],
            "btn" => '<div class="btn-group text-center" style="margin: 0px auto;  width:100%;">' . $btn1 . $btn2 . $btn3 . $btn4 . $btn5 . $btn6 . '</div>'
        );

        return $arreglo;
    }

    public function Crear()
    {
        $idPermiso = 88;
        $page = validarPermisoPagina($idPermiso);

        $dataTipDoc = $this->TiposDocumentos_model->obtenerTiposDocumentos();
        if (isset($dataTipDoc) && $dataTipDoc != false) {
            $dataTiposVivienda = $this->Direcciones_model->obtenerTiposVivienda();
            if (isset($dataTiposVivienda) && $dataTiposVivienda != false) {
                $dataProductos = $this->Productos_model->obtenerProductos();
                if (isset($dataProductos) && $dataProductos != false) {
                    $dataTarifas = $this->Tarifas_model->obtenerTarifas();
                    if (isset($dataTarifas) && $dataTarifas != false) {
                        $dataVendedores = $this->Vendedores_model->obtenerVendedores();
                        if (isset($dataVendedores) && $dataVendedores != false) {
                            $dataEventos = $this->Eventos_model->obtenerEventosIGLBARR();
                            if (isset($dataEventos) && $dataEventos != false) {
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

                                $usuariosAsignado = $this->Usuarios_model->obtenerUsuariosEP();

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
                                $data->ListaUsuarios = $usuariosAsignado;

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

    public function NewClient()
    {
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
            $cli_Usu = trim($this->input->post('cli_Usu'));
            $cli_IglEve = ucwords(strtolower(trim($this->input->post('cli_IglEve'))));
            $cli_BarEve = ucwords(strtolower(trim($this->input->post('cli_BarEve'))));
            $cli_FecEve = trim($this->input->post('cli_FecEve') . " 00:00:00");
            $cli_FecEve = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $cli_FecEve);
            $cli_PagEve = trim($this->input->post('cli_PagEve'));
            $cli_Obs = ucfirst(strtolower(trim($this->input->post('cli_Obs'))));
            //Valores Predeterminados
            $cli_dirPre = 1;
            $cli_hab = 1;
            $num_cuo = 1;
            $cli_est = 104;
            $ped_est = 110;
            //Datos Auditoría
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");
            $errores = 0;
            $lblErrores = null;
            
            $dataClienteTemp = $this->Clientes_model->obtenerClienteDocLast($cli_doc);
            // if ($dataClienteTemp) {
            //     echo "El documento digitado ya está registrado. Al parecer el Cliente ya existe.";
            //     return false;
            // }

            $lblErrores = $this->AddCliente($cli_nom, $cli_tipdoc, $cli_doc, $cli_dirPre, $cli_tel1, $cli_tel2, $cli_tel3, $cli_est, $cli_Obs, $cli_hab, $user, $fecha);
            if ($lblErrores != null) {
                echo $lblErrores;
                return false;
            }

            $cli_cod = $this->session->flashdata("cli_cod");
            if ($cli_cod == null) {
                echo "No se pudo guardar el Cliente";
                return false;
            }

            $lblErrores = $this->AddAddress($cli_cod, $cli_nom, $cli_dir, $cli_eta, $cli_tor, $cli_apto, $cli_manz, $cli_int, $cli_casa, $cli_bar, $cli_tipviv, $cli_hab, $user, $fecha);
            if ($lblErrores != null) {
                echo $lblErrores;
                return false;
            }
            
            $lblErrores = $this->AddReference($cli_cod, $cli_nom, $cli_nomrf1, $cli_telrf1, $cli_paren1, $cli_hab, $user, $fecha);
            if ($lblErrores != null) {
                echo $lblErrores;
                return false;
            }
            
            $lblErrores = $this->AddReference($cli_cod, $cli_nom, $cli_nomrf2, $cli_telrf2, $cli_paren2, $cli_hab, $user, $fecha);
            if ($lblErrores != null) {
                echo $lblErrores;
                return false;
            }
            
            $lblErrores = $this->AddReference($cli_cod, $cli_nom, $cli_nomrf3, $cli_telrf3, $cli_paren3, $cli_hab, $user, $fecha);
            if ($lblErrores != null) {
                echo $lblErrores;
                return false;
            }
            
            $lblErrores = $this->AddEvent($cli_cod, $cli_nom, $cli_Ven, $cli_IglEve, $cli_BarEve, $cli_FecEve, $cli_hab, $user, $fecha);
            if ($lblErrores != null) {
                echo $lblErrores;
                return false;
            }
            $cli_Eve = $this->session->flashdata("cli_Eve");
            
            if ($cli_Eve == null) {
                $cli_Eve = 101;
            }

            $lblErrores = $this->AddOrder($cli_cod, $cli_nom, $cli_totalPag, $cli_tar1, $cli_nomTar, $cli_priCobro, $ped_est, $cli_Eve, $cli_Ven, $cli_PagEve, $cli_numCuo, $cli_valCuo, $cli_hab, $user, $fecha);
            if ($lblErrores != null) {
                echo $lblErrores;
                return false;
            }

            $ped_cod = $this->session->flashdata("ped_cod");
            if ($ped_cod == null) {
                echo "No se pudo guardar el Pedido";
                return false;
            }

            $lblErrores = $this->AddProduct($cli_cod, $cli_nom, $ped_cod, $cli_cant1, $cli_prod1, $cli_nomprod1, $cli_val1, $cli_hab, $user, $fecha);
            if ($lblErrores != null) {
                echo $lblErrores;
                return false;
            }

            if ($cli_abono > 0 && $cli_abono != "") {
                $lblErrores = $this->AddPayment($cli_cod, $cli_nom, $ped_cod, $num_cuo, $cli_abono, $cli_totalPag, $cli_hab, $user, $fecha);
                if ($lblErrores != null) {
                    echo $lblErrores;
                    return false;
                }
            }
            
            $nomUsuario = $this->Usuarios_model->obtenerUsuario($cli_Usu);
            $lblErrores = $this->asignarClienteUsuario($cli_cod, $cli_nom, $cli_Usu, $nomUsuario[0]["Nombre"], $fecha);
            if ($lblErrores != null) {
                echo $lblErrores;
                return false;
            }
            
            echo "1";
        } else {
            echo "No tiene permisos para Crear un Cliente/Pedido";
        }
    }

    public function AddCliente($cli_nom, $cli_tipdoc, $cli_doc, $cli_dir, $cli_tel1, $cli_tel2, $cli_tel3, $cli_est, $cli_Obs, $cli_hab, $user, $fecha)
    {
        $lblErrores = null;
        $dataCliente = array(
            "Nombre" => $cli_nom,
            "TipoDocumento" => $cli_tipdoc,
            "Documento" => $cli_doc,
            "Direccion" => $cli_dir,
            "Telefono1" => $cli_tel1,
            "Telefono2" => $cli_tel2,
            "Telefono3" => $cli_tel3,
            "Estado" => $cli_est,
            "Observaciones" => $cli_Obs,
            "Habilitado" => $cli_hab,
            "UsuarioCreacion" => $user,
            "FechaCreacion" => $fecha
        );
        
        try {
            if ($this->Clientes_model->save($dataCliente)) {
                $Cli = $this->Clientes_model->obtenerClienteDoc($cli_doc, "DESC"); 
                if ($Cli) {
                    $modulo = "Crear Cliente";
                    $accion = "Creación Cliente '" . $cli_nom."'";
                    $tabla = "Clientes";
                    $llave = $Cli[0]['Codigo'];
                    $cliente_log = $Cli[0]['Codigo'];
                    $enlace = "Clientes|Consultar|" . $llave;
                    $dataInsert = $dataCliente;
                    $observaciones = "";
                    $this->session->set_flashdata("cli_cod", $llave);
                    insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                } else {
                    $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Cliente.";
                }
            } else {
                $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Cliente.";
            }
        } catch (Exception $e) {
            $lblErrores = 'Ha habido una excepción: ' . $e->getMessage() . "<br>Cliente.";
        }
        
        return $lblErrores;
    }

    public function AddAddress($cli_cod, $cli_nom, $cli_dir, $cli_eta, $cli_tor, $cli_apto, $cli_manz, $cli_int, $cli_casa, $cli_bar, $cli_tipviv, $cli_hab, $user, $fecha)
    {
        $lblErrores = null;
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
            "Habilitado" => $cli_hab,
            "UsuarioCreacion" => $user,
            "FechaCreacion" => $fecha
        );

        try {
            if ($this->Direcciones_model->save($dataDireccion)) {
                $dir = $this->Direcciones_model->obtenerDireccionPorDirUserFec($cli_dir, $user, $fecha);
                if ($dir) {
                    $dataCliente = $this->Clientes_model->obtenerCliente($cli_cod);

                    $modulo = "Crear Cliente";
                    $accion = "Creación Dirección '" . $cli_dir."'";
                    $tabla = "Direcciones";
                    $llave = $dir[0]['Codigo'];
                    $cliente_log = $cli_cod;
                    $enlace = "Clientes|Consultar|" . $cli_cod;
                    $dataInsert = $dataDireccion;
                    $observaciones = "";
                    insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                    
                    $dataTemp = array(
                        "Direccion" => $llave
                    );
                    
                    $dataOriginal = $dataCliente[0];
                    $dataNew = compararCambiosLog($dataOriginal, $dataTemp);
                    $this->Clientes_model->update($cli_cod, $dataNew);
                    $modulo = "Crear Cliente";
                    $accion = "Actualización Cliente '" . $cli_nom."'";
                    $tabla = "Clientes";
                    $llave = $cli_cod;
                    $cliente_log = $cli_cod;
                    $enlace = "Clientes|Consultar|" . $cli_cod;
                    $observaciones = "Actualización de Dirección después de la creación";
                    updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                } else {
                    $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Dirección.";
                }
            } else {
                $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Dirección.";
            }
        } catch (Exception $e) {
            $lblErrores = 'Ha habido una excepción: ' . $e->getMessage() . "<br>Dirección.";
        }
        
        return $lblErrores;
    }

    public function AddReference($cli_cod, $cli_nom, $cli_nomrf, $cli_telrf, $cli_paren, $cli_hab, $user, $fecha)
    {
        $lblErrores = null;
        if ($cli_nomrf != "" && $cli_telrf != "" && $cli_paren != "") {
            $dataReferencia = array(
                "Nombres" => $cli_nomrf,
                "Telefono" => $cli_telrf,
                "Parentesco" => $cli_paren,
                "Habilitado" => 1,
                "UsuarioCreacion" => $user,
                "FechaCreacion" => $fecha
            );

            try {
                if ($this->Referencias_model->save($dataReferencia)) {
                    $Ref = $this->Referencias_model->obtenerReferenciasCodUserFec($cli_nomrf, $user, $fecha);
                    if ($Ref) {
                        $llave = $Ref[0]['Codigo'];
                        $dataRefCliente = array(
                            "Cliente" => $cli_cod,
                            "Referencia" => $llave,
                            "Habilitado" => $cli_hab,
                            "UsuarioCreacion" => $user,
                            "FechaCreacion" => $fecha
                        );

                        if ($this->Referencias_model->saveRefCli($dataRefCliente)) {
                            $RefCli = $this->Referencias_model->obtenerRefClienteCodUserFec($cli_cod, $llave, $user, $fecha, "DESC");
                            if ($RefCli) {
                                $modulo = "Crear Cliente";
                                $accion = "Creación Referencia '" . $cli_nomrf . "' del Cliente '" . $cli_nom . "'";
                                $tabla = "Referencias";
                                $cliente_log = $cli_cod;
                                $enlace = "Clientes|Consultar|" . $cli_cod;
                                $observaciones = "Se enlaza Referencia al Cliente después de Crearse";
                                $dataInsert = $dataReferencia;
                                insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                            } else {
                                $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Referencia.";
                            }
                        } else {
                            $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Referencia.";
                        }
                    } else {
                        $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Referencia.";
                    }
                } else {
                    $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Referencia.";
                }
            } catch (Exception $e) {
                $errores++;
                $lblErrores = 'Ha habido una excepción: ' . $e->getMessage() . "<br>Referencia.";
            }
        }
    }

    public function AddEvent($cli_cod, $cli_nom, $cli_Ven, $cli_IglEve, $cli_BarEve, $cli_FecEve, $cli_hab, $user, $fecha)
    {
        $lblErrores = null;

        try {
            $evento = $this->Eventos_model->obtenerEventoVendIglBarFec($cli_Ven, $cli_IglEve, $cli_BarEve, $cli_FecEve);
            if (isset($evento) && $evento != false) {
                $cli_Eve = $evento[0]["Codigo"];
                $this->session->set_flashdata("cli_Eve", $cli_Eve);
            } else {
                $evento = array(
                    "Vendedor" => $cli_Ven,
                    "Iglesia" => $cli_IglEve,
                    "Barrio" => $cli_BarEve,
                    "Fecha" => $cli_FecEve,
                    "Habilitado" => $cli_hab,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );

                if ($this->Eventos_model->save($evento)) {
                    $dataEvento = $this->Eventos_model->obtenerEventoVendIglBarFec($cli_Ven, $cli_IglEve, $cli_BarEve, $cli_FecEve);
                    if ($dataEvento) {
                        $modulo = "Crear Cliente";
                        $accion = "Creación Evento en la Iglesia '" . $cli_IglEve."'";
                        $tabla = "Eventos";
                        $llave = $dataEvento[0]["Codigo"];
                        $cliente_log = $cli_cod;
                        $enlace = "Clientes|Consultar|" . $cli_cod;
                        $dataInsert = $evento;
                        $observaciones = "Creación Evento en la Iglesia '" . $cli_IglEve."' en el barrio '" . $cli_BarEve . "'. Vendedor: '" . $cli_Ven . "'";
                        $this->session->set_flashdata("cli_Eve", $llave);
                        insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                    }
                }
            }
        } catch (Exception $e) {
            $lblErrores = 'Ha habido una excepción: ' . $e->getMessage() . "<br>Evento.";
        }
    }

    public function AddOrder($cli_cod, $cli_nom, $cli_totalPag, $cli_tar1, $cli_nomTar, $cli_priCobro, $ped_est, $cli_Eve, $cli_Ven, $cli_PagEve, $cli_numCuo, $cli_valCuo, $cli_hab, $user, $fecha)
    {
        $lblErrores = null;
        $observacion = "Se crea Pedido desde el módulo de Clientes. \nCliente: " . $cli_nom . "\nTarifa Aplicada: " . $cli_nomTar
        . "\nTotal a Pagar: " . money_format("%.0n", $cli_totalPag) . "\nCuotas: " . $cli_numCuo
        . "\nValor Cuotas: " . money_format("%.0n", $cli_valCuo) . "\nPrimer Pago: " . $cli_priCobro . "\n "
        . "\nObservación automática.";

        $dataPedido = array(
            "Codigo" => $cli_cod,
            "Cliente" => $cli_cod,
            "Valor" => $cli_totalPag,
            "Tarifa" => $cli_tar1,
            "DiaCobro" => $cli_priCobro,
            "Estado" => $ped_est,
            "Evento" => $cli_Eve,
            "Vendedor" => $cli_Ven,
            "FechaPedido" => $fecha,
            "Saldo" => $cli_totalPag,
            "PaginaFisica" => $cli_PagEve,
            "Habilitado" => $cli_hab,
            "UsuarioCreacion" => $user,
            "FechaCreacion" => $fecha
        );


        try {
            if ($this->Pedidos_model->save($dataPedido)) {
                $ped = $this->Pedidos_model->obtenerPedido($cli_cod);
                if ($ped) {
                    $modulo = "Crear Cliente";
                    $accion = "Creación Pedido al Cliente '" . $cli_nom."'";
                    $tabla = "Pedidos";
                    $llave = $cli_cod;
                    $cliente_log = $cli_cod;
                    $enlace = "Clientes|Consultar|" . $cli_cod;
                    $dataInsert = $dataPedido;
                    $observaciones = $observacion;
                    $this->session->set_flashdata("ped_cod", $llave);
                    insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                } else {
                    $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Pedido.";
                }
            } else {
                $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Pedido.";
            }
        } catch (Exception $e) {
            $lblErrores = 'Ha habido una excepción: ' . $e->getMessage() . "<br>Pedido.";
        }
        
        return $lblErrores;
    }

    public function AddProduct($cli_cod, $cli_nom, $ped_cod, $cli_cant1, $cli_prod1, $cli_nomprod1, $cli_val1, $cli_hab, $user, $fecha)
    {
        $lblErrores = null;
        $dataPedidoPro1 = array(
            "Pedido" => $ped_cod,
            "Cantidad" => $cli_cant1,
            "Producto" => $cli_prod1,
            "Valor" => $cli_val1,
            "Habilitado" => $cli_hab,
            "UsuarioCreacion" => $user,
            "FechaCreacion" => $fecha
        );

        try {
            if ($this->Pedidos_model->saveProPed($dataPedidoPro1)) {
                $pedPro1 = $this->Pedidos_model->obtenerPedidoProUserFec($ped_cod, $cli_prod1, $user, $fecha);
                if ($pedPro1) {
                    $modulo = "Crear Cliente";
                    $accion = "Agregar Producto al Pedido del Cliente '" . $cli_nom."'";
                    $tabla = "ProductosPedidos";
                    $llave = $ped_cod;
                    $cliente_log = $cli_cod;
                    $enlace = "Clientes|Productos|" . $ped_cod;
                    $dataInsert = $dataPedidoPro1;
                    $observaciones = "Se vincula el producto: " . $cli_nomprod1 . " al Pedido " . $ped_cod . " del Cliente " . $cli_nom . ". \n"
                    . "Cantidad del Producto: " . $cli_cant1 . ". \nValor del Producto: " . money_format("%.0n", $cli_val1) . ". \n\nObservación automática.";
                    insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                } else {
                    $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Producto del Pedido.";
                }
            } else {
                $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Producto del Pedido.";
            }
        } catch (Exception $e) {
            $lblErrores = 'Ha habido una excepción: ' . $e->getMessage() . "<br>Producto del Pedido.";
        }
        
        return $lblErrores;
    }

    public function AddPayment($cli_cod, $cli_nom, $ped_cod, $num_cuo, $cli_abono, $cli_totalPag, $cli_hab, $user, $fecha)
    {
        $lblErrores = null;
        //Si hay abono
        $saldo = (intval($cli_totalPag) - intval($cli_abono));
        $dataAbono = array(
            "Cliente" => $cli_cod,
            "Pedido" => $ped_cod,
            "Cuota" => $num_cuo,
            "Pago" => $cli_abono,
            "FechaPago" => $fecha,
            "TotalPago" => $cli_totalPag,
            "Observaciones" => "Abono por valor de: " . money_format("%.0n", $cli_abono) . "\nAbono realizado al momento del pedido.\n"
            . "Saldo Actual: " . money_format("%.0n", ($saldo)) . "\nObservación automática.",
            "Habilitado" => $cli_hab,
            "UsuarioCreacion" => $user,
            "FechaCreacion" => $fecha
        );

        try {
            if ($this->Pagos_model->save($dataAbono)) {
                $abono = $this->Pagos_model->obtenerPagosPedidoUserFec($cli_cod, $ped_cod, $user, $fecha);
                $modulo = "Crear Cliente";
                $accion = "Abono del Cliente '" . $cli_nom."'";
                $tabla = "Pagos";
                $llave = $abono[0]['Codigo'];
                $cliente_log = $cli_cod;
                $enlace = "Pagos|Cliente|" . $cli_cod;
                $dataInsert = $dataAbono;
                $observaciones = "Primer Abono del Cliente, por un valor de " . money_format("%.0n", $cli_abono) . ". Pago automático.";
                insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
                $this->History($cli_cod, $ped_cod, $fecha, $user, "Primer Abono", intval($cli_totalPag), 1, intval($saldo), intval($cli_abono), $dataAbono["Observaciones"]);
        
                $estado = 111;
                if ($saldo <= 0) {
                    $estado = 114;
                }

                $dataActPedido = array(
                    "Saldo" => $saldo,
                    "Estado" => $estado,
                    "FechaUltimoPago" => $fecha,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );
                
                $dataPedido = $this->Pedidos_model->obtenerPedido($ped_cod);
                $dataOriginal = $dataPedido[0];
                $dataNew = compararCambiosLog($dataOriginal, $dataActPedido);
                $this->Pedidos_model->update($ped_cod, $dataNew);
                $modulo = "Crear Cliente";
                $accion = "Actualización Pedido del Cliente '" . $cli_nom."'";
                $tabla = "Pedidos";
                $llave = $ped_cod;
                $cliente_log = $cli_cod;
                $enlace = "Clientes|Consultar|" . $cli_cod;
                $observaciones = "Actualización de Saldo después del primer abono";
                updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                
                if ($saldo <= 0) {
                    //echo $saldo . "<br><br>";
                    $dataActCliente = array(
                        "Estado" => 123,
                        "Observaciones" => "Estado: Paz y Salvo\n---\nCliente queda a Paz y Salvo por Saldo en $ 0\n \nObservación automática.",
                        "UsuarioModificacion" => $user,
                        "FechaModificacion" => $fecha
                    );
                    
                    $dataTemp = $this->Clientes_model->obtenerClienteCampos($cli_cod, "Estado, Observaciones, UsuarioModificacion, FechaModificacion");
                    $dataOriginal = $dataTemp[0];
                    $dataNew = compararCambiosLog($dataOriginal, $dataActCliente);
                    $this->Clientes_model->update($cli_cod, $dataNew);
                    if (count($dataNew) > 2) {
                        $modulo = "Crear Cliente";
                        $accion = "Actualización del Cliente '" . $cli_nom."'";
                        $tabla = "Clientes";
                        $llave = $cli_cod;
                        $cliente_log = $cli_cod;
                        $enlace = "Clientes|Consultar|" . $cli_cod;
                        $observaciones = "Actualización de Estado a Paz y Salvo";
                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                    }
                }
            } else {
                $lblErrores = "No se pudo guardar, por favor intentelo de nuevo. Primer Abono.";
            }
        } catch (Exception $e) {
            $lblErrores = 'Ha habido una excepción: ' . $e->getMessage() . "<br>Primer Abono.";
        }
        
        return $lblErrores;
    }

    public function asignarClienteUsuario($cli_cod, $cli_nom, $usuario, $nomUsuario, $fecha)
    {
        $lblErrores = null;
        //Datos Auditoría
        $user = $this->session->userdata('Usuario');

        try {
            $cliUsu = $this->Clientes_model->getClienteUsuario($cli_cod);
            
            $modulo = "Asignar Clientes";
            $accion = "Asignacion del Cliente '" . $cli_nom."'";
            $tabla = "ClientesUsuarios";
            $llave = $cli_cod;
            $cliente_log = $cli_cod;
            $enlace = "Clientes|Consultar|" . $cli_cod;
            $observaciones = "Se asigna el Cliente '" . $cli_nom . "' al usuario '" . $nomUsuario . "'.\nObservación automática.";
            
            if (isset($cliUsu) && $cliUsu != false) {
                $dataCliUsu = array(
                    "Usuario" => $usuario,
                    "Habilitado" => 1,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );
                
                $dataOriginal = $cliUsu[0];
                $dataNew = compararCambiosLog($dataOriginal, $dataCliUsu);
                $this->Clientes_model->updateClientesUsuariosbyClient($cli_cod, $dataNew);
                updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
            } else {
                $dataCliUsu = array(
                    "Usuario" => $usuario,
                    "Cliente" => $cli_cod,
                    "Habilitado" => 1,
                    "UsuarioCreacion" => $user,
                    "FechaCreacion" => $fecha
                );
                $this->Clientes_model->saveCliUsu($dataCliUsu);
            
                $dataInsert = $dataCliUsu;
                insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
            }
        } catch (Exception $e) {
            $lblErrores = 'Ha habido una excepción: ' . $e->getMessage() . "<br>Producto del Pedido.";
        }
    }

    public function Consultar($cliente)
    {
        $idPermiso = 15;
        $page = validarPermisoPagina($idPermiso);

        if (isset($cliente)) {
            $dataClientes = $this->Clientes_model->obtenerClienteDir($cliente);
            if (isset($dataClientes) && $dataClientes != false) {
                $dataTipDoc = $this->TiposDocumentos_model->obtenerTiposDocumentoCod($dataClientes[0]["TipoDocumento"]);
                if (isset($dataTipDoc) && $dataTipDoc != false) {
                    $dataTiposVivienda = $this->Direcciones_model->obtenerTiposVivienda();
                    if (isset($dataTiposVivienda) && $dataTiposVivienda != false) {
                        $dataReferencias = $this->Referencias_model->obtenerRefClienteData($cliente);
                        $dataRef = array();
                        if (isset($dataReferencias) && $dataReferencias != false) {
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
                        if (isset($dataPedido) && $dataPedido != false) {
                            $pedido = $dataPedido[0]["Codigo"];
                            $dataProdPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
                            if (isset($dataProdPedido) && $dataProdPedido != false) {
                                $dataVendedores = $this->Vendedores_model->obtenerVendedor($dataPedido[0]["Vendedor"]);
                                if (isset($dataVendedores) && $dataVendedores != false) {
                                    $dataEventos = $this->Eventos_model->obtenerEvento($dataPedido[0]["Evento"]);
                                    if (isset($dataEventos) && $dataEventos != false) {
                                        $usuariosAsignado = $this->Usuarios_model->obtenerUsuariosEP();
                                        $UsuarioAsignado = $this->Usuarios_model->obtenerUsuarioAsignadoPorCliente($cliente);
                                         
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
                                        $data->ListaUsuarios = $usuariosAsignado;
                                        $data->UsuarioAsignado = $UsuarioAsignado[0]["Codigo"];

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

    public function UpdateClientDataP()
    {
        $idPermiso = 92;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $cli_cod = trim($this->input->post('Codigo'));
            $cli_nom = trim($this->input->post('Nombre'));
            $cli_doc = trim($this->input->post('Documento'));

            $dataClientes = $this->Clientes_model->obtenerClienteDir($cli_cod);
            if (isset($dataClientes) && $dataClientes != false) {
                //Datos Auditoría
                $user = $this->session->userdata('Usuario');
                $fecha = date("Y-m-d H:i:s");

                //Actualizar Datos
                $dataPersonal = array(
                    "Nombre" => $cli_nom,
                    "Documento" => $cli_doc,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );

                $dataOriginal = $dataClientes[0];
                $dataNew = compararCambiosLog($dataOriginal, $dataPersonal);
                if ($this->Clientes_model->update($cli_cod, $dataNew)) {
                    $modulo = "Consultar Cliente";
                    $accion = "Actualizar Datos Personales del Cliente '" . $cli_nom."'";
                    $tabla = "Clientes";
                    $llave = $cli_cod;
                    $cliente_log = $cli_cod;
                    $enlace = "Clientes|Consultar|" . $cli_cod;
                    $observaciones = "Actualización Nombre y Documento de '" . $cli_nom."'";
                    updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
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

    public function UpdateClientDir()
    {
        $idPermiso = 93;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $cli_cod = trim($this->input->post('cli_cod'));
            $cli_nom = trim($this->input->post('cli_nom'));
            $dataClientes = $this->Clientes_model->obtenerClienteDir($cli_cod);
            if (isset($dataClientes) && $dataClientes != false) {
                $cod_dir = $dataClientes[0]["Direccion"];
                $dataDireccion = $this->Direcciones_model->obtenerDireccionPorCod($cod_dir);
                if (isset($dataDireccion) && $dataDireccion != false) {
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

                    //Actualizar Direccion
                    $data = array(
                        "Direccion" => $cli_dir,
                        "Etapa" => $cli_eta,
                        "Torre" => $cli_tor,
                        "Apartamento" => $cli_apto,
                        "Manzana" => $cli_manz,
                        "Interior" => $cli_int,
                        "Casa" => $cli_casa,
                        "Barrio" => $cli_bar,
                        "TipoVivienda" => $cli_tipviv,
                        "UsuarioModificacion" => $user,
                        "FechaModificacion" => $fecha
                    );

                    $dataOriginal = $dataDireccion[0];
                    $dataNew = compararCambiosLog($dataOriginal, $data);
                    if ($this->Direcciones_model->update($cod_dir, $data)) {
                        $modulo = "Consultar Cliente";
                        $accion = "Actualizar Dirección del Cliente '" . $cli_nom."'";
                        $tabla = "Clientes";
                        $llave = $cod_dir;
                        $cliente_log = $cli_cod;
                        $enlace = "Clientes|Consultar|" . $cli_cod;
                        $observaciones = "Actualización Dirección de '" . $cli_nom."'";
                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
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

    public function UpdateClientTel()
    {
        $idPermiso = 93;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $cli_cod = trim($this->input->post('cli_cod'));
            $cli_nom = trim($this->input->post('cli_nom'));
            $dataClientes = $this->Clientes_model->obtenerCliente($cli_cod);
            if (isset($dataClientes) && $dataClientes != false) {
                //Telefonos
                $cli_tel1 = trim($this->input->post('cli_tel1'));
                $cli_tel2 = trim($this->input->post('cli_tel2'));
                $cli_tel3 = trim($this->input->post('cli_tel3'));
                //Datos Auditoría
                $user = $this->session->userdata('Usuario');
                $fecha = date("Y-m-d H:i:s");

                $dataTel= array(
                    "Telefono1" => $dataClientes[0]["Telefono1"],
                    "Telefono2" => $dataClientes[0]["Telefono2"],
                    "Telefono3" => $dataClientes[0]["Telefono3"]
                );

                //Actualizar Teléfono
                $dataTelefono = array(
                    "Telefono1" => $cli_tel1,
                    "Telefono2" => $cli_tel2,
                    "Telefono3" => $cli_tel3,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );
                
                $dataOriginal = $dataTel;
                $dataNew = compararCambiosLog($dataOriginal, $dataTelefono);
                if ($this->Clientes_model->update($cli_cod, $dataNew)) {
                    if (count($dataNew) > 2) {
                        $modulo = "Consultar Cliente";
                        $accion = "Actualizar Teléfono del Cliente '" . $cli_nom."'";
                        $tabla = "Clientes";
                        $llave = $cli_cod;
                        $cliente_log = $cli_cod;
                        $enlace = "Clientes|Consultar|" . $cli_cod;
                        $observaciones = "Actualización Teléfono de '" . $cli_nom."'";
                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
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

    public function UpdateClientRef()
    {
        $idPermiso = 93;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $cli_cod = trim($this->input->post('cli_cod'));
            $cli_nom = trim($this->input->post('cli_nom'));
            $dataClientes = $this->Clientes_model->obtenerCliente($cli_cod);
            if (isset($dataClientes) && $dataClientes != false) {
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
                    
                    $dataRef = $this->Referencias_model->obtenerReferencia($cli_codrf1);
                    if (!isset($dataRef) && $dataRef == false) {
                        $dReferencia = array();
                    } else {
                        $dReferencia = array(
                            "Nombres" => $dataRef[0]["Nombres"],
                            "Telefono" => $dataRef[0]["Telefono"],
                            "Parentesco" => $dataRef[0]["Parentesco"]
                        );
                    }

                    $dataOriginal = $dReferencia;
                    $dataNew = compararCambiosLog($dataOriginal, $data);
                    $dataOriginal = $dataRef[0];
                    if (count($dataNew) > 2) {
                        if ($this->Referencias_model->update($cli_codrf1, $data)) {
                            $modulo = "Consultar Cliente";
                            $accion = "Actualizar Referencia del Cliente '" . $cli_nom."'";
                            $tabla = "Referencias";
                            $enlace = "Clientes|Consultar|" . $cli_cod;
                            $llave = $cli_codrf1;
                            $cliente_log = $cli_cod;
                            $observaciones = "Actualización Referencia: '" . $cli_nomrf1."'";
                            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                        } else {
                            $errores++;
                            echo "No se pudieron guardar las Referencias. Recargue la página e intente de nuevo.";
                        }
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
                                    $modulo = "Consultar Cliente";
                                    $accion = "Agregar Referencia del Cliente '" . $cli_nom."'";
                                    $tabla = "Referencias";
                                    $llave = $dataReferencia1['Codigo'];
                                    $cliente_log = $cli_cod;
                                    $enlace = "Clientes|Consultar|" . $cli_cod;
                                    $dataInsert = $dataReferencia1;
                                    $observaciones = "";
                                    insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);

                                    $dataRefCliente1 = array(
                                        "Cliente" => $cli_cod,
                                        "Referencia" => $Ref1[0]['Codigo'],
                                        "Habilitado" => 1,
                                        "UsuarioCreacion" => $user,
                                        "FechaCreacion" => $fecha
                                    );

                                    if (!$this->Referencias_model->saveRefCli($dataRefCliente1)) {
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
                    
                    $dataRef = $this->Referencias_model->obtenerReferencia($cli_codrf2);
                    if (!isset($dataRef) && $dataRef == false) {
                        $dReferencia = array();
                    } else {
                        $dReferencia = array(
                            "Nombres" => $dataRef[0]["Nombres"],
                            "Telefono" => $dataRef[0]["Telefono"],
                            "Parentesco" => $dataRef[0]["Parentesco"]
                        );
                    }

                    $dataOriginal = $dReferencia;
                    $dataNew = compararCambiosLog($dataOriginal, $data);
                    $dataOriginal = $dataRef[0];
                    if (count($dataNew) > 2) {
                        if ($this->Referencias_model->update($cli_codrf2, $data)) {
                            $modulo = "Consultar Cliente";
                            $accion = "Actualizar Referencia del Cliente '" . $cli_nom."'";
                            $tabla = "Referencias";
                            $enlace = "Clientes|Consultar|" . $cli_cod;
                            $llave = $cli_codrf2;
                            $cliente_log = $cli_cod;
                            $observaciones = "Actualización Referencia: '" . $cli_nomrf2."'";
                            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                        } else {
                            $errores++;
                            echo "No se pudieron guardar las Referencias. Recargue la página e intente de nuevo.";
                        }
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
                                    $modulo = "Consultar Cliente";
                                    $accion = "Agregar Referencia del Cliente '" . $cli_nom."'";
                                    $tabla = "Referencias";
                                    $llave = $dataReferencia2['Codigo'];
                                    $cliente_log = $cli_cod;
                                    $enlace = "Clientes|Consultar|" . $cli_cod;
                                    $dataInsert = $dataReferencia2;
                                    $observaciones = "";
                                    insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);

                                    $dataRefCliente2 = array(
                                        "Cliente" => $cli_cod,
                                        "Referencia" => $Ref2[0]['Codigo'],
                                        "Habilitado" => 1,
                                        "UsuarioCreacion" => $user,
                                        "FechaCreacion" => $fecha
                                    );

                                    if (!$this->Referencias_model->saveRefCli($dataRefCliente2)) {
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
                    
                    $dataRef = $this->Referencias_model->obtenerReferencia($cli_codrf3);
                    if (!isset($dataRef) && $dataRef == false) {
                        $dReferencia = array();
                    } else {
                        $dReferencia = array(
                            "Nombres" => $dataRef[0]["Nombres"],
                            "Telefono" => $dataRef[0]["Telefono"],
                            "Parentesco" => $dataRef[0]["Parentesco"]
                        );
                    }

                    $dataOriginal = $dReferencia;
                    $dataNew = compararCambiosLog($dataOriginal, $data);
                    $dataOriginal = $dataRef[0];
                    if (count($dataNew) > 2) {
                        if ($this->Referencias_model->update($cli_codrf3, $data)) {
                            $modulo = "Consultar Cliente";
                            $accion = "Actualizar Referencia del Cliente '" . $cli_nom."'";
                            $tabla = "Referencias";
                            $enlace = "Clientes|Consultar|" . $cli_cod;
                            $llave = $cli_codrf3;
                            $cliente_log = $cli_cod;
                            $observaciones = "Actualización Referencia: '" . $cli_nomrf3."'";
                            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                        } else {
                            $errores++;
                            echo "No se pudieron guardar las Referencias. Recargue la página e intente de nuevo.";
                        }
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
                                    $modulo = "Consultar Cliente";
                                    $accion = "Agregar Referencia del Cliente '" . $cli_nom."'";
                                    $tabla = "Referencias";
                                    $llave = $dataReferencia3['Codigo'];
                                    $cliente_log = $cli_cod;
                                    $enlace = "Clientes|Consultar|" . $cli_cod;
                                    $dataInsert = $dataReferencia3;
                                    $observaciones = "";
                                    insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);

                                    $dataRefCliente3 = array(
                                        "Cliente" => $cli_cod,
                                        "Referencia" => $Ref3[0]['Codigo'],
                                        "Habilitado" => 1,
                                        "UsuarioCreacion" => $user,
                                        "FechaCreacion" => $fecha
                                    );

                                    if (!$this->Referencias_model->saveRefCli($dataRefCliente3)) {
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

    public function UpdateClientObs()
    {
        $idPermiso = 93;
        $page = validarPermisoAcciones($idPermiso);
        if ($page) {
            $cli_cod = trim($this->input->post('cli_cod'));
            $cli_ped = trim($this->input->post('cli_ped'));
            $cli_nom = trim($this->input->post('cli_nom'));
            $cli_pag = trim($this->input->post('cli_pag'));
            $cli_usu = trim($this->input->post('cli_usu'));
            $cli_usu_nom = trim($this->input->post('cli_usu_nom'));
            $cli_obs = ucfirst(strtolower(trim($this->input->post('cli_obs'))));
            //Datos Auditoría
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");

            $errores = 0;
            $dataClientes = $this->Clientes_model->obtenerCliente($cli_cod);
            if (isset($dataClientes) && $dataClientes != false) {
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
                
                $dataPedido = $this->Pedidos_model->obtenerPedido($cli_ped);
                $dPagina = array('PaginaFisica' => $dataPedido[0]["PaginaFisica"]);
                $dataOriginal = $dPagina;
                $dataNew = compararCambiosLog($dataOriginal, $dataPag);
                if ($this->Pedidos_model->update($cli_ped, $dataPag)) {
                    if (count($dataNew) > 2) {
                        $modulo = "Consultar Cliente";
                        $accion = "Actualizar Observaciones del Cliente '" . $cli_nom."'";
                        $tabla = "Pedidos";
                        $llave = $cli_cod;
                        $cliente_log = $cli_cod;
                        $enlace = "Clientes|Consultar|" . $cli_cod;
                        $observaciones = "Actualización de Ubicación/Página Física del '" . $cli_nom."'";
                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                    }
                } else {
                    $errores++;
                    echo "No se pudo guardar la ubicación física. Recargue la página e intente de nuevo.";
                }

                if (strlen($cli_obs) > 0) {
                    $dataOriginal = $dataClientes[0];
                    $dataNew = compararCambiosLog($dataOriginal, $dataObs);
                    if ($this->Clientes_model->update($cli_cod, $dataObs)) {
                        if (count($dataNew) > 2) {
                            $modulo = "Consultar Cliente";
                            $accion = "Actualizar Observaciones del Cliente '" . $cli_nom."'";
                            $tabla = "Clientes";
                            $llave = $cli_cod;
                            $cliente_log = $cli_cod;
                            $enlace = "Clientes|Consultar|" . $cli_cod;
                            $observaciones = "Actualización de Observaciones del '" . $cli_nom."'";
                            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                        }
                    } else {
                        $errores++;
                        echo "No se pudieron guardar las Observaciones. Recargue la página e intente de nuevo.";
                    }
                }
                
                $lblErrores = $this->asignarClienteUsuario($cli_cod, $cli_nom, $cli_usu, $cli_usu_nom, $fecha);
                if ($lblErrores != null) {
                    echo $lblErrores;
                    $errores++;
                    return false;
                }
                
                if ($errores <= 0) {
                    echo "1";
                }
            } else {
                echo "No se puede acceder a los datos del Cliente. Recargue la página e intente de nuevo.";
            }
        } else {
            echo "No tiene permisos para Modificar los datos del Cliente";
        }
    }

    public function Pagos($cliente)
    {
        if (isset($cliente)) {
            redirect(base_url("Pagos/Cliente/" . $cliente . "/"));
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a los pagos del Cliente");
            redirect(base_url() . "Clientes/Admin/");
        }
    }

    public function Log($cliente)
    {
        if ($cliente == null || $cliente == "") {
            $this->session->set_flashdata("error", "No se encontró Cliente.");
            redirect(base_url("/Clientes/Admin/"));
        } else {
            $dataClientes = $this->Clientes_model->obtenerClienteDir($cliente);
            if (isset($dataClientes) && $dataClientes != false) {
                $dataLog = $this->Clientes_model->LogCliente($cliente);
                if (isset($dataLog) && $dataLog != false) {
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

    public function CambioFecha($cliente)
    {
        $idPermiso = 16;
        $page = validarPermisoPagina($idPermiso);

        if (isset($cliente)) {
            $dataClientes = $this->Clientes_model->obtenerClienteDir($cliente);
            if (isset($dataClientes) && $dataClientes != false) {
                $dataPedido = $this->Pedidos_model->obtenerPedidosCliente($cliente);
                if (isset($dataPedido) && $dataPedido != false) {
                    $pedido = $dataPedido[0]["Codigo"];
                    $dataProdPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
                    if (isset($dataProdPedido) && $dataProdPedido != false) {
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

    public function ChangePayDate()
    {
        $idPermiso = 95;
        $accion = validarPermisoAcciones($idPermiso);
        if ($accion) {
            $cli_ped = trim($this->input->post('cli_ped'));
            $cli_cod = trim($this->input->post('cli_cli'));
            $cli_nom = trim($this->input->post('cli_nom'));
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
                $dataTemp = $this->Pedidos_model->obtenerPedidoCampos($cli_ped, "DiaCobro, UsuarioModificacion, FechaModificacion");
                $dataOriginal = $dataTemp[0];
                $dataNew = compararCambiosLog($dataOriginal, $dataP);
                
                if ($this->Pedidos_model->update($cli_ped, $dataP)) {
                    if (count($dataNew) > 2) {
                        $modulo = "Cambiar Fecha de Pago";
                        $accion = "Actualizar Fecha de Pago del Cliente '" . $cli_nom."'";
                        $tabla = "Pedidos";
                        $llave = $cli_ped;
                        $cliente_log = $cli_cod;
                        $enlace = "Clientes|CambioFecha|" . $cli_cod;
                        $observaciones = "Se cambia Fecha de Pago\nNueva fecha de Cobro: " . $cli_fec . "\n \nObservación automática.";
                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                    }
                    echo 1;
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

    public function CambioTarifa($cliente)
    {
        $idPermiso = 17;
        $page = validarPermisoPagina($idPermiso);

        if (isset($cliente)) {
            $dataClientes = $this->Clientes_model->obtenerClienteDir($cliente);
            if (isset($dataClientes) && $dataClientes != false) {
                $dataPedido = $this->Pedidos_model->obtenerPedidosCliente($cliente);
                if (isset($dataPedido) && $dataPedido != false) {
                    $pedido = $dataPedido[0]["Codigo"];
                    $dataProdPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
                    if (isset($dataProdPedido) && $dataProdPedido != false) {
                        $Producto = $dataProdPedido[0]["Producto"];
                        $dataTarifa = $this->Tarifas_model->obtenerTarifaPorProducto($Producto);
                        if (isset($dataTarifa) && $dataTarifa != false) {
                            $pago = $dataPedido[0]["Valor"] - $dataPedido[0]["Saldo"];

                            $data = new stdClass();
                            $data->Controller = "Clientes";
                            $data->title = "Cambio de Tarifa";
                            $data->subtitle = $dataClientes[0]["Nombre"];
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

    public function changeRate()
    {
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
            $cli_cod = trim($this->input->post('cli_cli'));
            $cli_nom = trim($this->input->post('cli_nom'));
            //Datos Auditoría
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");

            $dataPedido = array(
                "Valor" => $tar_tot,
                "Tarifa" => $tar_tar,
                "Saldo" => $tar_sal,
                "UsuarioModificacion" => $user,
                "FechaModificacion" => $fecha
            );

            try {
                $dataTemp = $this->Pedidos_model->obtenerPedidoCampos($tar_ped, "Valor, Tarifa, Saldo, UsuarioModificacion, FechaModificacion");
                $dataOriginal = $dataTemp[0];
                $dataNew = compararCambiosLog($dataOriginal, $dataPedido);
                if ($this->Pedidos_model->update($tar_ped, $dataPedido)) {
                    if (count($dataNew) > 2) {
                        $modulo = "Cambiar Tarifa Pedido";
                        $accion = "Actualizar Tarifa del Cliente '" . $cli_nom."'";
                        $tabla = "Pedidos";
                        $llave = $tar_ped;
                        $cliente_log = $cli_cod;
                        $enlace = "Clientes|Consultar|" . $cli_cod;
                        $observaciones = "Se cambia la tarifa de Pago\nNueva Tarifa: " . $tar_nom . "\n \nObservación automática.";
                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
                    }

                    $dataPagos = $this->Pagos_model->obtenerPagosPedido($tar_ped);
                    if (isset($dataPagos) && $dataPagos != false) {
                        foreach ($dataPagos as $value) {
                            $pago = array(
                                "TotalPago" => $tar_tot,
                                "Observaciones" => $value["Observaciones"] . "\n---\nSe actualiza Tarifa: " . $tar_tot,
                                "UsuarioModificacion" => $user,
                                "FechaModificacion" => $fecha
                            );
 
                            $this->Pagos_model->update($value["Codigo"], $pago);

                            $this->History($cli_cod, $tar_ped, $fecha, $user, "Cambio de Tarifa", $dataTemp[0]["Saldo"], 0, $tar_sal, 0, $observaciones);
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

    public function Contador()
    {
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

    public function ConteoClientesPost()
    {
        $fecha1 = trim($this->input->post('pag_fec1') . " 00:00:00");
        $fecha1 = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fecha1);
        $fecha2 = trim($this->input->post('pag_fec2') . " 23:59:59");
        $fecha2 = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fecha2);
        $Clientes = $this->ConteoClientes($fecha1, $fecha2);

        echo json_encode($Clientes);
    }

    public function ConteoClientes($fecha1, $fecha2)
    {
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

    public function Asignados()
    {
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

    public function Productos($pedido)
    {
        if (isset($pedido)) {
            $dataProdPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
            if (isset($dataProdPedido) && $dataProdPedido != false) {
                $dataProductos = $this->Productos_model->obtenerProductos();
                if (isset($dataProductos) && $dataProductos != false) {
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

    public function AddProducto()
    {
        $pedido = trim($this->input->post('pedido'));
        $producto = trim($this->input->post('producto'));
        $nombre = trim($this->input->post('nombre'));
        $cli_nom = trim($this->input->post('cli_nom'));
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
            if (isset($dataProdPedido) && $dataProdPedido != false) {
                $cli_cod = $dataProdPedido[0]["CodCliente"];
                $dataProductos = $this->Productos_model->obtenerProductos();
                if (isset($dataProductos) && $dataProductos != false) {
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
                                    $modulo = "Productos del Cliente";
                                    $accion = "Agregar Producto al Cliente '" . $cli_nom."'";
                                    $tabla = "ProductosPedidos";
                                    $llave = $pedido;
                                    $cliente_log = $cli_cod;
                                    $enlace = "Clientes|Productos|" . $llave;
                                    $dataInsert = $dataProductoPedido;
                                    $observaciones = "Se agregó  " . $cantidad . " unidad(es) de " . $nombre . " al Cliente " . $cli_nom . " con un valor de " . money_format("%.0n", $valor);
                                    insertLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataInsert, $observaciones);
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

                                $dataTemp = $this->Productos_model->obtenerProductosPedidosCampos($pedido, $producto, "Cantidad, Valor, UsuarioModificacion, FechaModificacion");
                                $dataOriginal = $dataTemp[0];
                                $dataNew = compararCambiosLog($dataOriginal, $dataProductoPedido);
                                if ($this->Pedidos_model->updateProPedidoxPedido($pedido, $producto, $dataProductoPedido)) {
                                    if (count($dataNew) > 2) {
                                        $modulo = "Productos del Cliente";
                                        $accion = "Actualizar Producto al Cliente '" . $cli_nom."'";
                                        $tabla = "ProductosPedidos";
                                        $llave = $pedido;
                                        $cliente_log = $cli_cod;
                                        $enlace = "Clientes|Consultar|" . $llave;
                                        $cant = $item["Cantidad"] + $cantidad;
                                        $val = $item["ValPP"] + $valor;
                                        $observaciones = "Se actualizaron las unidades de " . $nombre . " a " .  $cant . " unidades, al Cliente " . $cli_nom . " con un valor de " . money_format("%.0n", $val);
                                        updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
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
                        
                        $dataTemp = $this->Pedidos_model->obtenerPedidoCampos($pedido, "Valor, Saldo, UsuarioModificacion, FechaModificacion");
                        $dataOriginal = $dataTemp[0];
                        $dataNew = compararCambiosLog($dataOriginal, $dataPedido);
                        if ($this->Pedidos_model->update($pedido, $dataPedido)) {
                            $dataPed = $this->Pedidos_model->obtenerPedido($pedido);
                            $modulo = "Agregar Producto";
                            $tabla = "Pedidos";
                            $accion = "Actualizar Pedido";
                            $data = compararCambiosLog($dataPed, $dataPedido);
                            if (count($dataNew) > 2) {
                                $modulo = "Productos del Cliente";
                                $accion = "Agregar Producto al Cliente '" . $cli_nom."'";
                                $tabla = "Pedidos";
                                $llave = $pedido;
                                $cliente_log = $cli_cod;
                                $enlace = "Clientes|Productos|" . $llave;
                                $sal = $item["Saldo"] + $valor;
                                $val = $item["Valor1"] + $valor;
                                $observaciones = "Se actualiza Valor y Saldo del Cliente " . $cli_nom . "\nValor: " . money_format("%.0n", $val) . "\nSaldo: " . money_format("%.0n", $sal);
                                updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
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

    public function DeleteProducto()
    {
        $pedido = trim($this->input->post('pedido'));
        $producto = trim($this->input->post('producto'));
        if (isset($pedido)) {
            $dataProdPedido = $this->Pedidos_model->obtenerProductosPedidoCliente($pedido);
            if (isset($dataProdPedido) && $dataProdPedido != false) {
                echo $numeroProductos = count(dataProdPedido);
            } else {
                $this->session->set_flashdata("error", "No se puede acceder a los productos del Pedido del Cliente");
                redirect(base_url() . "Clientes/Admin/");
            }
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a los datos del Pedido del Cliente");
            redirect(base_url() . "Clientes/Admin/");
        }
    }
}
