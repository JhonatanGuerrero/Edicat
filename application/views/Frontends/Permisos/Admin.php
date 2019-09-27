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
                    <div class="col-md-12">
                        <div class="btn-toolbar list-toolbar">
                            <a href="<?= base_url() . "Mantenimiento/Permisos/Crear/" ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo Permiso</a>
                            <a href="<?= base_url() . "Mantenimiento/Permisos/Usuarios/" ?>" class="btn btn-default"><i class="fa fa-user"></i> Permisos a Usuarios</a>
                        </div>
                    </div>                            
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Controlador</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($ListaDatos as $item) {
                                    ?>
                                    <tr>
                                        <td><?= $item["Codigo"]; ?></td>
                                        <td><?= $item["Nombre"]; ?></td>
                                        <td><?= $item["TipoPermiso"]; ?></td>
                                        <td><?= $item["Controlador"]; ?></td>                                                
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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
