<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="content">
    <div class="header">        
        <?php //$this->load->view('Modules/notifications'); ?>
        <h1 class="page-title" style="font-size: 2em;"><?= $title; ?> </h1>
    </div>            
    <div class="main-content">
        <div class="panel panel-default">
            <a href="#" class="panel-heading"><?= $subtitle; ?></a>
            <div id="page-stats-0" class="panel-collapse panel-body collapse in">
                <div class="row">
                    <?php if ($this->session->flashdata("error")): ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissable fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>Error</strong>
                                <br />
                                <?= $this->session->flashdata("error"); ?>
                            </div>
                        </div>
                    <?php endif; ?>                                               
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cliente</label>
                                <input type="text" value="<?= $ListaDatos2[0]["Nombre"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" value="<?= $ListaDatos2[0]["Dir"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                            </div>
                        </div>                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Barrio</label>
                                <input type="text" value="<?= $ListaDatos2[0]["Barrio"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Teléfonos</label>
                                <input type="text" value="<?= $ListaDatos2[0]["Telefono1"] . "  " . $ListaDatos2[0]["Telefono2"] . "  " . $ListaDatos2[0]["Telefono3"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                            </div> 
                        </div>
                    </div>
                </div>                                            
            </div>                                            
        </div>
        <?php
        foreach ($ListaDatos as $item) {
            //var_dump($item);
            $dataPago[0]["Observaciones"] = str_replace("\n", "\n", $dataPago[0]["Observaciones"]);
            ?>
            <div class="panel panel-default">
                <div id="page-stats-<?= $item["Codigo"] ?>" class="panel-collapse panel-body collapse in">
                    <div class="row">             
                        <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <input type="text" value="<?= $item["EstNombre"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Saldo Actual</label>
                                    <input type="text" value="<?= money_format("%.0n", $ListaDatos3[$item["CodPedido"]]["saldo"]); ?>" class="form-control" disabled style="background-color: #ffffff;" id="SaldoActual" name="SaldoActual">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Valor Pedido</label>
                                    <input type="text" value="<?= money_format("%.0n", $item["Valor1"]); ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha de Compra</label>
                                    <input type="text" value="<?= date("d/m/Y", strtotime($item["FechaPedido"])); ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Valor Cuota</label>
                                    <input type="text" value="<?= money_format("%.0n", $item["ValCuota"]); ?>" class="form-control" disabled style="background-color: #ffffff;" id="SaldoActual" name="SaldoActual">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cuota Actual</label>
                                    <input type="text" id="Cuota" name="Cuota" value="<?= intval($ListaDatos3[$item["CodPedido"]]["cuota"]) + 1; ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div> 
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Valor última Cuota</label>
                                    <input type="text" value="<?= money_format("%.0n", $ListaDatos3[$item["CodPedido"]]["UltimoPago"]); ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha última Cuota</label>
                                    <input type="text" value="<?php
                                    if ($ListaDatos3[$item["CodPedido"]]["fechaUltimoPago"] == 0) {
                                        echo "--";
                                    } else {
                                        echo date("d/m/Y", strtotime($ListaDatos3[$item["CodPedido"]]["fechaUltimoPago"]));
                                    }
                                    ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div>
                            <br /> 
                            <fieldset style="background-color: #fefefe; padding:5px; border: 1px solid #e3e3e3;">
                                <legend>Programar Pago</legend>
                                <form id="form-descartarPago">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Valor del Pago</label>
                                            <input type="text" value="<?= money_format("%.0n", $dataPago[0]["Cuota"]); ?>" class="form-control" max="<?= $ListaDatos3[$item["CodPedido"]]["saldo"]; ?>" id="NuevoAbono" name="NuevoAbono" disabled style="background-color: #ffffff;">
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha de Recibo:</label>
                                            <input type="text" value="<?= date("d/m/Y", strtotime($dataPago[0]["FechaProgramada"])); ?>" class="form-control" id="FechaPrograma" name="FechaPrograma" disabled style="background-color: #ffffff;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Volver a llamar:</label>
                                            <input type="text" value="" class="form-control datepicker81" id="FechaReLlamar" name="FechaReLlamar">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-6">
                                        <div class="form-group">
                                            <label>Notas/Observaciones</label>
                                            <textarea value="" rows="6" class="form-control" style="resize: none;" id="Observacion" name="Observacion" disabled><?= $dataPago[0]["Observaciones"]; ?></textarea>
                                        </div>  
                                    </div>  
                                    <div class="col-sm-12 col-lg-6">
                                        <div class="form-group">
                                            <label>Nuevas Notas/Observaciones</label>
                                            <textarea rows="6" class="form-control" name="nuevasObservaciones" id="nuevasObservaciones" style="resize: none;"></textarea>
                                        </div>
                                    </div>  
                                    <div class="col-md-12">
                                        <div class="pull-right btn-toolbar list-toolbar">
                                            <button id="btn-descartarPago" name="btn-descartarPago" class="btn btn-danger"><i class="fa fa-close"></i> Descartar Pago</button>                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="message">
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    var todayDate = new Date().getDate();
                    $('.datepicker8').datetimepicker({
                        format: 'DD/MM/YYYY',
                        locale: 'es',
                        minDate: new Date(new Date().setDate(todayDate + 1))
                    });
                    $('#FechaReLlamar').datetimepicker({
                        format: 'DD/MM/YYYY',
                        locale: 'es'
                    });
                    $('#form-descartarPago').submit(function (e) {
                        e.preventDefault();
                        DescartarPago();
                    });
                    $('#btn-descartarPago').click(function (e) {
                        e.preventDefault();
                        DescartarPago();
                    });

                    $('#nuevasObservaciones').focus(function () {
                        var abono = $('#NuevoAbono').val().replace("$", "").replace(" ", "").replace(".", "");
                        var fechaPago = $('#FechaPrograma').val();
                        var fechaRellamar = $('#FechaReLlamar').val();
                        if (abono.toString().length > 0 && fechaPago.toString().length > 0) {
                            var mon = new Intl.NumberFormat().format(abono);
                            if (fechaRellamar.toString().length > 0) {
                                var ob = "Cliente no pagó como se acordó:  $" + mon + " el día " + fechaPago + ".\nSe programa llamada para el día: " + fechaRellamar;
                            } else {
                                var ob = "Cliente no pagó como se acordó:  $" + mon + " el día " + fechaPago + ".";
                            }
                            $('#nuevasObservaciones').text(ob);
                        } else {
                            $('#nuevasObservaciones').val("");
                        }
                    });

                    function DescartarPago() {
                        var pag_cli = <?= $cliente; ?>;
                        var pag_ped = <?= $pedido; ?>;
                        var pag_pro = <?= $codigo; ?>;
                        var pag_pag = $('#NuevoAbono').val().replace("$", "").replace(" ", "").replace(".", "");
                        var pag_obs = $('#nuevasObservaciones').val();
                        var pag_sal = $('#SaldoActual').val();
                        var pag_Rellam = $('#FechaReLlamar').val();
                        var pag_cuo = $('#Cuota').val();


                        if (pag_cli.toString().length <= 0) {
                            $('#message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />No Existe Cliente\n\
                                    </div>');
                        } else {
                            if (pag_ped.toString().length <= 0) {
                                $('#message').html(
                                        '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />No Existe Pedido\n\
                                        </div>');
                            } else {
                                if (pag_pro.toString().length <= 0) {
                                    $('#message').html(
                                            '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                <strong>Error</strong><br />No Existe un Recibo de Pago\n\
                                            </div>');
                                } else {
                                    if (pag_obs.toString().length <= 0) {
                                        $('#message').html(
                                                '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>Error</strong><br />Debe indicar una observación del descarte del Pago\n\
                                                </div>');
                                    } else {
                                        $('#message').html("");
                                        var method = "<?= base_url(); ?>Pagos/Discard/";
                                        $("body").css({
                                            'cursor': 'wait'
                                        })
                                        $.ajax({
                                            type: 'post',
                                            url: method,
                                            data: {
                                                pag_cli: pag_cli, pag_ped: pag_ped, pag_pag: pag_pag, pag_pro: pag_pro, pag_obs: pag_obs,
                                                pag_sal: pag_sal, pag_cuo: pag_cuo, pag_Rellam: pag_Rellam
                                            },
                                            cache: false,
                                            beforeSend: function () {
                                                $('#message').html("");
                                                $('#btn-descartarPago').html('<i class="fa fa-close"></i> Descartando...');
                                                //$('#btn-descartarPago').attr("disabled", "true");
                                            },
                                            success: function (data) {
                                                $('#btn-descartarPago').html('<i class="fa fa-close"></i> Descartar Pago');
                                                if (data == 1) {
                                                    $('#message').html(
                                                            '<div class="alert alert-success alert-dismissable fade in">\n\
                                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                                <strong>Se descartó el pago de <b>$ ' + new Intl.NumberFormat("es-CO").format(pag_pag) + '</b></strong>\n\
                                                            </div>');
                                                    location.href = "<?= base_url("Pagos/Cliente/"); ?>" + pag_cli + "/";
                                                } else {
                                                    //$('#btn-descartarPago').removeAttr("disabled");
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



                });

            </script>
            <?php
        }
        ?>
    </div>