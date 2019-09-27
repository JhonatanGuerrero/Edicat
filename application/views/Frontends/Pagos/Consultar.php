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
                                    <input type="text" value="<?= money_format("%.0n", $item["ValCuota"]); ?>" class="form-control" disabled style="background-color: #ffffff;" id="SaldoActual" name="SaldoActual">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cuotas</label>
                                    <input type="text" id="Cuota" name="Cuota" value="<?= intval($ListaDatos3[$item["CodPedido"]]["cuota"]); ?>" class="form-control" disabled style="background-color: #ffffff;">
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
                                <legend>Datos Pago</legend>
                                <form id="form-confirmarPago">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Valor del Pago</label>
                                            <input type="text" value="<?= money_format("%.0n", $dataPago[0]["Pago"]); ?>" class="form-control" id="NuevoAbono" name="NuevoAbono" disabled style="background-color: #ffffff;">
                                        </div>
                                    </div>                              
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Cobrador:</label>
                                            <input type="text" value="<?= $Lista1[0]['Nombre']; ?>" class="form-control" id="NuevoAbono" name="NuevoAbono" disabled style="background-color: #ffffff;">                                            
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fecha Pago:</label>
                                            <input type="text" value="<?= date("d/m/Y", strtotime($dataPago[0]["FechaPago"])); ?>" class="form-control" id="FechaPrograma" name="FechaPrograma" disabled style="background-color: #ffffff;">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Notas/Observaciones</label>
                                            <textarea value="" rows="6" class="form-control" style="resize: none;" id="Observacion" name="Observacion" disabled><?= $dataPago[0]["Observaciones"]; ?></textarea>
                                        </div>  
                                    </div>                                      
                                    <div class="col-md-12">
                                        <div class="pull-right btn-toolbar list-toolbar">
                                            <a href="<?= base_url(); ?>Pagos/Cliente/<?= $item["Cliente"]; ?>/" class="btn btn-primary"><i class="fa fa-usd"></i> Pagos Cliente</a>
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
                    
                });

            </script>
            <?php
        }
        ?>
    </div>