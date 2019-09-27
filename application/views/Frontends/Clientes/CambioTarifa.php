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
            <li class="active"><a href="#pago" data-toggle="tab"><?= $subtitle; ?></a></li>
        </ul>
        <?php //print_r($Listadatos); ?>
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
                            <div class="col-md-12 hidden">
                                <div class="form-group">
                                    <label>Pedido</label>
                                    <input type="text" placeholder="Pedido" value="<?= $pedido; ?>" readonly
                                        style="background-color: #fff;" class="form-control" id="Pedido" name="Pedido">
                                </div>
                            </div>
                            <div class="col-md-6 hidden">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <input type="text" placeholder="Cliente" value="<?= $cliente; ?>" readonly
                                        style="background-color: #fff;" class="form-control" id="Cliente"
                                        name="Cliente">
                                </div>
                            </div>
                            <div class="col-md-6 hidden">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" placeholder="Nombre" value="<?= $subtitle; ?>" readonly
                                        style="background-color: #fff;" class="form-control" id="Nombre" name="Nombre">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" placeholder="Cantidad" value="<?= $cantidad; ?>" readonly
                                        style="background-color: #fff;" class="form-control" id="Cantidad1"
                                        name="Cantidad1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Producto</label>
                                    <input type="text" placeholder="Producto" value="<?= $item["NomPro"]; ?>" readonly
                                        style="background-color: #fff;" class="form-control" id="Cantidad1"
                                        name="Producto1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Pedido</label>
                                    <input type="text" placeholder="Fecha Pedido"
                                        value="<?= date("d/m/Y", strtotime($item["FechaPedido"])); ?>" readonly
                                        style="background-color: #fff;" class="form-control" id="FechaPedido"
                                        name="FechaPedido">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor Total</label>
                                    <input type="text" placeholder="Valor Total"
                                        value="<?= money_format("%.0n", intval($item["Valor1"])); ?>" readonly
                                        style="background-color: #fff;" class="form-control" id="valorTotal1"
                                        name="valorTotal1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tarifa</label>
                                    <input type="text" placeholder="Tarifas" value="<?= $item["NomTarifa"]; ?>" readonly
                                        style="background-color: #fff;" class="form-control" id="Tarifa1"
                                        name="Tarifa1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha de Pago </label>
                                    <input type="text" placeholder="Dia de Cobro"
                                        value="<?= date("d/m/Y", strtotime($item["DiaCobro"])); ?>" readonly
                                        style="background-color: #fff;" class="form-control required" id="DiaCobro"
                                        name="DiaCobro">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 hidden">
                                <div class="form-group">
                                    <label>Pedido</label>
                                    <input type="number" placeholder="Pedido" value="<?= $pedido; ?>" readonly
                                        style="background-color: #fff;" class="form-control required" id="pedido"
                                        name="pedido">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número de Cuotas</label>
                                    <input type="number" placeholder="Cuotas" value="<?= $item["Cuotas"]; ?>" readonly
                                        style="background-color: #fff;" class="form-control required" id="numCuotas1"
                                        name="numCuotas1" onchange="tarifaManual(1)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor de la Cuota</label>
                                    <input type="text" min="0" placeholder="Valor de la Cuota"
                                        value="<?= money_format("%.0n", $ValorCuota); ?>" readonly
                                        style="background-color: #fff;" class="form-control required" id="valorCuota1"
                                        name="valorCuota1" onchange="tarifaManual(2)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Saldo</label>
                                    <input type="text" placeholder="Saldo"
                                        value="<?= money_format("%.0n", $item["Saldo"]); ?>" readonly
                                        style="background-color: #fff;" class="form-control" id="Saldo" name="Saldo">
                                </div>
                            </div>
                        </div>

                        <fieldset>
                            <form id="form-CambioFecha">
                                <legend><?= $title; ?></legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nueva Tarifa</label>
                                            <label>Tarifa *</label>
                                            <select name="Tarifa" id="Tarifa" class="form-control required"
                                                onchange="datosTarifa(this.value, <?= $Tarifa; ?>, <?= $Pago; ?>)">
                                                <?php
                                                foreach ($Lista2 as $item):
                                                    echo '<option ' . ($Tarifa == $item['Codigo'] ? 'selected="selected"' : '') . 'value = "' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nuevo Total *</label>
                                            <input type="text" placeholder="Nuevo Total" value="" readonly
                                                style="background-color: #fff;" class="form-control" id="TotalPagar"
                                                name="TotalPagar">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-md-4">
                                        <div class="form-group">
                                            <label>Nuevo número de Cuotas *</label>
                                            <input type="number" placeholder="Nuevo número de Cuotas" value="" readonly
                                                style="background-color: #fff;" class="form-control required"
                                                id="numCuotas" name="numCuotas">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nuevo Valor de la Cuota *</label>
                                            <input type="number" min="0" placeholder="Nuevo Valor de la Cuota" value=""
                                                readonly style="background-color: #fff;" class="form-control required"
                                                id="valorCuota" name="valorCuota">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nuevo Saldo *</label>
                                            <input type="number" min="0" placeholder="Nuevo Saldo" value="" readonly
                                                style="background-color: #fff;" class="form-control required"
                                                id="valorSaldo" name="valorSaldo">
                                        </div>
                                    </div>
                                    <?php
                                    $idPermiso = 96;
                                    $btn = validarPermisoBoton($idPermiso);
                                    if ($btn) {
                                        ?>
                                    <div class="col-md-12">
                                        <div class="form-group pull-right">
                                            <button type="submit" id="btn-CambioTarifa" name="btn-CambioTarifa"
                                                class="btn btn-primary"><i class="fa fa-save"></i> Cambio de
                                                Tarifa</button>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
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
        $('#form-CambioTarifa').submit(function(e) {
            e.preventDefault();
            CambioTarifa();
        });
        $('#btn-CambioTarifa').click(function(e) {
            e.preventDefault();
            CambioTarifa();
        });

        function CambioTarifa() {
            var tar_ped = $('#Pedido').val();
            var tar_tar = $('#Tarifa').val();
            var tar_nom = $('#Tarifa option:selected').text();
            var tar_tot = $('#TotalPagar').val();
            var tar_num = $('#numCuotas').val();
            var tar_cuo = $('#valorCuota').val();
            var tar_sal = $('#valorSaldo').val();
            var cli_cli = $('#Cliente').val();
            var cli_nom = $('#Nombre').val();

            if (tar_tot.toString().length <= 0) {
                $('#btn-CambioTarifa').attr('disabled', 'disabled');
                $('#message').html(
                    '<div class="alert alert-success alert-dismissable fade in" >\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                No se cambió la tarifa. Direccionando...\n\
                            </div>');
                setTimeout(location.href = "<?= base_url("Clientes/Admin/"); ?>", 1000);
            } else {
                $('#message').html("");
                var method = "<?= base_url(); ?>Clientes/ChangeRate/";
                $("body").css({
                    'cursor': 'wait'
                })
                $.ajax({
                    type: 'post',
                    url: method,
                    data: {
                        tar_ped: tar_ped,
                        tar_tar: tar_tar,
                        tar_nom: tar_nom,
                        tar_tot: tar_tot,
                        tar_num: tar_num,
                        tar_cuo: tar_cuo,
                        tar_sal: tar_sal,
                        cli_cli: cli_cli,
                        cli_nom: cli_nom
                    },
                    cache: false,
                    beforeSend: function() {
                        $('#message').html("");
                        $('#btn-CambioTarifa').attr('disabled', 'disabled');
                        $('#btn-CambioTarifa').html(
                            '<i class="fa fa-save"></i> Cambiando Tarifa...');
                    },
                    success: function(data) {
                        $('#btn-CambioTarifa').prop("disabled", false);
                        $('#btn-CambioTarifa').html('<i class="fa fa-save"></i> Cambio de Tarifa');
                        if (data == 1) {
                            $('#message').html(
                                '<div class="alert alert-success alert-dismissable fade in">\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Se actualizó la Tarifa.</strong>\n\
                                        </div>');
                            location.href = "<?= base_url("Clientes/Admin") . "/"; ?>";
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


    });

    function datosTarifa(event, tar, pago) {
        $('#lblErrorTotal').html("");
        var codigoTarifa = event;
        if (codigoTarifa == tar) {
            $('#numCuotas').val("");
            $('#valorCuota').val("");
            $('#TotalPagar').val("");
            $('#valorSaldo').val("");
        } else {
            if (codigoTarifa == null || codigoTarifa == "" || codigoTarifa == 0) {
                $('#numCuotas').val("0");
                $('#valorCuota').val("0");
                $('#TotalPagar').val("0");
                $('#valorSaldo').val("0");
            } else {
                var method = "<?= base_url() . 'Tarifas/obtenerTarifaCod/'; ?>";
                $("body").css({
                    'cursor': 'wait'
                })
                $.ajax({
                    type: 'POST',
                    url: method,
                    data: {
                        codigo: codigoTarifa
                    },
                    dataType: 'JSON',
                    cache: false,
                    success: function(data) {
                        if (typeof data[0].Codigo === 'undefined') {
                            alert(data);
                        } else {
                            if (data[0].Cuotas == 0) {
                                $('#lblErrorTotal').html("");
                                $('#numCuotas').val("0");
                                $('#valorCuota').val("0");
                                $('#TotalPagar').val("0");
                                $('#valorSaldo').val("0");
                            } else {
                                $('#lblErrorTotal').html("");
                                $('#numCuotas').attr("readonly", true);
                                $('#valorCuota').attr("readonly", true);
                                var cantidad = parseInt($('#Cantidad1').val());
                                var total1 = parseInt($('#valorTotal1').val());

                                var numCuotas = data[0].Cuotas;
                                $('#numCuotas').val(numCuotas);
                                var descuento = parseInt(data[0].Descuento);
                                var totaldescuento = descuento * cantidad;
                                $('#valorDescuento').val(totaldescuento);
                                var valorCuota = parseInt(data[0].ValorCuota) * cantidad;
                                $('#valorCuota').val(valorCuota);
                                var valor = parseInt(data[0].Valor);
                                var totalpagar = (valor * cantidad);
                                $('#TotalPagar').val(totalpagar);
                                var saldo = totalpagar - pago;
                                $('#valorSaldo').val(saldo);

                                if ((totalpagar + totaldescuento) != total1) {
                                    $('#lblErrorTotal').html(
                                        "La tarifa no coincide: Por favor pruebe otra, de lo contrario solicite creación de Tarifa"
                                    );
                                }
                            }
                        }
                    }
                });
                $("body").css({
                    'cursor': 'default'
                })
            }
        }
    }
    </script>