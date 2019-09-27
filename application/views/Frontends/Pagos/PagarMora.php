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
            ?>
            <div class="panel panel-default">
                <a href="#page-stats-<?= $item["Codigo"] ?>" class="panel-heading" data-toggle="collapse">Pedido <label id="Pedido" name="Pedido"><?= $item["Codigo"]; ?></label></a>
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
                                    <input type="text" value="<?= money_format("%.0n", $ListaDatos3[$item["Codigo"]]["saldo"]); ?>" class="form-control" disabled style="background-color: #ffffff;" id="SaldoActual_<?= $item["Codigo"]; ?>" name="SaldoActual_<?= $item["Codigo"]; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Valor Pedido</label>
                                    <input type="text" value="<?= money_format("%.0n", $item["Valor"]); ?>" class="form-control" disabled style="background-color: #ffffff;">
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
                                    <input type="text" value="<?= money_format("%.0n", $item["ValCuota"]); ?>" class="form-control" disabled style="background-color: #ffffff;" id="SaldoActual_<?= $item["Codigo"]; ?>" name="SaldoActual_<?= $item["Codigo"]; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Pago mínimo</label>
                                    <input type="text" value="<?= money_format("%.0n", 0);//$ListaDatos3[$item["Codigo"]]["PagoMin"]); ?>" class="form-control" disabled style="background-color: #ffffff;" id="PagoMin_<?= $item["Codigo"]; ?>" name="PagoMin_<?= $item["Codigo"]; ?>">
                                </div> 
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Días de Mora</label>
                                    <input type="text" value="<?= intval($ListaDatos3[$item["Codigo"]]["DiasDiferencia"]); ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha última Cuota</label>
                                    <input type="text" value="<?php
                                    if ($ListaDatos3[$item["Codigo"]]["fechaUltimoPago"] == 0) {
                                        echo "--";
                                    } else {
                                        echo date("d/m/Y", strtotime($ListaDatos3[$item["Codigo"]]["fechaUltimoPago"]));
                                    }
                                    ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div>
                            <br /> 
                            <fieldset style="background-color: #fefefe; padding:5px; border: 1px solid #e3e3e3;">
                                <legend>Programar Pago</legend>
                                <form id="form-programaPago">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Valor de Cuota (Abono)</label>
                                            <input type="number" value="" class="form-control" min="1" max="<?= $ListaDatos3[$item["Codigo"]]["saldo"]; ?>" id="NuevoAbono_<?= $item["Codigo"]; ?>" name="NuevoAbono_<?= $item["Codigo"]; ?>" onchange="saldo(<?= $item["Codigo"]; ?>, document.getElementById('SaldoActual_<?= $item["Codigo"]; ?>').value, document.getElementById('PagoMin_<?= $item["Codigo"]; ?>').value, this.value);">
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nuevo Saldo</label>
                                            <input type="text" value="" class="form-control" disabled style="background-color: #ffffff;" id="NuevoSaldo_<?= $item["Codigo"]; ?>" name="NuevoSaldo_<?= $item["Codigo"]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Programar para:</label>
                                            <input type="text" value="" class="form-control datepicker8" id="FechaPrograma_<?= $item["Codigo"]; ?>" name="FechaPrograma_<?= $item["Codigo"]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Notas/Observaciones</label>
                                            <textarea value="" rows="6" class="form-control" style="resize: none;" id="Observacion_<?= $item["Codigo"]; ?>" name="Observacion_<?= $item["Codigo"]; ?>"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="pull-right btn-toolbar list-toolbar">
                                            <button id="btn-programaPago-<?= $item["Codigo"]; ?>" name="btn-programaPago-<?= $item["Codigo"]; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Programar Pago</button>
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
                        minDate: new Date(new Date().setDate(todayDate - 10)),
                        maxDate: new Date(new Date().setDate(todayDate + 10))
                    });
                    $('#form-programaPago-<?= $item["Codigo"]; ?>').submit(function (e) {
                        e.preventDefault();
                        ProgramarPago(<?= $item["Codigo"]; ?>);
                    });
                    $('#btn-programaPago-<?= $item["Codigo"]; ?>').click(function (e) {
                        e.preventDefault();
                        ProgramarPago(<?= $item["Codigo"]; ?>);
                    });
                    $('#Observacion_<?= $item["Codigo"]; ?>').focus(function () {
                        var abono = $('#NuevoAbono_<?= $item["Codigo"]; ?>').val();
                        var fechaPago = $('#FechaPrograma_<?= $item["Codigo"]; ?>').val();
                        if (abono.toString().length > 0 && fechaPago.toString().length > 0) {
                            var mon = new Intl.NumberFormat().format(abono);
                            var ob = "Cliente indica que quiere pagar $" + mon + " el día " + fechaPago + ".";
                            $('#Observacion_<?= $item["Codigo"]; ?>').text(ob);                            
                        } else {
                            $('#Observacion_<?= $item["Codigo"]; ?>').val("");
                        }
                    });
                    
                    function ProgramarPago(id) {
                        var pag_ped = id;
                        var pag_pag = $('#NuevoAbono_<?= $item["Codigo"]; ?>').val();
                        var pag_fec = $('#FechaPrograma_<?= $item["Codigo"]; ?>').val();
                        var pag_obs = $('#Observacion_<?= $item["Codigo"]; ?>').val();
                        //
                        var pag_sal = $('#SaldoActual_<?= $item["Codigo"]; ?>').val();
                        var pag_cuo = $('#CuotaActual_<?= $item["Codigo"]; ?>').val();
                        var pag_cli = <?= $ListaDatos2[0]["Codigo"]; ?>;

                        if (pag_ped.toString().length <= 0) {
                            $('#message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />No existe Pedido\n\
                                    </div>');
                        } else {
                            if (pag_pag.toString().length <= 0) {
                                $('#message').html(
                                        '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />Debe indicar el valor del Pago\n\
                                        </div>');
                            } else {
                                if (pag_fec.toString().length <= 0) {
                                    $('#message').html(
                                            '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                <strong>Error</strong><br />Debe indicar la Fecha de Programación del Pago\n\
                                            </div>');
                                } else {
                                    if (pag_obs.toString().length <= 0) {
                                        $('#message').html(
                                                '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>Error</strong><br />Debe indicar una observación del Pago\n\
                                                </div>');
                                    } else {
                                        $('#message').html("");
                                        var method = "<?= base_url(); ?>Pagos/SchedulePayment/";
                                        $("body").css({
                                            'cursor': 'wait'
                                        })
                                        $.ajax({
                                            type: 'post',
                                            url: method,
                                            data: {
                                                pag_ped: pag_ped, pag_pag: pag_pag, pag_fec: pag_fec, pag_obs: pag_obs,
                                                pag_sal: pag_sal, pag_cuo: pag_cuo, pag_cli: pag_cli
                                            },
                                            cache: false,
                                            beforeSend: function () {
                                                $('#message').html("");
                                                $('#btn-ubicacion').html('<i class="fa fa-save"></i> Programando...');
                                            },
                                            success: function (data) {
                                                $('#btn-ubicacion').html('<i class="fa fa-save"></i> Programar Pago');
                                                if (data == 1) {
                                                    $('#message').html(
                                                            '<div class="alert alert-success alert-dismissable fade in">\n\
                                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                                <strong>Se programó el pago de <b>$ ' + new Intl.NumberFormat("es-CO").format(pag_pag) + '</b> para el día ' + pag_fec + '</strong>\n\
                                                            </div>');
                                                    location.href = "<?= base_url("Pagos/Programados/"); ?>" + pag_ped + "/";
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

                });
            </script>
            <?php
        }
        ?>
    </div>  
    <script>
        function saldo(id, SaldoActual, PagoMin, Abono = 0) {
            var total = 0;
            SaldoActual = SaldoActual.replace("$", "");
            SaldoActual = SaldoActual.replace(".", "");
            SaldoActual = SaldoActual.replace(" ", "");
            valorSaldo = parseInt($.trim(SaldoActual));

            PagoMin = PagoMin.replace("$", "");
            PagoMin = PagoMin.replace(".", "");
            PagoMin = PagoMin.replace(" ", "");
            valorPagoMin = parseInt($.trim(PagoMin));

            Abono = Abono.replace("$", "");
            Abono = Abono.replace(".", "");
            Abono = Abono.replace(" ", "");
            valorAbono = parseInt($.trim(Abono));

            if (valorAbono < valorPagoMin) {
                alert("El Abono debe ser Mayor o Igual al Pago Mínimo");
                $('#NuevoAbono_' + id).val("");
                $('#NuevoSaldo_' + id).val("");
                $('#NuevoAbono_' + id).focus();
            } else if (valorAbono > valorSaldo) {
                alert("El Abono no puede superar el Saldo Actual");
                $('#NuevoAbono_' + id).val("");
                $('#NuevoSaldo_' + id).val("");
                $('#NuevoAbono_' + id).focus();
            } else {
                total = (valorSaldo - valorAbono);
                $('#NuevoSaldo_' + id).val("$ " + formatNumber(total));
        }
        }

    </script>