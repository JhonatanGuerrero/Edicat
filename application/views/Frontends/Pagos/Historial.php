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
                            <a href="<?= base_url() . "Pagos/Cliente/" . $cliente . "/"; ?>" class="btn btn-default"><i class="fa fa-undo"></i> Pagos Cliente</a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Acción</th>
                                    <th>Saldo Anterior</th>
                                    <th>Cuota</th>
                                    <th>Abono</th>
                                    <th>Saldo Nuevo</th>
                                    <th style="width: 33.33%;">Notas/Observaciones</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($ListaDatos as $item) {
                                    if ($item["Cuota"] == 0) {
                                        $item["Cuota"] = "--";
                                    }
                                    if ($item["Abono"] == 0) {
                                        $item["Abono"] = "--";
                                    } else {
                                        $item["Abono"] = money_format("%.0n", $item["Abono"]);
                                    }
                                    ?>
                                    <tr title="<?= $item["Observaciones"]; ?>">
                                        <td><?= date("d/m/Y", strtotime($item["FechaHistorial"])); ?></td>
                                        <td><?= $item["Accion"]; ?></td>
                                        <td><?= money_format("%.0n", $item["SaldoAnterior"]); ?></td>
                                        <td><?= $item["Cuota"]; ?></td>
                                        <td><?= $item["Abono"]; ?></td>
                                        <td><?= money_format("%.0n", $item["SaldoNuevo"]); ?></td>
                                        <td style="width: 33.33%;">
                                            <?php
                                            $item["Observaciones"] = str_replace("\n", "<br>", $item["Observaciones"]);
                                            echo $item["Observaciones"];
                                            ?>
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
                    //lengthMenu: [25, 50, 100, -1],
                    responsive: true,
                    scrollX: true,
                    paging: false,
                    bSort: false,
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    }
                });
            });
        </script>
