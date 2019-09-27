<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Importar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Importar';
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

        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI] );
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        }
    }

    public function Clientes() {
        $data = new stdClass();
        $data->Controller = "Clientes";
        $data->title = "Importar Clientes";
        $data->subtitle1 = "Subir Cliente";
        $data->subtitle2 = "Resultados de Importación";
        $data->contenido = $this->viewControl . '/Clientes';

        $this->load->view('frontend', $data);
    }

    public function ClientesUp() {
        $encabezado = trim($this->input->post('encabezado'));
        $csvMimes = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv');
        if (!empty($_FILES['fileUp']['name']) && in_array($_FILES['fileUp']['type'], $csvMimes)) {
            if (is_uploaded_file($_FILES['fileUp']['tmp_name'])) {
                $csvFile = fopen($_FILES['fileUp']['tmp_name'], 'r');

                if ($encabezado == "on") {
                    fgetcsv($csvFile);
                }

                $i = 0;
                while (!feof($csvFile)) {
                    $line = utf8_encode(fgets($csvFile));
                    $dataFile = explode(";", $line);
                    if ($dataFile[0] != "") {
                        $i++;
                        $cli_nom = ucwords(strtolower(trim($dataFile[0])));
                        $cli_tipdoc = "101";
                        $cli_doc = trim($dataFile[1]);
                        $cli_dir = ucwords(strtolower(trim($dataFile[2])));
                        $cli_eta = "";
                        $cli_tor = "";
                        $cli_apto = "";
                        $cli_manz = "";
                        $cli_int = "";
                        $cli_casa = "";
                        $cli_bar = substr($cli_dir, 0, 30);
                        $cli_tipviv = "101";
                        $cli_tel1 = trim($dataFile[3]);
                        $cli_tel2 = trim($dataFile[4]);
                        $cli_tel3 = trim($dataFile[5]);
                        $cli_PagEve = ucwords(strtolower(trim($dataFile[6])));
                        $cli_Eve = 101;
                        $cli_Ven = 101;
                        $cli_FecEve = trim($dataFile[7] . " 00:00:00");
                        $cli_FecEve = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $cli_FecEve);
                        $cli_totalPag = trim($dataFile[8]);
                        $fec_ult_ped = trim($dataFile[9] . " 00:00:00");
                        $fec_ult_ped = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fec_ult_ped);
                        $cli_tar1 = "1";
                        $sal_ped = $dataFile[10];
                        $cli_priCobro = trim($dataFile[11] . " 00:00:00");
                        $cli_priCobro = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $cli_priCobro);
                        $cli_Obs = ucfirst(strtolower(trim($dataFile[12])));
                        $num_pag = trim($dataFile[13]);
                        $pag_pag = trim($dataFile[14]);
                        $fec_pag = trim($dataFile[15] . " 00:00:00");
                        $fec_pag = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $fec_pag);
                        $cli_usu = trim($dataFile[16]);
                        //Datos Auditoría
                        $user = $this->session->userdata('Usuario');
                        $fecha = date("Y-m-d H:i:s");

                        $total = $sal_ped + $pag_pag;

                        if ($total == $cli_totalPag) {
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

                            try {
                                if ($this->Clientes_model->save($dataCliente)) {
                                    $Cli = $this->Clientes_model->obtenerClienteDocLast($cli_doc);
                                    $dataCliente['Codigo'] = $Cli[0]['Codigo'];
                                } else {
                                    echo "No se pudo guardar el cliente: " . $cli_nom . "Por favor intentelo de nuevo.";
                                }
                            } catch (Exception $e) {
                                echo "No se pudo guardar el cliente: " . $cli_nom . "Por favor intentelo de nuevo: " . $e->getMessage();
                            }

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
                                    $dir = $this->Direcciones_model->obtenerDireccionPorDirUserFecLast($cli_dir, $user, $fecha);
                                    $dataTemp = array(
                                        "Direccion" => $dir[0]['Codigo']
                                    );
                                    $this->Clientes_model->update($Cli[0]['Codigo'], $dataTemp);
                                } else {
                                    echo "No se pudo guardar el cliente: " . $cli_nom . "Por favor intentelo de nuevo.";
                                }
                            } catch (Exception $e) {
                                echo "No se pudo guardar el cliente: " . $cli_nom . "Por favor intentelo de nuevo: " . $e->getMessage();
                            }

                            $cli_nomTar = "Tarifa Importada sin Valor";

                            //Crear Pedido
                            $dataPedido = array(
                                "Cliente" => $Cli[0]['Codigo'],
                                "Valor" => $cli_totalPag,
                                "Tarifa" => $cli_tar1,
                                "DiaCobro" => $cli_priCobro,
                                "Estado" => 110,
                                "Evento" => $cli_Eve,
                                "Vendedor" => $cli_Ven,
                                "FechaPedido" => $cli_FecEve,
                                "Saldo" => $cli_totalPag,
                                "PaginaFisica" => $cli_PagEve,
                                "Observaciones" => "Se crea Pedido desde el módulo de Importar. \nCliente: " . $cli_nom . "\nTarifa Aplicada: " . $cli_nomTar
                                . "\nTotal a Pagar: " . money_format("%.0n", $cli_totalPag)
                                . "\nSaldo: " . money_format("%.0n", $sal_ped) . "\n "
                                . "\nObservación automática.",
                                "Habilitado" => 1,
                                "UsuarioCreacion" => $user,
                                "FechaCreacion" => $fecha
                            );

                            try {
                                if ($this->Pedidos_model->save($dataPedido)) {
                                    $ped = $this->Pedidos_model->obtenerPedidoCliValUserFecLast($Cli[0]['Codigo'], $cli_priCobro, $user, $fecha);

                                    $dataCliUsu = array(
                                        "Usuario"=> $cli_usu,
                                        "Cliente"=> $Cli[0]['Codigo'],
                                        "Habilitado" => 1,
                                        "UsuarioCreacion" => $user,
                                        "FechaCreacion" => $fecha
                                    );

                                    if (($this->Clientes_model->saveCliUsu($dataCliUsu)) == FALSE) {
                                        echo "No se pudo guardar el cliente: " . $cli_nom . "Por favor intentelo de nuevo.";
                                    }
                                } else {
                                    echo "No se pudo guardar el cliente: " . $cli_nom . "Por favor intentelo de nuevo.";
                                }
                            } catch (Exception $e) {
                                echo "No se pudo guardar el cliente: " . $cli_nom . "Por favor intentelo de nuevo: " . $e->getMessage();
                            }

                            $cli_cant1 = 1;
                            $cli_prod1 = 101;
                            $pro = $this->Productos_model->obtenerProductoCod($cli_prod1);
                            $cli_val1 = $pro[0]["Valor"];
                            $cli_nomprod1 = $pro[0]["Nombre"];

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
                                if (($this->Pedidos_model->saveProPed($dataPedidoPro1)) == FALSE) {
                                    echo "No se pudo guardar el cliente: " . $cli_nom . "Por favor intentelo de nuevo.";
                                }
                            } catch (Exception $e) {
                                echo "No se pudo guardar el cliente: " . $cli_nom . "Por favor intentelo de nuevo: " . $e->getMessage();
                            }

                            if (trim($pag_pag) != "" && trim($fec_pag) != "") {
                                $pag_obs = "Pago de " . money_format("%.0n", $pag_pag) . " el día " . $fec_pag . ".\n\nSubida Automática.";
                                $this->conf($Cli[0]['Codigo'], $ped[0]['Codigo'], $num_pag, $pag_pag, $fec_pag, $cli_totalPag, $pag_obs);
                            }

                            echo "Registro " . $i . " Ok<br>";
                        } else {
                            echo "Registro " . $i . ". Los datos no coinciden, por favor validar.<br>";
                            echo "Nombre: " . $cli_nom . "<br>Tipo Doc: " . $cli_tipdoc . "<br>Documento: " . $cli_doc .
                            "<br>Dirección: " . $cli_dir . "<br>Barrio: " . $cli_bar . "<br>Tipo Vivienda: " . $cli_tipviv .
                            "<br>Teléfono 1: " . $cli_tel1 . "<br>Teléfono 2: " . $cli_tel2 . "<br>Teléfono 3: " . $cli_tel3 .
                            "<br>Ubicacion: " . $cli_PagEve . "<br>Evento: " . $cli_Eve . "<br>Fecha Pedido: " . $cli_FecEve .
                            "<br>Valor Pedido: " . $cli_totalPag . "<br>Último Pago: " . $fec_ult_ped . "<br>Tarifa: " . $cli_tar1 .
                            "<br>Saldo: " . $sal_ped . "<br>Fecha Cobro: " . $cli_priCobro . "<br>Observaciones: " . $cli_Obs .
                            "<br>Número Pago: " . $num_pag . "<br>Valor Pago: " . $pag_pag . "<br>Fecha Pago: " . $fec_pag . "<br><br>";
                        }
                    }
                }

                //                $this->session->set_flashdata("msg", "Se agregaron " . $i . " Clientes con sus Pagos respectivos.");
                //                redirect(site_url("Clientes/Admin/"));
            }
        }
    }

    public function conf($pag_cli, $pag_ped, $pag_cuo, $pag_pag, $pag_fec, $pag_tot, $pag_obs) {
        //Datos Auditoría
        $user = $this->session->userdata('Usuario');
        $fecha = date("Y-m-d H:i:s");

        $pag_fec = trim($pag_fec . " 00:00:00");
        $pag_fec = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $pag_fec);

        //Confirmar Pago (Crear Pago y actualizar Recibo de Pago)
        $dataPago = array(
            "Cliente" => $pag_cli,
            "Pedido" => $pag_ped,
            "Cuota" => $pag_cuo,
            "Pago" => $pag_pag,
            "FechaPago" => $pag_fec,
            "TotalPago" => $pag_tot,
            "Observaciones" => $pag_obs,
            "Habilitado" => 1,
            "UsuarioCreacion" => $user,
            "FechaCreacion" => $fecha
        );

        try {
            if ($this->Pagos_model->save($dataPago)) {
                $Pag = $this->Pagos_model->obtenerPagosPedidoUserFec($pag_cli, $pag_ped, $user, $fecha);
                if ($Pag) {
                    $dataPago['Codigo'] = $Pag[0]['Codigo'];
                    $dataPago['Observaciones'] = "Estado: Pago Realizado\n---\n" . $pag_obs;

                    $dataPedido = $this->Pedidos_model->obtenerPedido($pag_ped);
                    $saldo = intval($dataPedido[0]["Saldo"]) - intval($pag_pag);
                    //Se Crea Historial Pago
                    $this->History($pag_cli, $pag_ped, $fecha, $user, "Confirmar Pago", $dataPedido[0]["Saldo"], $pag_cuo, $saldo, $pag_pag, $pag_obs);

                    if ($saldo <= 0) {
                        $dataActPedido = array(
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
                            "Saldo" => $saldo,
                            "Estado" => 111,
                            "FechaUltimoPago" => $fecha,
                            "Observaciones" => "Actualización de Saldo del Pedido:\nSaldo Anterior: " . money_format("%.0n", ($dataPedido[0]["Valor"]))
                            . "\nSaldo Actual: " . money_format("%.0n", ($saldo)) . "\nEstado: Pagado\n \nObservación automática.",
                            "UsuarioModificacion" => $user,
                            "FechaModificacion" => $fecha
                        );
                    }

                    if ($this->Pedidos_model->update($pag_ped, $dataActPedido)) {
                        $dataPagProg = array(
                            "Estado" => 117,
                            "Observaciones" => $pag_obs,
                            "UsuarioModificacion" => $user,
                            "FechaModificacion" => $fecha
                        );

                        if ($saldo <= 0) {
                            $dataCli = array(
                                "Estado" => 123,
                                "Observaciones" => "Estado: Paz y Salvo\n---\nCliente queda a Paz y Salvo por Saldo en $ 0\n \nObservación automática.",
                                "UsuarioModificacion" => $user,
                                "FechaModificacion" => $fecha
                            );
                            if ($this->Clientes_model->update($dataPedido[0]["Cliente"], $dataCli)) {
                                $dataCliente = $this->Clientes_model->obtenerCliente($dataPedido[0]["Cliente"]);
                            } else {
                                echo "No se pudo Actualizar el Estado del Cliente. Actualice la página y vuelva a intentarlo.";
                            }
                        } else {
                            $dataCli = array(
                                "Estado" => 104,
                                "UsuarioModificacion" => $user,
                                "FechaModificacion" => $fecha
                            );
                            if ($this->Clientes_model->update($dataPedido[0]["Cliente"], $dataCli)) {
                                $dataCliente = $this->Clientes_model->obtenerCliente($dataPedido[0]["Cliente"]);
                            } else {
                                echo "No se pudo Actualizar el Estado del Cliente. Actualice la página y vuelva a intentarlo.";
                            }
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

}
