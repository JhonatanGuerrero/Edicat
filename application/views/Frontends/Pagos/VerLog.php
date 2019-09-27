<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!--<style>
    .form-control[disabled]{
        background-color: #fff;
    }
</style>    -->
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Codigo">Código</label>
                            <input type="text" disabled class="form-control" name="Codigo" id="Codigo" value="<?= $ListaDatos[0]['Codigo']; ?>" />
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Usuario">Usuario</label>
                            <input type="text" disabled class="form-control" name="Usuario" id="Usuario" value="<?= $ListaDatos[0]['Usuario']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">                    
                        <div class="form-group">
                            <label for="Fecha">Fecha</label>
                            <input type="text" disabled class="form-control" name="Fecha" id="Fecha" value="<?= $ListaDatos[0]['Fecha']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Modulo">Módulo</label>
                            <input type="text" disabled class="form-control" name="Modulo" id="Modulo" value="<?= $ListaDatos[0]['Modulo']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Tabla">Tabla</label>
                            <input type="text" disabled class="form-control" name="Tabla" id="Tabla" value="<?= $ListaDatos[0]['Tabla']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Accion">Acción</label>
                            <input type="text" disabled class="form-control" name="Accion" id="Accion" value="<?= $ListaDatos[0]['Accion']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Llave">Registro Afectado o Consultado</label>
                            <input type="text" disabled class="form-control" name="Llave" id="Llave" value="<?= $ListaDatos[0]['Llave']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="datos">Datos Ingresados o Modificados</label>
                            <textarea disabled class="form-control" name="datos" id="datos" rows="10" m ><?= trim(str_replace("___", "\n", $ListaDatos[0]['Datos'])); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea disabled class="form-control" name="observaciones" id="observaciones" rows="10" m ><?= trim(str_replace("___", "\n", $ListaDatos[0]['Observaciones'])); ?></textarea>
                        </div>
                    </div>  
                    <div class="col-md-2 col-md-offset-5">
                        <a href="<?= base_url() . "Pagos/Log/" . $ListaDatos[0]['Llave'] . "/"; ?>" class="btn btn-primary"><i class="fa fa-undo"></i> Volver a Pagos</a>
                    </div>
                </div>
            </div>
        </div>  
        <script>
            $(document).ready(function () {
                $('#<?= $Controller; ?>').DataTable({
                    responsive: true,
                    scrollX: true,
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    }
                });
            });
        </script>