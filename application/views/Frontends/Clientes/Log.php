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
            <a href="#" class="panel-heading" data-toggle="collapse"><?= $subtitle; ?></a>
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
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Modulo</th>
                                    <th>Accion</th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ListaDatos as $item) { ?>
                                    <tr>
                                        <td><?= $item["Modulo"]; ?></td>
                                        <td><?= $item["Accion"]; ?></td>
                                        <td><?= $item["Fecha"]; ?></td>
                                        <td><?= $item["Usuario"]; ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                            <?php
                                            $idPermiso = 110;
                                            $btn = validarPermisoBoton($idPermiso);
                                            if ($btn) {
                                            ?>
                                                <a href="<?= base_url() . "Clientes/VerLog/" . $item["Codigo"] . "/"; ?>" title="Ver Registro"><i class="fa fa-eye" aria-hidden="true" style="padding:5px;"></i></a>                                                
                                            <?php 
                                            } else {
                                                ?>
                                                Sin Permisos
                                                <?php
                                            } ?>
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
                    lengthMenu: [ 25, 50, 100, -1 ],
                    responsive: true,
                    scrollX: true,
                    order: [[ 2, "desc" ]],
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    }
                });
            });
        </script>
