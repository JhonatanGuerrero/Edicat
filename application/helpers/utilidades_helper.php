<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function LogSave($data, $modulo, $tabla, $accion, $llave) {
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
            "Codigo" => NULL,
            "Modulo" => $modulo,
            "Tabla" => $tabla,
            "Usuario" => $user,
            "Fecha" => $fecha,
            "Accion" => $accion,
            "Llave" => $llave,
            "Datos" => $var_data,
            "Observaciones" => $Obs
        );

        $sql = 'INSERT INTO `Log`(`Codigo`, `Modulo`, `Tabla`, `CodUsuario`, `Usuario`, `Fecha`, `Accion`, `Llave`, `Datos`, `Observaciones`) ' .
                'VALUES (null, "' . $dataSave["Modulo"] . '", "' . $dataSave["Tabla"] . '", "' . $Coduser . '","' . $dataSave["Usuario"] . '", "' . $dataSave["Fecha"] . '", ' .
                '"' . $dataSave["Accion"] . '", "' . $dataSave["Llave"] . '", "' . $dataSave["Datos"] . '", "' . $dataSave["Observaciones"] . '")';               
        $ci->db->query($sql);
        //echo $sql;
    }
}

function compararCambiosLog($dataAnterior, $dataNuevo) {
    $d = "";
    foreach ($dataAnterior[0] as $key => $value) {
        if (isset($dataNuevo[$key])) {
            if ($key != 'UsuarioModificacion' && $key != 'FechaModificacion') {
                $d = $d . "<br>" . $dataAnterior[0][$key] . " - " . $dataNuevo[$key];
                if ($dataAnterior[0][$key] === $dataNuevo[$key]) {
                    $d = $d . "-ok";
                    unset($dataNuevo[$key]);
                }
            }
        }
    }
    return $dataNuevo;
}

?>