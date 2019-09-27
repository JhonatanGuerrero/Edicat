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
            $item["Observaciones"] = str_replace("\n", "\n", $item["Observaciones"]);
            ?>
            <div class="panel panel-default">
                <div id="page-stats-<?= $item["Codigo"] ?>" class="panel-collapse panel-body collapse in">
                    <div class="row">             
                        <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cuotas Pagadas</label>
                                    <input type="text" value="<?= $item["Cuota"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Saldo</label>
                                    <input type="text" value="<?= money_format("%.0n", $item["Saldo"]); ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Valor de Devolución</label>
                                    <input type="text" value="<?= money_format("%.0n", $item["ValorDevol"]); ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cobrador</label>
                                    <input type="text" value="<?= $item["NomCobrador"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div> 
                            </div> 
                            <br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Notas/Observaciones</label>
                                    <textarea value="" rows="6" class="form-control" style="resize: none;" id="Observacion" name="Observacion" disabled><?= $item["Observaciones"]; ?></textarea>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="pull-right btn-toolbar list-toolbar">
                                    <a href="<?= base_url(); ?>Devoluciones/Admin/" class="btn btn-primary"><i class="fa fa-truck"></i> Todas las Devoluciones</a>
                                </div>
                            </div>
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