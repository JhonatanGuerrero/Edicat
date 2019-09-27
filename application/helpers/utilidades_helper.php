<?php

defined('BASEPATH') or exit('No direct script access allowed');

function insertLog($modulo, $accion, $tabla, $llave, $cliente, $enlace, $data, $observaciones)
{
    $ci = & get_instance();
    $sentencia = 'INSERT';

    $insert = "";
    foreach ($data as $key => $value) {
        $insert = $insert . $key . ": " . $value . "|";
    }

    $Coduser = trim($ci->session->userdata('Codigo'));
    $user = trim($ci->session->userdata('Usuario'));
    $fecha = date("Y-m-d H:i:s");

    $sql = 'INSERT INTO `Log` VALUES (NULL, "'.$modulo.'", "'.$accion.'", "'.$tabla.'", "'.$llave.'", "'.$cliente.'", "'.$enlace.'", '.
            '"'.$sentencia.'", "'.$insert.'", NULL, NULL, NULL, "'.$observaciones.'", "'.$Coduser.'", "'.$user.'", "'.$fecha.'")';
    $ci->db->query($sql);
}

function updateLog($modulo, $accion, $tabla, $llave, $cliente, $enlace, $dataOriginal, $dataNew, $observaciones)
{
    $ci = & get_instance();
    $sentencia = 'UPDATE';

    $select = "";
    foreach ($dataOriginal as $key => $value) {
        $select = $select . $key . ": " . $value . "|";
    }
    
    $update = "";
    foreach ($dataNew as $key => $value) {
        $update = $update . $key . ": " . $value . "|";
    }

    $Coduser = trim($ci->session->userdata('Codigo'));
    $user = trim($ci->session->userdata('Usuario'));
    $fecha = date("Y-m-d H:i:s");

    $sql = 'INSERT INTO `Log` VALUES (NULL, "'.$modulo.'", "'.$accion.'", "'.$tabla.'", "'.$llave.'", "'.$cliente.'", "'.$enlace.'", '.
            '"'.$sentencia.'", NULL, "'.$select.'", "'.$update.'", NULL, "'.$observaciones.'", "'.$Coduser.'", "'.$user.'", "'.$fecha.'")';
    $ci->db->query($sql);
}

function LogSave($data, $modulo, $tabla, $accion, $llave)
{
    if (isset($modulo) && isset($tabla) && isset($llave)) {
        $Obs = "";
        if (isset($data['Observaciones'])) {
            $Obs = $data['Observaciones'];
            unset($data['Observaciones']);
        }
        if (isset($data['Pass'])) {
            unset($data['Pass']);
        }
        if (isset($data['Salt'])) {
            unset($data['Salt']);
        }
        if (isset($data['Habilitado'])) {
            if ($data['Habilitado'] != '0' && $data['Habilitado'] != 0) {
                unset($data['Habilitado']);
            }
        }

        unset($data['UsuarioCreacion'], $data['FechaCreacion'], $data['UsuarioModificacion'], $data['FechaModificacion']);
        $ci = & get_instance();

        $user = trim($ci->session->userdata('Usuario'));
        $Coduser = trim($ci->session->userdata('Codigo'));
        $fecha = date("Y-m-d H:i:s");
        $var_data = "";

        foreach ($data as $key => $value) {
            $var_data = $var_data . $key . ": " . $value . "\n";
        }

        $dataSave = array(
            "Codigo" => null,
            "Modulo" => $modulo,
            "Tabla" => $tabla,
            "Usuario" => $user,
            "Fecha" => $fecha,
            "Accion" => $accion,
            "Llave" => $llave,
            "Datos" => $var_data,
            "Observaciones" => $Obs
        );

        // $sql = 'INSERT INTO `Log`(`Codigo`, `Modulo`, `Tabla`, `CodUsuario`, `Usuario`, `Fecha`, `Accion`, `Llave`, `Datos`, `Observaciones`) ' .
        //         'VALUES (null, "' . $dataSave["Modulo"] . '", "' . $dataSave["Tabla"] . '", "' . $Coduser . '","' . $dataSave["Usuario"] . '", "' . $dataSave["Fecha"] . '", ' .
        //         '"' . $dataSave["Accion"] . '", "' . $dataSave["Llave"] . '", "' . $dataSave["Datos"] . '", "' . $dataSave["Observaciones"] . '")';
        // $ci->db->query($sql);
        //echo $sql;
    }
}

function compararCambiosLog($dataAnterior, $dataNuevo)
{
    $d = "";
    foreach ($dataAnterior as $key => $value) {
        if (isset($dataNuevo[$key])) {
            if ($key != 'UsuarioModificacion' && $key != 'FechaModificacion') {
                $d = $d . "<br>" . $dataAnterior[$key] . " - " . $dataNuevo[$key];
                if ($dataAnterior[$key] === $dataNuevo[$key]) {
                    $d = $d . "-ok";
                    unset($dataNuevo[$key]);
                }
            }
        }
    }
    return $dataNuevo;
}

function Deuda($pedido = null)
{
    $ci = & get_instance();
    $ci->load->model('Clientes_model');
    $ci->load->model('Pedidos_model');
    $ci->load->model('Pagos_model');

    //Datos Auditoría
    $user = $ci->session->userdata('Usuario');
    $fecha = date("Y-m-d H:i:s");
    //$this->Pagos_model->updateEspaciosPagosProg();

    $dataPedidos = $ci->Pedidos_model->obtenerPedidosActivos($pedido);
    if (isset($dataPedidos) && $dataPedidos != false) {
        foreach ($dataPedidos as $value) {
            if ($value["Saldo"] > 0) {
                $EstCliente = $value["EstCliente"];
                $fechaPago = date("Y-m-d", strtotime($value["DiaCobro"]));
                $fecha = date("Y-m-d", strtotime($fecha));
                $interval = date_diff(date_create($fechaPago), date_create($fecha));
                $diferencia = intval($interval->format('%a'));
                $signo = $interval->format('%R');
                if ($signo == "-") {
                    $diferencia = $diferencia * -1;
                }

                $cambio = 0;

                if ($diferencia <= 10) {
                    $estado = "Al día";
                    $CodEstadoPed = 111;
                    $CodEstadoCli = 104;
                } elseif ($diferencia >= 11 && $diferencia < 45) {
                    $estado = "Debe";
                    $CodEstadoPed = 112;
                    $CodEstadoCli = 105;
                } elseif ($diferencia >= 45 && $diferencia < 90) {
                    $estado = "En Mora";
                    $CodEstadoPed = 112;
                    $CodEstadoCli = 124;
                } elseif ($diferencia >= 90) {
                    $estado = "DataCrédito";
                    $CodEstadoPed = 125;
                    $CodEstadoCli = 115;
                }

                $cambio = ($CodEstadoCli != $EstCliente) ? 1 : 0;


                if ($cambio > 0) {
                    cambiarEstadoPedidoDeuda($value["Codigo"], $CodEstadoPed, $estado);
                    cambiarEstadoClienteDeuda($value["Cliente"], $CodEstadoCli, $value["Codigo"], $estado);
                    $val = $val = $ci->Pedidos_model->valPedido($value["Codigo"]);
                    if ($val == false) {
                        //Crear Registro Validacion Deuda
                        valDeudaSave($value["Codigo"], $value["Cliente"], $diferencia, $estado);
                    } else {
                        valDeudaUpdate($val[0]["Codigo"], $diferencia, $estado);
                    }
                }
            }
        }
    }
    return 1;
}

function cambiarEstadoPedidoDeuda($pedido, $estado, $est)
{
    $ci = & get_instance();
    $ci->load->model('Pedidos_model');

    //Datos Auditoría
    $user = $ci->session->userdata('Usuario');
    $fecha = date("Y-m-d H:i:s");

    $dataP = array(
        "Estado" => $estado,
        "UsuarioModificacion" => $user,
        "FechaModificacion" => $fecha
    );

    try {
        $datapedido = $ci->Pedidos_model->obtenerPedido($pedido);
        $cliente = $datapedido[0]["Cliente"];
        $dataOriginal = $datapedido[0];
        $dataNew = compararCambiosLog($dataOriginal, $dataP);
        if ($ci->Pedidos_model->update($pedido, $dataNew)) {
            $modulo = "Deuda Pedido";
            $tabla = "Pedido";
            $accion = "Cambio Estado Pedido";
            $llave = $pedido;
            $cliente_log = $cliente;
            $enlace = "Clientes|Consultar|" . $cliente;
            $observaciones = "Estado: " . $est . "\n---\nSe actualiza estado del Pedido de forma automática\n \nObservación automática.";
            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
        } else {
            return "No se pudo Actualizar el Pedido.";
        }
    } catch (Exception $e) {
        return 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
    }
    return 1;
}

function cambiarEstadoClienteDeuda($cliente, $estado, $pedido, $est)
{
    $ci = & get_instance();
    $ci->load->model('Clientes_model');
    
    //Datos Auditoría
    $user = $ci->session->userdata('Usuario');
    $fecha = date("Y-m-d H:i:s");

    $dataC = array(
        "Estado" => $estado,
        "UsuarioModificacion" => $user,
        "FechaModificacion" => $fecha
    );

    try {
        $dataClientes = $ci->Clientes_model->obtenerCliente($cliente);
        $dataOriginal = $dataClientes[0];
        $dataNew = compararCambiosLog($dataOriginal, $dataC);
        if ($ci->Clientes_model->update($cliente, $dataNew)) {
            $modulo = "Deuda Pedido";
            $accion = "Cambio Estado Cliente";
            $tabla = "Cliente";
            $llave = $cliente;
            $cliente_log = $cliente;
            $enlace = "Clientes|Consultar|" . $cliente;
            $observaciones = "Estado: " . $est . "\n---\nSe actualiza estado del Cliente de forma automática\n \nObservación automática.";
            updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
        } else {
            return "No se pudo Actualizar el Cliente.";
        }
    } catch (Exception $e) {
        return 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
    }
}

function valDeudaUpdate($validacion, $dia, $estado)
{
    $ci = & get_instance();
    $ci->load->model('Pedidos_model');
    
    //Datos Auditoría
    $user = $ci->session->userdata('Usuario');
    $fecha = date("Y-m-d H:i:s");
    $fechaValidacion = date("Y-m-d");

    $data = array(
        "FechaValidacion" => $fechaValidacion,
        "Dias" => $dia,
        "Estado" => $estado,
        "Observaciones" => "Validación de Deuda y Cambio de Estado\nNuevo Estado: " . $estado . "\n\nObservación Automática.",
        "UsuarioModificacion" => $user,
        "FechaModificacion" => $fecha
    );
    try {
        $ci->Pedidos_model->updateValDeuda($validacion, $data);
    } catch (Exception $e) {
        return 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
    }
}

function valDeudaSave($pedido, $cliente, $dia, $estado)
{
    $ci = & get_instance();
    $ci->load->model('Pedidos_model');
     
    //Datos Auditoría
    $user = $ci->session->userdata('Usuario');
    $fecha = date("Y-m-d H:i:s");
    $fechaValidacion = date("Y-m-d");

    $data = array(
        "Pedido" => $pedido,
        "Cliente" => $cliente,
        "FechaValidacion" => $fechaValidacion,
        "Dias" => $dia,
        "Estado" => $estado,
        "Observaciones" => "Validación de Deuda y Cambio de Estado\nNuevo Estado: " . $estado . "\n\nObservación Automática.",
        "UsuarioCreacion" => $user,
        "FechaCreacion" => $fecha
    );
    try {
        $ci->Pedidos_model->saveValDeuda($data);
    } catch (Exception $e) {
        return 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
    }
}

function valPagosProgramadosAll()
{
    $ci = & get_instance();
    $ci->load->model('Cobradores_model');
 
    $fecha = date("Y-m-d");
    $fechaI = "2018-01-01";
    $fechaF = date("Y-m-d", strtotime($fecha . "- 15 days"));
    
    $dataProgramados = $ci->Cobradores_model->obtenerDevolucionLlamadasMotivoFechaPro($fechaI, $fechaF);
    if (isset($dataProgramados) && $dataProgramados != false) {
        foreach ($dataProgramados as $item) {
            if ($item["Devolucion"] == 0 && $item["Motivo"] != 900) {
                // Datos Auditoría
                $user = $ci->session->userdata('Usuario');
                $fecha = date("Y-m-d H:i:s");
                $observacion = "Se cancela devolución de llamada al Pedido: " . $item["Pedido"] . "\nObservación Automática";
            
                $dataDevolucionLlamada = array(
                    "Motivo" => 900,
                    "Observaciones" => $observacion,
                    "UsuarioModificacion" => $user,
                    "FechaModificacion" => $fecha
                );

                // var_dump($dataDevolucionLlamada);
                // echo "<br><br>";
    
                $dataOriginal = $item;
                $dataNew = compararCambiosLog($dataOriginal, $dataDevolucionLlamada);
                $ci->Cobradores_model->updateDevolucionLlamada($item["Codigo"], $dataNew);
                $modulo = "Cancelación Devolución Llamada";
                $accion = "Cancelación Devolución Llamada del Pedido '" . $item["Pedido"] ."'";
                $tabla = "DevolucionLlamadas";
                $llave = $item["Codigo"];
                $cliente_log = $item["Cliente"];
                $enlace = null;
                $observaciones = $observacion;
                updateLog($modulo, $accion, $tabla, $llave, $cliente_log, $enlace, $dataOriginal, $dataNew, $observaciones);
 
                //ResetLlamada($item["Cliente"], $item["Pedido"]);
            }
        }
    }
    
    return 10;
}

function ResetLlamada($cliente, $pedido)
{
    $ci = & get_instance();
    $ci->load->model('Cobradores_model');
    
    $dataLlamada = array("Motivo" => 100, "FechaProgramada" => null, "Devolucion" => 0, "Observaciones" => "");
    $Llamada = $ci->Cobradores_model->obtenerLlamadasxNoDevolucion($cliente, $pedido);
    $ci->Cobradores_model->updateLlamada($Llamada[0]["Codigo"], $dataLlamada);
    $VolverLlamada = $ci->Cobradores_model->obtenerVolverLlamadasxNoDevolucion($cliente, $pedido);
    if (isset($VolverLlamada) && $VolverLlamada != false) {
        $ci->Cobradores_model->updateDevolucionLlamada($VolverLlamada[0]["Codigo"], $dataLlamada);
    }
}