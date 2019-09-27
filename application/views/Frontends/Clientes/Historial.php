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
            <a href="#" class="panel-heading"><?= $subtitle; ?></a>
            <div id="page-stats-0" class="panel-collapse panel-body collapse in">
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <input type="text" value="<?= $DatosCliente["Nombre"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Dirección</label>
                                    <input type="text" value="<?= $DatosCliente["Direccion"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div>                        
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Barrio</label>
                                    <input type="text" value="<?= $DatosCliente["Barrio"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Teléfonos</label>
                                    <input type="text" value="<?= $DatosCliente["Telefono"]; ?>" class="form-control" disabled style="background-color: #ffffff;">
                                </div> 
                            </div>
                        </div>
                    </div>  
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Cuota</th>
                                    <th>Pago</th>
                                    <th>Total a Pagar</th>
                                    <th>Fecha</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>                                
                                <?php
                                if (!isset($ListaDatos)) {
                                    ?>
                                    <tr>
                                        <td colspan="6" class="dataTables_empty">
                                            El Cliente no ha generado ningún pago todavía.
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    foreach ($ListaDatos as $item) {
                                        ?>
                                        <tr>
                                            <td><?= $item["Pedido"]; ?></td>
                                            <td><?= $item["Cuota"]; ?></td>
                                            <td><?= money_format("%.0n", $item["Pago"]); ?></td>
                                            <td><?= money_format("%.0n", $item["TotalPago"]); ?></td>
                                            <td><?= date("d/m/Y", strtotime($item["FechaPago"])); ?></td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="<?= base_url() . "Pagos/Consultar/" . $item["Codigo"] . "/"; ?>" title="Consultar Pago "><i class="fa fa-search" aria-hidden="true" style="padding:5px;"></i></a>
                                                    <a href="<?= base_url() . "Pagos/Programados/" . $item["Pedido"] . "/"; ?>" title="Pagos Programados del Pedido"><i class="fa fa-shopping-basket" aria-hidden="true" style="padding:5px;"></i></a>
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
                    paging: false,
                    searching: false,
                    bSort: false,
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    }
                });
            });
        </script>
