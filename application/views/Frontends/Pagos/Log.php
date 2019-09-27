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
                    <?php if ($this->session->flashdata("msg")): ?>
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissable fade in">                       
                                <?= $this->session->flashdata("msg"); ?>
                            </div>
                        </div>
                    <?php endif; ?>
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
                            <a href = "<?= base_url() . "Pagos/Cliente/" . $cliente; ?>" class = "btn btn-default"><i class = "fa fa-undo"></i> Pagos del Cliente</a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Modulo</th>
                                    <th>Accion</th>
                                    <th>Fecha</th>
                                    <th>Notas/Observaciones</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($ListaDatos as $item) {
                                    $item["Observaciones"] = str_replace("\n", "", $item["Observaciones"]);
                                    $item["Observaciones"] = str_replace("---", ". ", $item["Observaciones"]);
                                    ?>
                                    <tr>
                                        <td><?= $item["Modulo"]; ?></td>
                                        <td><?= $item["Accion"]; ?></td>
                                        <td><?= $item["Fecha"]; ?></td>
                                        <td><?php
                                            if (strlen($item["Observaciones"]) > 50) {
                                                echo substr($item["Observaciones"], 0, 50) . " (...)";
                                            } else {
                                                echo $item["Observaciones"];
                                            }
                                            ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="<?= base_url() . "Pagos/VerLog/" . $item["Codigo"] . "/"; ?>" title="Ver Registro"><i class="fa fa-eye" aria-hidden="true" style="padding:5px;"></i></a>                                                
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
                    lengthMenu: [25, 50, 100, -1],
                    responsive: true,
                    scrollX: true,
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    }
                });
            });
        </script>
