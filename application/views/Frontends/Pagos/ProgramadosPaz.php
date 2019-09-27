<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="content">
    <div class="header">        
        <?php //$this->load->view('Modules/notifications'); ?>
        <h1 class="page-title" style="font-size: 2em;"><?= $title; ?> </h1>
    </div>            
    <div class="main-content">
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
                    <a href="<?= base_url() . "Pagos/Cliente/" . $cliente; ?>" class="btn btn-default"><i class="fa fa-undo"></i> Pagos Cliente</a>
                </div>
            </div>                            
        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Valor</th>
                            <th>Fecha Programada</th>
                            <th>Estado</th>
                            <th>Nota</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($ListaDatos) > 0) {
                            foreach ($ListaDatos as $item) {
                                //var_dump($item);
                                $item["Observaciones"] = str_replace("\n", "", $item["Observaciones"]);
                                $item["Observaciones"] = str_replace("---", ". ", $item["Observaciones"]);
                                ?>
                                <tr>
                                    <td><?= $item["Pedido"]; ?></td>
                                    <td><?= money_format("%.0n", $item["Cuota"]); ?></td>
                                    <td><?= date("d/m/Y", strtotime($item["FechaProgramada"])); ?></td>
                                    <td><?= $item["NomEstado"]; ?></td>
                                    <td><?php
                                        if (strlen($item["Observaciones"]) > 50) {
                                            echo substr($item["Observaciones"], 0, 50) . " (...)";
                                        } else {
                                            echo $item["Observaciones"];
                                        }
                                        ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?= base_url() . "Pagos/Validar/" . $item["Codigo"] . "/"; ?>" title="Ver Recibo de Pago"><i class="fa fa-search" aria-hidden="true" style="padding:5px;"></i></a>
                                            <?php
                                            if ($item["NomEstado"] == "Programado") {
                                                ?>
                                                <a href="<?= base_url() . "Pagos/Confirmar/" . $item["Codigo"] . "/"; ?>" title="Confirmar Pago"><i class="fa fa-check" aria-hidden="true" style="padding:5px;"></i></a>
                                                <a href="<?= base_url() . "Pagos/Descartar/" . $item["Codigo"] . "/"; ?>" title="Descartar Pago"><i class="fa fa-close" aria-hidden="true" style="padding:5px;"></i></a>                                                
                                                <?php
                                            }
                                            ?>
                                        </div>                                        
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="btn-group">
                                        <label>No existen Pagos Programados para este pedido</label>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>                            
        </div>
        <script>
            $(document).ready(function () {
                $('#<?= $Controller; ?>').DataTable({
                    responsive: true,
                    scrollX: true,
                    bSort: false,
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    }
                });
            });
        </script>
