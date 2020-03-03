<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="content">
    <div class="header">
        <?php //$this->load->view('Modules/notifications'); 
        ?>
        <h1 class="page-title" style="font-size: 2em;"><?= $title; ?> </h1>
    </div>
    <div class="main-content">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#pago" data-toggle="tab"><?= $subtitle; ?></a></li>
        </ul>
        <?php //print_r($Listadatos); 
        ?>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <br>
                <div id="myTabContent" class="tab-content">

                    <div class="tab-pane active in" id="pago">
                        <?php
                        $item = $Lista1[0];
                        $cantidad = intval($item["Cantidad"]);
                        $descuento = intval($item["Descuento"]) * $cantidad;
                        $ValorCuota = intval($item["ValorCuota"]) * $cantidad;
                        if ($item["Saldo"] == 0 || trim($item["Saldo"]) == "") {
                            $item["Saldo"] = $item["Valor"];
                        }
                        //print_r($item);
                        ?>
                        <div class="row">
                            <div class="col-md-6 hidden">
                                <div class="form-group">
                                    <label>Pedido</label>
                                    <input type="text" placeholder="Pedido" value="<?= $pedido; ?>" readonly style="background-color: #fff;" class="form-control" id="Pedido" name="Pedido">
                                </div>
                            </div>
                            <div class="col-md-6 hidden">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <input type="text" placeholder="Cliente" value="<?= $cliente; ?>" readonly style="background-color: #fff;" class="form-control" id="Cliente" name="Cliente">
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                    <label>Fecha Pedido</label>
                                    <input type="text" placeholder="Fecha Pedido" value="<?= date("d/m/Y", strtotime($item["FechaPedido"])); ?>" readonly style="background-color: #fff;" class="form-control" id="FechaPedido" name="FechaPedido">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor Total</label>
                                    <input type="text" placeholder="Valor Total" value="<?= money_format("%.0n", intval($item["Valor1"])); ?>" readonly style="background-color: #fff;" class="form-control" id="valorTotal1" name="valorTotal1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tarifa</label>
                                    <input type="text" placeholder="Tarifas" value="<?= $item["NomTarifa"]; ?>" readonly style="background-color: #fff;" class="form-control" id="Tarifa1" name="Tarifa1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha de Pago </label>
                                    <input type="text" placeholder="Dia de Cobro" value="<?= date("d/m/Y", strtotime($item["DiaCobro"])); ?>" readonly style="background-color: #fff;" class="form-control required" id="DiaCobro" name="DiaCobro">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número de Cuotas</label>
                                    <input type="number" placeholder="Cuotas" value="<?= $item["Cuotas"]; ?>" readonly style="background-color: #fff;" class="form-control required" id="numCuotas" name="numCuotas" onchange="tarifaManual(1)">
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

                        <fieldset>
                            <form id="form-CambioFecha">
                                <legend>Cambio Fecha Próximo Pago</legend>
                                <div class="row">
                                    <?php
                                    $idPermiso = 95;
                                    $btn = validarPermisoBoton($idPermiso);
                                    ?>
                                    <div class="col-md-6 col-md-offset-3">
                                        <div class="form-group">
                                            <label>Fecha Próximo Pago</label>
                                            <input type="text" placeholder="Fecha Próximo" <?php if (!$btn) { ?> readonly="true" <?php } ?> value="<?= date("d/m/Y", strtotime($item["DiaCobro"])); ?>" style="background-color: #fff;" class="form-control datepicker8" id="FechaPago" name="FechaPago" required>
                                        </div>
                                        <?php
                                        if ($btn) {
                                        ?>
                                            <div class="form-group pull-right">
                                                <button type="submit" id="btn-CambioFecha" name="btn-CambioFecha" class="btn btn-primary"><i class="fa fa-save"></i> Cambio de Fecha</button>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
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
        $(document).ready(function() {
            var todayDate = new Date().getDate();
            $('.datepicker8').datetimepicker({
                format: 'DD/MM/YYYY',
                locale: 'es',
                minDate: new Date(new Date().setDate(todayDate)),
                maxDate: new Date(new Date().setDate(todayDate + 60))
            });
            $('#form-CambioFecha').submit(function(e) {
                e.preventDefault();
                CambioFecha();
            });
            $('#btn-CambioFecha').click(function(e) {
                e.preventDefault();
                CambioFecha();
            });

            function CambioFecha() {
                $('#btn-CambioFecha').addClass("hidden");
                var cli_ped = $('#Pedido').val();
                var cli_cli = $('#Cliente').val();
                var cli_fec = $('#FechaPago').val();

                if (cli_ped.toString().length <= 0) {
                    $('#message').html(
                        '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />El Pedido no existe. Actualice la página.\n\
                            </div>');
                    $('#btn-CambioFecha').removeClass("hidden");
                } else {
                    if (cli_cli.toString().length <= 0) {
                        $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />El Cliente no existe. Actualice la página.\n\
                                </div>');
                        $('#btn-CambioFecha').removeClass("hidden");
                    } else {
                        if (cli_fec.toString().length <= 0) {
                            $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Debe indicar una fecha para continuar.\n\
                                    </div>');
                            $('#btn-CambioFecha').removeClass("hidden");
                        } else {
                            $('#message').html("");
                            var method = "<?= base_url(); ?>Clientes/ChangePayDate/";
                            $("body").css({
                                'cursor': 'wait'
                            })
                            $.ajax({
                                type: 'post',
                                url: method,
                                data: {
                                    cli_ped: cli_ped,
                                    cli_fec: cli_fec
                                },
                                cache: false,
                                beforeSend: function() {
                                    $('#message').html("");
                                    $('#btn-CambioFecha').html('<i class="fa fa-save"></i> Cambiando Fecha de Pago...');
                                    $('#message').html(
                                        '<div class="alert alert-success alert-dismissable fade in">\n\
                                            Cambiando Fecha de Pago...\n\
                                        </div>');
                                },
                                success: function(data) {
                                    $('#btn-CambioFecha').html('<i class="fa fa-save"></i> Cambio de Fecha');
                                    if (data == 1) {
                                        $('#message').html(
                                            '<div class="alert alert-success alert-dismissable fade in">\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>Se actualizó la Fecha del Pago.</strong>\n\
                                                </div>');
                                        setTimeout(location.href = "<?= base_url("Clientes/Admin/"); ?>", 1000);
                                    } else {
                                        $('#message').html(
                                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>Error</strong><br />' + data + '\n\
                                                </div>');
                                        $('#btn-CambioFecha').removeClass("hidden");
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
        });
    </script>