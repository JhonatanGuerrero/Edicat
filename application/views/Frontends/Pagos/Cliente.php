<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="content">
    <div class="header">
        <?php //$this->load->view('Modules/notifications');?>
        <h1 class="page-title" style="font-size: 2em;"><?= $title; ?></h1>
    </div>
    <div class="main-content">
        <div class="panel panel-default" style="border: none;">
            <div id="page-stats" class="panel-collapse panel-body collapse in" style="border: none;">
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
                            <?php
                            if (isset($pedido)) {
                                if ($ListaDatos2[0]["Estado"] != 123) {
                                    $idPermiso = 100;
                                    $btn = validarPermisoBoton($idPermiso);
                                    if ($btn) {
                                        ?>
                            <a href="<?= base_url() . "Pagos/Generar/" . $cliente . "/"; ?>" class="btn btn-primary"><i
                                    class="fa fa-plus"></i> Hacer Recibo</a>
                            <?php
                                    }
                                    $idPermiso = 118;
                                    $btn = validarPermisoBoton($idPermiso);
                                    if ($btn) {
                                        ?>
                            <a href="<?= base_url() . "Pagos/Programados/" . $pedido . "/"; ?>"
                                class="btn btn-default"><i class="fa fa-shopping-basket"></i> Recibos</a>
                            <?php
                                    }
                                } else {
                                    ?>
                            <a href="<?= base_url() . "Pagos/ProgramadosPaz/" . $pedido . "/"; ?>"
                                class="btn btn-default"><i class="fa fa-shopping-basket"></i> Recibos</a>
                            <?php
                                }
                                $idPermiso = 119;
                                $btn = validarPermisoBoton($idPermiso);
                                if ($btn) {
                                    ?>
                            <a href="<?= base_url() . "Pagos/Historial/" . $pedido . "/"; ?>" class="btn btn-default"><i
                                    class="fa fa-history"></i> Historial</a>
                            <?php
                                }
                                $idPermiso = 120;
                                $btn = validarPermisoBoton($idPermiso);
                                if ($btn) {
                                    ?>
                            <a href="<?= base_url() . "Pagos/Log/" . $pedido . "/"; ?>" class="btn btn-default"><i
                                    class="fa fa-list-alt"></i> Log</a>
                            <?php
                                }
                                $idPermiso = 121;
                                $btn = validarPermisoBoton($idPermiso);
                                if ($btn) {
                                    ?>
                            <a href="<?= base_url() . "Clientes/Consultar/" . $cliente . "/"; ?>"
                                class="btn btn-primary"><i class="fa fa-address-book"></i> Datos Cliente</a>
                            <?php
                                }
                                if (isset($saldo)) {
                                    ?>
                            &nbsp;&nbsp;
                            Saldo Actual: <b><?= money_format("%.0n", $saldo); ?></b>
                            <?php
                                } ?>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Cuota</th>
                                    <th>Pago</th>
                                    <th>Total a Pagar</th>
                                    <th>Fecha</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($ListaDatos) && $ListaDatos!==false) {
                                    foreach ($ListaDatos as $item) {
                                        ?>
                                <tr>
                                    <td><?= $ListaDatos2[0]["Nombre"]; ?></td>
                                    <td><?= $item["Cuota"]; ?></td>
                                    <td><?= money_format("%.0n", $item["Pago"]); ?></td>
                                    <td><?= money_format("%.0n", $item["TotalPago"]); ?></td>
                                    <td><?= date("d/m/Y", strtotime($item["FechaPago"])); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?= base_url() . "Pagos/Consultar/" . $item["Codigo"] . "/"; ?>"
                                                title="Consultar Pago "><i class="fa fa-search" aria-hidden="true"
                                                    style="padding:5px;"></i></a>

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
        $(document).ready(function() {
            $('#<?= $Controller; ?>').DataTable({
                responsive: true,
                scrollX: true,
                language: {
                    url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                }
            });
        });
        </script>