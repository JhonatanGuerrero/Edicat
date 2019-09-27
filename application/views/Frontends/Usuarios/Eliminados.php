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
                            <a href="<?= base_url() . "Mantenimiento/Usuarios/Admin/" ?>" class="btn btn-primary"><i class="fa fa-list-ol"></i> Listado de Usuarios</a>
                            <div class="btn-group">
                            </div>
                        </div>
                    </div>                            
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    <th>Perfil</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($ListaDatos) && $ListaDatos != false) {
                                    foreach ($ListaDatos as $item) {
                                        ?>
                                        <tr>
                                            <td><?= $item["Usuario"]; ?></td>
                                            <td><?= $item["Nombre"]; ?></td>
                                            <td><?= $item["Perfil"]; ?></td>
                                            <td><?= $item["Estado"]; ?></td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="<?= base_url() . "Mantenimiento/Usuarios/Recuperar/" . $item["Codigo"] . "/"; ?>" title="Recuperar Usuario"><i class="fa fa-upload" aria-hidden="true" style="padding:5px;"></i></a>
                                                </div>                                        
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
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
