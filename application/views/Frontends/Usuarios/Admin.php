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
                            <?php
                            $idPermiso = 2;
                            $accion = validarPermisoAcciones($idPermiso);
                            if ($accion) {
                                ?>
                                <a href="<?= base_url() . "Mantenimiento/Usuarios/Crear/" ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo Usuario</a>
                                <?php
                            }

                            $idPermiso = 87;
                            $menu = validarPermisoMenu($idPermiso);
                            if ($menu) {
                                ?>
                                <a href="<?= base_url() . 'Mantenimiento/Usuarios/Eliminados/'; ?>" class="btn btn-danger">Eliminados</a>
                                <?php
                            }
                            ?>                                                
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
                                <?php foreach ($ListaDatos as $item) { ?>
                                    <tr>
                                        <td><?= $item["Usuario"]; ?></td>
                                        <td><?= $item["Nombre"]; ?></td>
                                        <td><?= $item["Perfil"]; ?></td>
                                        <td><?= $item["Estado"]; ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <?php
                                                $idPermiso = 3;
                                                $accion = validarPermisoAcciones($idPermiso);
                                                if ($accion) {
                                                    ?>
                                                    <a href="<?= base_url() . "Mantenimiento/Usuarios/Consultar/" . $item["Codigo"] . "/"; ?>" title="Consultar Usuario"><i class="fa fa-search" aria-hidden="true" style="padding:5px;"></i></a>
                                                    <?php
                                                }

                                                $idPermiso = 4;
                                                $accion = validarPermisoAcciones($idPermiso);
                                                if ($accion) {
                                                    ?>
                                                    <a href="<?= base_url() . "Mantenimiento/Usuarios/ResetPass/" . $item["Codigo"] . "/" . $item["Usuario"] . "/"; ?>" title="Resetear Contraseña"><i class="fa fa-key" aria-hidden="true" style="padding:5px;"></i></a>
                                                    <?php
                                                }

                                                $idPermiso = 9;
                                                $accion = validarPermisoAcciones($idPermiso);
                                                if ($accion) {
                                                    ?>
                                                    <a href="<?= base_url() . "Mantenimiento/Permisos/Usuario/" . $item["Codigo"] . "/"; ?>" title="Permisos de Usuario"><i class="fa fa-unlock-alt" aria-hidden="true" style="padding:5px;"></i></a>
                                                    <?php
                                                }

                                                $idPermiso = 5;
                                                $accion = validarPermisoAcciones($idPermiso);
                                                if ($accion) {
                                                    ?>
                                                    <a href="<?= base_url() . "Mantenimiento/Usuarios/Log/" . $item["Codigo"] . "/"; ?>" title="Consultar Log del Usuario"><i class="fa fa-list-alt" aria-hidden="true" style="padding:5px;"></i></a>
                                                    <?php
                                                }

                                                $idPermiso = 6;
                                                $accion = validarPermisoAcciones($idPermiso);
                                                if ($accion) {
                                                    ?>
                                                    <a href="<?= base_url() . "Mantenimiento/Usuarios/Eliminar/" . $item["Codigo"] . "/"; ?>" title="Eliminar Usuario"><i class="fa fa-trash-o" aria-hidden="true" style="padding:5px;"></i></a>
                                                        <?php
                                                    }
                                                    ?>                                                                                                
                                            </div>                                        
                                        </td>
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
