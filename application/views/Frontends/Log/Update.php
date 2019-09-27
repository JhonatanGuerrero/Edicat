<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!--<style>
    .form-control[disabled]{
        background-color: #fff;
    }
</style>    -->
<div class="content">
    <div class="header">
        <?php //$this->load->view('Modules/notifications');?>
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
                            <input type="text" disabled class="form-control" name="Codigo" id="Codigo"
                                value="<?= $ListaDatos[0]['Codigo']; ?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Usuario">Usuario</label>
                            <input type="text" disabled class="form-control" name="Usuario" id="Usuario"
                                value="<?= $ListaDatos[0]['NombreUsuario']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Fecha">Fecha</label>
                            <input type="text" disabled class="form-control" name="Fecha" id="Fecha"
                                value="<?= $ListaDatos[0]['Fecha']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Modulo">Módulo</label>
                            <input type="text" disabled class="form-control" name="Modulo" id="Modulo"
                                value="<?= $ListaDatos[0]['Modulo']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Tabla">Tabla</label>
                            <input type="text" disabled class="form-control" name="Tabla" id="Tabla"
                                value="<?= $ListaDatos[0]['Tabla']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Accion">Acción</label>
                            <input type="text" disabled class="form-control" name="Accion" id="Accion"
                                value="<?= $ListaDatos[0]['Accion']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Llave">Código Modificado</label>
                            <input type="text" disabled class="form-control" name="Llave" id="Llave"
                                value="<?= $ListaDatos[0]['Llave']; ?>" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php
                                if ($ListaDatos[0]['Enlace'] != null || $ListaDatos[0]['Enlace'] != "") {
                                    ?>
                            <label for="Enlace">Enlace</label>
                            <br>
                            <a href="<?=base_url().trim(str_replace("|", "/", $ListaDatos[0]['Enlace'])); ?>"
                                class="btn btn-primary" target="_blank"><i class="fa fa-folder-open-o"></i> Ver la
                                Información</a>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="datos">Datos Originales</label>
                            <textarea disabled class="form-control" name="datos" id="datos" rows="12"
                                m><?= trim(str_replace("|", "\n", $ListaDatos[0]['Lectura'])); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Cambios</label>
                                <textarea disabled class="form-control" name="observaciones" id="observaciones" rows="5"
                                    m><?= trim(str_replace("|", "\n", $ListaDatos[0]['Actualizacion'])); ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea disabled class="form-control" name="observaciones" id="observaciones" rows="4"
                                    m><?= trim($ListaDatos[0]['Observaciones']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-md-offset-5">
                        <a href="<?= base_url() . "Mantenimiento/Log/Usuarios/" . $ListaDatos[0]['Usuario'] . "/"; ?>"
                            class="btn btn-primary"><i class="fa fa-undo"></i> Volver al Usuario</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
        $(document).ready(function() {
            $('#<?= $Controller; ?>').DataTable({
                responsive: true,
                scrollX: true,
                language: {
                    url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                }
            });
        });
        </script>