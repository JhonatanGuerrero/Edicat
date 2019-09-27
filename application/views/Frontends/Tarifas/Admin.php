<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="content">
    <div class="header">        
        <?php //$this->load->view('Modules/notifications');  ?>
        <h1 class="page-title" style="font-size: 2em;"><?= $title; ?> </h1>
    </div>            
    <div class="main-content">
        <div class="panel panel-default">
            <a href="#page-stats" class="panel-heading" data-toggle="collapse"><?= $subtitle; ?></a>
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
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Producto</th>
                                    <th>Valor</th>
                                    <th>Núm Cuotas</th>
                                    <th>Valor Cuotas</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($ListaDatos) > 0 && $ListaDatos != false) {
                                    foreach ($ListaDatos as $item) {
//                                        var_dump($item);
//                                        echo "<br><br>";
                                        ?>
                                        <tr>
                                            <td><?= $item["Codigo"]; ?></td>
                                            <td><?= $item["NomProducto"]; ?></td>
                                            <td><?= money_format("%.0n", $item["Valor"]); ?></td>                                            
                                            <td><?= $item["Cuotas"]; ?></td>
                                            <td><?= money_format("%.0n", $item["Valor"]); ?></td>  
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="<?= base_url() . "Tarifas/Consultar/" . $item["Codigo"] . "/"; ?>" title="Consultar Tarifa"><i class="fa fa-search" aria-hidden="true" style="padding:5px;"></i></a>
                                                    <a href="<?= base_url() . "Tarifas/Producto/" . $item["Producto"] . "/"; ?>" title="Tarifas del Producto"><i class="fa fa-tags" aria-hidden="true" style="padding:5px;"></i></a>
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