<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="content">
    <div class="header">        
        <?php //$this->load->view('Modules/notifications'); ?>
        <h1 class="page-title" style="font-size: 2em;"><?= $title; ?>  </h1>
    </div>            
    <div class="main-content">
        <div class="panel panel-default">
            <a href="#" class="panel-heading" data-toggle="collapse"><?= $subtitle; ?></a>
            <div id="page-stats" class="panel-collapse panel-body collapse in">
                <div class="row">
                    <?php if ($this->session->flashdata("msg")): ?>
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissable fade in">
                                <?= $this->session->flashdata("msg"); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata("permisos")): ?>
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissable fade in">
                                <?= $this->session->flashdata("permisos"); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-12">
                        <label style="font-size: 24px;">Nuestros accesos directos:</label>
                    </div>                    
                    <br><br>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <?php 
                                    $idPermiso = 59;
                                    $btn = validarPermisoBoton($idPermiso);
                                    if ($btn){
                                ?>
                                        <a href="<?= base_url('Clientes/Buscar/'); ?>" class="btn btn-primary btn-block"><i class="fa fa-search"></i>  Buscar Clientes</a>
                                <?php
                                    }
                                ?>
                                <br>
                            </div>
                            <div class="col-md-3">
                                <?php 
                                    $idPermiso = 60;
                                    $btn = validarPermisoBoton($idPermiso);
                                    if ($btn){
                                ?>
                                        <a href="<?= base_url('Pagos/Admin/'); ?>" class="btn btn-primary btn-block"><i class="fa fa-phone"></i>  Llamadas del Día</a>
                                <?php
                                    }
                                ?>
                                <br>
                            </div>
                            <div class="col-md-3">
                                <?php 
                                    $idPermiso = 61;
                                    $btn = validarPermisoBoton($idPermiso);
                                    if ($btn){
                                ?>
                                        <a href="<?= base_url('Pagos/AdminProg/'); ?>" class="btn btn-primary btn-block"><i class="fa fa-motorcycle"></i>  Recibos de Pago</a>
                                <?php
                                    }
                                ?>
                                <br>
                            </div>
                            <div class="col-md-3">
                                <?php 
                                    $idPermiso = 62;
                                    $btn = validarPermisoBoton($idPermiso);
                                    if ($btn){
                                ?>
                                        <a href="<?= base_url('Cartera/Usuarios/'); ?>" class="btn btn-primary btn-block"><i class="fa fa-arrow-right"></i>  Cartera Por Usuario</a>
                                <?php
                                    }
                                ?>
                                <br>    
                            </div>
                        </div>
                    </div>
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

