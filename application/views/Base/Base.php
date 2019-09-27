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
                    <div class="col-md-12">
                        <div class="btn-toolbar list-toolbar">
                            <a href="<?= base_url() . "Clientes/Agregar/" ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo Cliente</a>
                            <a href="<?= base_url() . "Clientes/Importar/" ?>" class="btn btn-default">Importar</a>
                            <a href="<?= base_url() . "Clientes/Exportar/" ?>" class="btn btn-default">Exportar</a>                            
                            <div class="btn-group">
                            </div>
                        </div>
                    </div>                            
                </div>
                <div class="row">
                    <div class="col-md-12">
                        
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
