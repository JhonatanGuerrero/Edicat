<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="content">
    <div class="header">        
        <?php //$this->load->view('Modules/notifications'); ?>
        <h1 class="page-title" style="font-size: 2em;"><?= $title; ?> </h1>
    </div>            
    <div class="main-content">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#datos" data-toggle="tab">Datos Personales</a></li>
            <li><a href="#ubicacion" data-toggle="tab">Ubicación</a></li>
            <li><a href="#telefonos" data-toggle="tab">Teléfonos de Contacto</a></li>
            <li><a href="#referencias" data-toggle="tab">Referencias</a></li>
            <li><a href="#pago" data-toggle="tab">Pedido y Pago</a></li>
            <li><a href="#observaciones" data-toggle="tab">Observaciones</a></li>
        </ul>
        <?php //print_r($Listadatos); ?>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <br>
                <div id="myTabContent" class="tab-content">
                    <?php
                    $idPermiso = 92;
                    $accion = validarPermisoAcciones($idPermiso);
                    ?>
                    <div class="tab-pane active in" id="datos">
                        <div class="form-group hidden">
                            <label>Pedido</label>
                            <input type="text" readonly style="background-color: #fff;" placeholder="Pedido" value="<?= $pedido; ?>" class="form-control" id="pedido" name="pedido">
                        </div>
                        <form id="form-id">
                            <div class="form-group">
                                <label>Código Cliente</label>
                                <input type="text" readonly style="background-color: #fff;" placeholder="Codigo Cliente" value="<?= $Listadatos[0]["Codigo"]; ?>" class="form-control" id="Codigo" name="Codigo">
                            </div>
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" <?php if (!$accion) echo "readonly"; ?> minlength="5" maxlength="50" style="background-color: #fff;" placeholder="Nombre" value="<?= $Listadatos[0]["Nombre"]; ?>" class="form-control" id="Nombre" name="Nombre">
                            </div>
                            <div class="form-group">
                                <label>Tipo de Documento</label>                                
                                <input type="text" readonly style="background-color: #fff;" placeholder="Tipo Documento" value="<?= $Lista1[0]['Nombre']; ?>" class="form-control required" id="TipoDocumento" name="TipoDocumento">
                            </div>
                            <div class="form-group">
                                <label>Documento</label>
                                <input type="number" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Documento" value="<?= $Listadatos[0]["Documento"]; ?>" class="form-control number" id="Documento" name="Documento">
                            </div> 
                            <div class="form-group pull-right <?php if (!$accion) echo "hidden"; ?> ">
                                <button type="submit" id="btn-id" name="btn-id" class="btn btn-primary"><i class="fa fa-save"></i> Actualizar Datos</button>
                            </div>
                        </form>
                    </div>
                    <?php
                    $idPermiso = 93;
                    $accion = validarPermisoAcciones($idPermiso);
                    ?>
                    <div class="tab-pane fade" id="ubicacion">
                        <form id="form-ubicacion">
                            <div class="form-group">
                                <label>Dirección *</label>
                                <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Direccion" value="<?= $Listadatos[0]["Dir"]; ?>"  minlength="8" maxlength="150" class="form-control required" id="Direccion" name="Direccion">
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Etapa</label>
                                        <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Etapa" value="<?= $Listadatos[0]["Etapa"]; ?>" class="form-control" maxlength="10" id="Etapa" name="Etapa">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Torre</label>
                                        <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Torre" value="<?= $Listadatos[0]["Torre"]; ?>" class="form-control" maxlength="10" id="Torre" name="Torre">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Apartamento</label>
                                        <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Apartamento" value="<?= $Listadatos[0]["Apartamento"]; ?>" class="form-control" maxlength="10" id="Apartamento" name="Apartamento">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Manzana</label>
                                        <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Manzana" value="<?= $Listadatos[0]["Manzana"]; ?>" class="form-control" id="Manzana" maxlength="10" name="Manzana">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Interior</label>
                                        <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Interior" value="<?= $Listadatos[0]["Interior"]; ?>" class="form-control" maxlength="10" id="Interior" name="Interior">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Casa</label>
                                        <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Casa" value="<?= $Listadatos[0]["Casa"]; ?>" class="form-control" maxlength="10" id="Casa" name="Casa">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Barrio *</label>
                                        <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Barrio" value="<?= $Listadatos[0]["Barrio"]; ?>" class="form-control required" maxlength="30" id="Barrio" name="Barrio">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tipo de Vivienda *</label>
                                        <select name="TipoVivienda" <?php if (!$accion) echo "disabled = 'true'"; ?> style="background-color: #fff;" id="TipoVivienda" class="form-control required">
                                            <?php
                                            foreach ($Lista2 as $item) {
                                                if ($item['Codigo'] == $Listadatos[0]["TipoVivienda"]) {
                                                    echo '<option selected="selected" value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>                                    
                                </div>
                                <div class="form-group pull-right <?php if (!$accion) echo "hidden"; ?> ">
                                    <button type="submit" id="btn-ubicacion" name="btn-ubicacion" class="btn btn-primary"><i class="fa fa-save"></i> Actualizar Dirección</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="telefonos">

                        <form id="form-telefonos">
                            <div class="form-group">
                                <label>Teléfono 1 *</label>
                                <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Teléfono 1" value="<?= $Listadatos[0]["Telefono1"]; ?>" class="form-control required number" minlength="7" maxlength="10" id="Telefono1" name="Telefono1">
                            </div>
                            <div class="form-group">
                                <label>Teléfono 2</label>
                                <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Teléfono 2" value="<?= $Listadatos[0]["Telefono2"]; ?>" class="form-control" minlength="7" maxlength="10" id="Telefono2" name="Telefono2">
                            </div>                                   
                            <div class="form-group">
                                <label>Teléfono 3</label>
                                <input type="text" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" placeholder="Teléfono 3" value="<?= $Listadatos[0]["Telefono3"]; ?>" class="form-control" minlength="7" maxlength="10" id="Telefono3" name="Telefono3">
                            </div>
                            <div class="form-group pull-right <?php if (!$accion) echo "hidden"; ?> ">
                                <button type="submit" id="btn-telefonos" name="btn-telefonos" class="btn btn-primary"><i class="fa fa-save"></i> Actualizar Teléfonos</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="referencias">

                        <form id="form-referencias">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group hidden">
                                        <label>Codigo</label>
                                        <input type="text" value="<?php
                                        if (isset($Lista4["cod1"])) {
                                            echo $Lista4["cod1"];
                                        };
                                        ?>" class="form-control" id="CodigoRef1" name="CodigoRef1">
                                    </div>
                                    <div class="form-group">
                                        <label>Nombre Referencia *</label>
                                        <input type="text" placeholder="Nombre" value="<?php
                                        if (isset($Lista4["nom1"])) {
                                            echo $Lista4["nom1"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control required" minlength="5" maxlength="50" id="NombreRef1" name="NombreRef1">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Teléfono Referencia *</label>
                                        <input type="text" placeholder="Teléfono" value="<?php
                                        if (isset($Lista4["tel1"])) {
                                            echo $Lista4["tel1"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control required" minlength="7" maxlength="30" id="TelefonoRef1" name="TelefonoRef1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Parentesco Referencia *</label>
                                        <input type="text" placeholder="Parentesco" value="<?php
                                        if (isset($Lista4["par1"])) {
                                            echo $Lista4["par1"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control required" minlength="3" maxlength="50" id="ParentescoRef1" name="ParentescoRef1">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group hidden">
                                        <label>Codigo</label>
                                        <input type="text" value="<?php
                                        if (isset($Lista4["cod2"])) {
                                            echo $Lista4["cod2"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control" id="CodigoRef2" name="CodigoRef2">
                                    </div>
                                    <div class="form-group">
                                        <label>Nombre Referencia *</label>
                                        <input type="text" placeholder="Nombre" value="<?php
                                        if (isset($Lista4["nom2"])) {
                                            echo $Lista4["nom2"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control required" minlength="5" maxlength="50" id="NombreRef2" name="NombreRef2">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Teléfono Referencia *</label>
                                        <input type="text" placeholder="Teléfono" value="<?php
                                        if (isset($Lista4["tel2"])) {
                                            echo $Lista4["tel2"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control required" minlength="7" maxlength="30" id="TelefonoRef2" name="TelefonoRef2">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Parentesco Referencia *</label>
                                        <input type="text" placeholder="Parentesco" value="<?php
                                        if (isset($Lista4["par2"])) {
                                            echo $Lista4["par2"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control required" minlength="3" maxlength="10" id="ParentescoRef2" name="ParentescoRef2">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group hidden">
                                        <label>Codigo</label>
                                        <input type="text" value="<?php
                                        if (isset($Lista4["cod3"])) {
                                            echo $Lista4["cod3"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control" id="CodigoRef3" name="CodigoRef3">
                                    </div>
                                    <div class="form-group">
                                        <label>Nombre Referencia</label>
                                        <input type="text" placeholder="Nombre" value="<?php
                                        if (isset($Lista4["nom3"])) {
                                            echo $Lista4["nom3"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control" minlength="5" maxlength="50" id="NombreRef3" name="NombreRef3">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Teléfono Referencia</label>
                                        <input type="text" placeholder="Teléfono" value="<?php
                                        if (isset($Lista4["tel3"])) {
                                            echo $Lista4["tel3"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control" minlength="7" maxlength="30" id="TelefonoRef3" name="TelefonoRef3">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Parentesco Referencia</label>
                                        <input type="text" placeholder="Parentesco" value="<?php
                                        if (isset($Lista4["par3"])) {
                                            echo $Lista4["par3"];
                                        };
                                        ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control" minlength="3" maxlength="10" id="ParentescoRef3" name="ParentescoRef3">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group pull-right <?php if (!$accion) echo "hidden"; ?> ">
                                <button type="submit" id="btn-referencias" name="btn-referencias" class="btn btn-primary"><i class="fa fa-save"></i> Actualizar Referencias</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="pago">
                        <?php
                        $item = $Lista3[0];
                        $cantidad = intval($item["Cantidad"]);
                        $descuento = intval($item["Descuento"]) * $cantidad;
                        $ValorCuota = intval($item["ValorCuota"]) * $cantidad;
                        if ($item["Saldo"] == 0 || trim($item["Saldo"]) == "") {
                            $item["Saldo"] = $item["Valor"];
                        }
                        //print_r($item);
                        ?>
                        <div class="row">
                            <div class="col-md-4 hidden">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" placeholder="Cantidad" value="<?= $cantidad; ?>" readonly style="background-color: #fff;" class="form-control" id="Cantidad1" name="Cantidad1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Producto</label>
                                    <input type="text" placeholder="Producto" value="<?= $item["NomPro"]; ?>" readonly style="background-color: #fff;" class="form-control" id="Cantidad1" name="Producto1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor Total</label>
                                    <input type="text" placeholder="Valor Total" value="<?= money_format("%.0n", intval($item["Valor1"])); ?>" readonly style="background-color: #fff;" class="form-control" id="valorTotal1" name="valorTotal1">
                                </div>
                            </div>   
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <label>Fecha Pedido</label>
                                    <input type="text" placeholder="Fecha Pedido" value="<?= date("d/m/Y", strtotime($item["FechaPedido"])); ?>" readonly style="background-color: #fff;" class="form-control" id="FechaPedido" name="FechaPedido">
                                </div>
                            </div>
                        </div>
                        <div class="row">                                                                 
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <label>Tarifa</label>
                                    <input type="text" placeholder="Tarifas" value="<?= $item["NomTarifa"]; ?>" readonly style="background-color: #fff;" class="form-control" id="Tarifa1" name="Tarifa1">
                                </div>    
                            </div>
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <label>Descuento</label>
                                    <input type="text" placeholder="Valor del Descuento" value="<?= money_format("%.0n", $descuento); ?>" readonly style="background-color: #fff;" class="form-control required" id="valorDescuento" name="valorDescuento">
                                </div>
                            </div>
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <label>Fecha de Pago</label>
                                    <input type="text" placeholder="Fecha Pago" value="<?= date("d/m/Y", strtotime($item["DiaCobro"])); ?>" readonly style="background-color: #fff;" class="form-control" id="DiaCobro" name="DiaCobro">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número de Cuotas</label>
                                    <input type="number" placeholder="Cuotas" value="<?= $item["Cuotas"]; ?>" readonly style="background-color: #fff;" class="form-control required" id="numCuotas" name="numCuotas"  onchange="tarifaManual(1)">
                                </div>
                            </div>
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <label>Valor de la Cuota</label>
                                    <input type="text" min="0" placeholder="Valor de la Cuota" value="<?= money_format("%.0n", $ValorCuota); ?>" readonly style="background-color: #fff;" class="form-control required" id="valorCuota" name="valorCuota" onchange="tarifaManual(2)">
                                </div>
                            </div>
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <label>Saldo</label>
                                    <input type="text" placeholder="Saldo" value="<?= money_format("%.0n", $item["Saldo"]); ?>" readonly style="background-color: #fff;" class="form-control" id="Saldo" name="Saldo">
                                </div>
                            </div>
                        </div>
                        <?php
                        $idPermiso = 94;
                        $btn = validarPermisoBoton($idPermiso);
                        if ($btn) {
                            ?>
                            <div class="row">                            
                                <div class="col-md-12">                        
                                    <div class="form-group pull-right">
                                        <a href="<?= base_url("Clientes/Productos/" . $pedido . "/"); ?>" class="btn btn-primary"><i class="fa fa-shopping-basket"></i> Lista de Productos</a>                                    
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="tab-pane fade" id="observaciones">

                        <form id="form-observaciones">
                            <div class="row">
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label>Vendedor</label>
                                        <input type="text" placeholder="Vendedor" value="<?= $Lista5[0]["Nombre"]; ?>" readonly style="background-color: #fff;" class="form-control" name="Vendedor1" id="Vendedor1" >                                    
                                    </div>    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"> 
                                    <div class="form-group">
                                        <label>Iglesia de Evento</label>
                                        <input type="text" placeholder="Iglesia" value="<?= $Lista6[0]["Iglesia"]; ?>" readonly style="background-color: #fff;" class="form-control" name="Iglesia" id="Iglesia" >
                                    </div>    
                                </div>
                                <div class="col-md-3"> 
                                    <div class="form-group">
                                        <label>Barrio de Evento</label>
                                        <input type="text" placeholder="Barrio" value="<?= $Lista6[0]["Barrio"]; ?>" readonly style="background-color: #fff;" class="form-control" name="Barrio" id="Barrio" >
                                    </div>    
                                </div>
                                <div class="col-md-3"> 
                                    <div class="form-group">
                                        <label>Fecha de Evento</label>
                                        <input type="text" placeholder="Fecha" value="<?= date("d/m/Y", strtotime($Lista6[0]["Fecha"])); ?>" readonly style="background-color: #fff;" class="form-control" name="Fecha" id="Fecha" >
                                    </div>    
                                </div>
                                <div class="col-md-3"> 
                                    <div class="form-group">
                                        <label>Ubicación Física</label>
                                        <input type="text" placeholder="Ubicación Física" value="<?= $PaginaFisica; ?>" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control" name="Pagina" id="Pagina" >
                                    </div>    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label>Notas/Observaciones</label>
                                        <textarea rows="6" disabled class="form-control" name="Observaciones" id="Observaciones" style="resize: none;"><?= trim(str_replace("\n", "\n", $Listadatos[0]['Observaciones'])); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label>Nuevas Notas/Observaciones</label>
                                        <textarea rows="6" <?php if (!$accion) echo "readonly"; ?> style="background-color: #fff;" class="form-control" name="nuevasObservaciones" id="nuevasObservaciones" style="resize: none;"></textarea>
                                    </div>
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label>Usuario Creación</label>
                                        <input type="text" disabled placeholder="Usuario Creador" value="<?= $Listadatos[0]['UsuarioCreacion']; ?>" <?php if (!$accion) echo "readonly"; ?> class="form-control" name="UsuarioCreacion" id="UsuarioCreacion" >
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label>Fecha Creación</label>
                                        <input type="text" disabled placeholder="Fecha Creación" value="<?= $Listadatos[0]['FechaCreacion']; ?>" <?php if (!$accion) echo "readonly"; ?> class="form-control" name="FechaCreacion" id="FechaCreacion" >
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-12">                        
                                <div class="form-group pull-right <?php if (!$accion) echo "hidden"; ?> ">
                                    <button type="submit" id="btn-observaciones" name="btn-observaciones" class="btn btn-primary"><i class="fa fa-save"></i> Actualizar Observaciones</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="message">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <script>
        $(document).ready(function () {
            $('#form-id').submit(function (e) {
                e.preventDefault();
                ActualizarDatosPer();
            });
            $('#btn-id').click(function (e) {
                e.preventDefault();
                ActualizarDatosPer();
            });
            $('#form-ubicacion').submit(function (e) {
                e.preventDefault();
                ActualizarDir();
            });
            $('#btn-ubicacion').click(function (e) {
                e.preventDefault();
                ActualizarDir();
            });
            var form = $("#form-ubicacion");
            form.validate({
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                },
                rules: {
                    Direccion: {
                        required: false
                    }
                },
                messages: {
                    Direccion: {
                        required: "La dirección es requerida"
                    }
                }

            });

            function ActualizarDatosPer() {
                var Codigo = $('#Codigo').val();
                var Nombre = $('#Nombre').val();
                var TipoDocumento = $('#TipoDocumento').val();
                var Documento = $('#Documento').val();

                if (Codigo.toString().length <= 0) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />No existe Cliente\n\
                            </div>');
                } else {
                    if (Nombre.toString().length <= 0) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />Debe indicar un Nombre\n\
                                </div>');
                    } else {
                        if (TipoDocumento.toString().length <= 0) {
                            $('#message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Debe seleccionar un Tipo de Documento\n\
                                    </div>');
                        } else {
                            if (Documento.toString().length <= 5) {
                                $('#message').html(
                                        '<div class="alert alert-danger alert-dismissable fade in">\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />Debe indicar un documento válido\n\
                                        </div>');
                            } else {
                                $('#message').html("");
                                var method = "<?= base_url(); ?>Clientes/UpdateClientDataP/";
                                $("body").css({
                                    'cursor': 'wait'
                                })
                                $.ajax({
                                    type: 'post',
                                    url: method,
                                    data: {
                                        Codigo: Codigo, Nombre: Nombre, Documento: Documento
                                    },
                                    cache: false,
                                    beforeSend: function () {
                                        $('#message').html("");
                                        $('#btn-id').html('<i class="fa fa-save"></i> Actualizando...');
                                    },
                                    success: function (data) {
                                        $('#btn-id').html('<i class="fa fa-save"></i> Actualizar Datos');
                                        if (data == 1) {
                                            $('#message').html(
                                                    '<div class="alert alert-success alert-dismissable fade in">\n\
                                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                            <strong>Se actualizó la información del cliente <b>' + Nombre + '</b></strong>\n\
                                                        </div>');
                                            location.reload();
                                        } else {
                                            $('#message').html(
                                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                            <strong>Error</strong><br />' + data + '\n\
                                                        </div>');
                                        }
                                    }

                                });
                                $("body").css({
                                    'cursor': 'Default'
                                })

                                return false;
                            }
                        }
                    }
                }
            }

            function ActualizarDir() {
                var cli_cod = $('#Codigo').val();
                var cli_nom = $('#Nombre').val();
                var cli_dir = $('#Direccion').val();
                var cli_eta = $('#Etapa').val();
                var cli_tor = $('#Torre').val();
                var cli_apto = $('#Apartamento').val();
                var cli_manz = $('#Manzana').val();
                var cli_int = $('#Interior').val();
                var cli_casa = $('#Casa').val();
                var cli_bar = $('#Barrio').val();
                var cli_tipviv = $('#TipoVivienda option:selected').val();
                if (cli_cod.toString().length <= 0) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />No existe Cliente\n\
                            </div>');
                } else {
                    if (cli_dir.toString().length <= 0) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />Indique una Dirección válida\n\
                                </div>');
                    } else {
                        if (cli_bar.toString().length <= 0) {
                            $('#message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Indique un Barrio válido\n\
                                    </div>');
                        } else {
                            if (cli_tipviv.toString().length <= 0) {
                                $('#message').html(
                                        '<div class="alert alert-danger alert-dismissable fade in">\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />Seleccione el tipo de Vivienda\n\
                                        </div>');
                            } else {
                                $('#message').html("");
                                var method = "<?= base_url(); ?>Clientes/UpdateClientDir/";
                                $("body").css({
                                    'cursor': 'wait'
                                })
                                $.ajax({
                                    type: 'post',
                                    url: method,
                                    data: {cli_cod: cli_cod, cli_dir: cli_dir, cli_eta: cli_eta, cli_tor: cli_tor, cli_apto: cli_apto, cli_manz: cli_manz, cli_int: cli_int, cli_casa: cli_casa, cli_bar: cli_bar, cli_tipviv: cli_tipviv},
                                    cache: false,
                                    beforeSend: function () {
                                        $('#message').html("");
                                        $('#btn-ubicacion').html('<i class="fa fa-save"></i> Actualizando...');
                                    },
                                    success: function (data) {
                                        $('#btn-ubicacion').html('<i class="fa fa-save"></i> Actualizar Dirección');
                                        if (data == 1) {
                                            $('#message').html(
                                                    '<div class="alert alert-success alert-dismissable fade in">\n\
                                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                        <strong>Se actualizó la información del cliente <b>' + cli_nom + '</b></strong>\n\
                                                    </div>');
                                            location.reload();
                                        } else {
                                            $('#message').html(
                                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                        <strong>Error</strong><br />' + data + '\n\
                                                    </div>');
                                        }
                                    }

                                });
                                $("body").css({
                                    'cursor': 'Default'
                                })

                                return false;
                            }
                        }
                    }
                }
            }

            $('#form-telefonos').submit(function (e) {
                e.preventDefault();
                ActualizarTel();
            });
            $('#btn-telefonos').click(function (e) {
                e.preventDefault();
                ActualizarTel();
            });
            function ActualizarTel() {
                var cli_cod = $('#Codigo').val();
                var cli_nom = $('#Nombre').val();
                var cli_tel1 = $('#Telefono1').val();
                var cli_tel2 = $('#Telefono2').val();
                var cli_tel3 = $('#Telefono3').val();
                if (cli_cod.toString().length <= 0) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />No existe Cliente\n\
                            </div>');
                } else {
                    if ((cli_tel1.toString().length <= 0) && (cli_tel2.toString().length <= 0) && (cli_tel3.toString().length <= 0)) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />Indique al menos un número de Teléfono\n\
                                </div>');
                    } else {
                        $('#message').html("");
                        var method = "<?= base_url(); ?>Clientes/UpdateClientTel/";
                        $("body").css({
                            'cursor': 'wait'
                        })
                        $.ajax({
                            type: 'post',
                            url: method,
                            data: {cli_cod: cli_cod, cli_nom: cli_nom, cli_tel1: cli_tel1, cli_tel2: cli_tel2, cli_tel3: cli_tel3},
                            cache: false,
                            beforeSend: function () {
                                $('#message').html("");
                                $('#btn-ubicacion').html('<i class="fa fa-save"></i> Actualizando...');
                            },
                            success: function (data) {
                                $('#btn-ubicacion').html('<i class="fa fa-save"></i> Actualizar Teléfonos');
                                if (data == 1) {
                                    $('#message').html(
                                            '<div class="alert alert-success alert-dismissable fade in">\n\
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                <strong>Se actualizó la información del cliente <b>' + cli_nom + '</b></strong>\n\
                                            </div>');
                                    location.reload();
                                } else {
                                    $('#message').html(
                                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                <strong>Error</strong><br />' + data + '\n\
                                            </div>');
                                }
                            }

                        });
                        $("body").css({
                            'cursor': 'Default'
                        })

                        return false;
                    }
                }
            }

            $('#form-referencias').submit(function (e) {
                e.preventDefault();
                ActualizarRef();
            });
            $('#btn-referencias').click(function (e) {
                e.preventDefault();
                ActualizarRef();
            });
            function ActualizarRef() {
                var cli_cod = $('#Codigo').val();
                var cli_nom = $('#Nombre').val();
                var cli_codrf1 = $('#CodigoRef1').val();
                var cli_nomrf1 = $('#NombreRef1').val();
                var cli_telrf1 = $('#TelefonoRef1').val();
                var cli_paren1 = $('#ParentescoRef1').val();
                var cli_codrf2 = $('#CodigoRef2').val();
                var cli_nomrf2 = $('#NombreRef2').val();
                var cli_telrf2 = $('#TelefonoRef2').val();
                var cli_paren2 = $('#ParentescoRef2').val();
                var cli_codrf3 = $('#CodigoRef3').val();
                var cli_nomrf3 = $('#NombreRef3').val();
                var cli_telrf3 = $('#TelefonoRef3').val();
                var cli_paren3 = $('#ParentescoRef3').val();
                if (cli_cod.toString().length <= 0) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />No existe Cliente\n\
                            </div>');
                } else {
                    if ((cli_nomrf1.toString().length <= 0) || (cli_telrf1.toString().length <= 0) || (cli_paren1.toString().length <= 0)) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />Debe poner los Datos de la Referecia 1\n\
                                </div>');
                    } else {
                        if ((cli_nomrf2.toString().length <= 0) || (cli_telrf2.toString().length <= 0) || (cli_paren2.toString().length <= 0)) {
                            $('#message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Debe poner los Datos de la Referecia 2\n\
                                    </div>');
                        } else {
                            $('#message').html("");
                            var method = "<?= base_url(); ?>Clientes/UpdateClientRef/";
                            $("body").css({
                                'cursor': 'wait'
                            })
                            $.ajax({
                                type: 'post',
                                url: method,
                                data: {
                                    cli_cod: cli_cod, cli_nom: cli_nom,
                                    cli_codrf1: cli_codrf1, cli_nomrf1: cli_nomrf1, cli_telrf1: cli_telrf1, cli_paren1: cli_paren1,
                                    cli_codrf2: cli_codrf2, cli_nomrf2: cli_nomrf2, cli_telrf2: cli_telrf2, cli_paren2: cli_paren2,
                                    cli_codrf3: cli_codrf3, cli_nomrf3: cli_nomrf3, cli_telrf3: cli_telrf3, cli_paren3: cli_paren3
                                },
                                cache: false,
                                beforeSend: function () {
                                    $('#message').html("");
                                    $('#btn-ubicacion').html('<i class="fa fa-save"></i> Actualizando...');
                                },
                                success: function (data) {
                                    $('#btn-ubicacion').html('<i class="fa fa-save"></i> Actualizar Referencias');
                                    if (data == 1) {
                                        $('#message').html(
                                                '<div class="alert alert-success alert-dismissable fade in">\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>Se actualizó la información del cliente <b>' + cli_nom + '</b></strong>\n\
                                                </div>');
                                        location.reload();
                                    } else {
                                        $('#message').html(
                                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>Error</strong><br />' + data + '\n\
                                                </div>');
                                    }
                                }

                            });
                            $("body").css({
                                'cursor': 'Default'
                            })

                            return false;

                        }
                    }

                }
            }

            $('#form-observaciones').submit(function (e) {
                e.preventDefault();
                ActualizarObs();
            });
            $('#btn-observaciones').click(function (e) {
                e.preventDefault();
                ActualizarObs();
            });
            function ActualizarObs() {
                var cli_cod = $('#Codigo').val();
                var cli_ped = $('#pedido').val();
                var cli_nom = $('#Nombre').val();
                var cli_pag = $('#Pagina').val();
                var cli_obs = $('#nuevasObservaciones').val().trim();

                if (cli_cod.toString().length <= 0) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />No existe Cliente\n\
                            </div>');
                } else {
                    if (cli_pag.toString().length <= 0) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />Debe indicar una ubicación física\n\
                                </div>');
                    } else {
                        $('#message').html("");
                        var method = "<?= base_url(); ?>Clientes/UpdateClientObs/";
                        $("body").css({
                            'cursor': 'wait'
                        })
                        $.ajax({
                            type: 'post',
                            url: method,
                            data: {
                                cli_cod: cli_cod, cli_ped: cli_ped, cli_pag: cli_pag, cli_obs: cli_obs
                            },
                            cache: false,
                            beforeSend: function () {
                                $('#message').html("");
                                $('#btn-ubicacion').html('<i class="fa fa-save"></i> Actualizando...');
                            },
                            success: function (data) {
                                $('#btn-ubicacion').html('<i class="fa fa-save"></i> Actualizar Observaciones');
                                if (data == 1) {
                                    $('#message').html(
                                            '<div class="alert alert-success alert-dismissable fade in">\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>Se actualizó la información del cliente <b>' + cli_nom + '</b></strong>\n\
                                                </div>');
                                    location.reload();
                                } else {
                                    $('#message').html(
                                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>Error</strong><br />' + data + '\n\
                                                </div>');
                                }
                            }

                        });
                        $("body").css({
                            'cursor': 'Default'
                        })

                        return false;
                    }
                }
            }
        });
    </script>