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
            <a href="#page-stats" class="panel-heading" data-toggle="collapse"><?= $subtitle; ?></a>
            <div id="page-stats" class="panel-collapse panel-body collapse in">
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
                    <div class="col-md-8 col-md-offset-2">
                        <div class="row hidden">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pedido</label>
                                    <input type="text" id="modal-pedido" name="modal-pedido" value="<?= $pedido; ?>" class="form-control" readonly style="background-color: #fff;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <input type="text" id="modal-cliente" name="modal-cliente" value="<?= $cliente; ?>" class="form-control" readonly style="background-color: #fff;">
                                </div>
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-md-12">
                                <div class="form-group">                          
                                    <label>Nombre Cliente:</label>
                                    <input type="text" id="modal-nombre" name="modal-nombre" value="<?= $DatosCliente["Nombre"]; ?>" class="form-control" readonly style="background-color: #fff;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">                                
                                    <label>Dirección:</label>
                                    <input type="text" class="form-control" value="<?= $DatosCliente["Direccion"]; ?>" readonly id="modal-Direccion" name="modal-Direccion" style="background-color: #fff;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">                                
                                    <label>Teléfonos:</label>
                                    <input type="text" class="form-control" value="<?= $DatosCliente["Telefono"]; ?>" readonly id="modal-Telefono" name="modal-Telefono" style="background-color: #fff;">
                                </div>
                            </div>
                        </div>
                        <?php
                        $i = 1;
                        foreach ($ListaDatos as $value) {
                            ?>
                            <fieldset>
                                <legend>Gestión #<?= $i; ?></legend>
                                <div class="row" >
                                    <div class="col-md-6">
                                        <div class="form-group">                                
                                            <label>Motivo de la llamada realizada:</label>
                                            <input type="text" value="<?= $value["nombreMotivo"]; ?>" class="form-control datepicker8" id="motivo" name="motivo" readonly style="background-color: #fff;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">                                
                                            <label>Fecha y hora de la Gestión:</label>
                                            <input type="text" value="<?= date("d/m/Y h:i:s A", strtotime($value["FechaCreacion"])); ?>" class="form-control datepicker8" id="motivo" name="motivo" readonly style="background-color: #fff;">
                                        </div>
                                    </div>
                                    <?php
                                    if ($value["color"] == "black") {
                                        ?>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Programar llamada para:</label>
                                                <input type="text" value="<?= date("d/m/Y", strtotime($value["FechaProgramada"])); ?>" class="form-control datepicker8" id="FechaProgramada" name="FechaProgramada" readonly style="background-color: #fff;">
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Observaciones</label>
                                            <textarea value="" rows="6" class="form-control" name="Observaciones" id="Observaciones" disabled style="resize: none;"><?= $value["Observaciones"]; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <?php
                            $i++;
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-12" id="message">
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>  