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
                            <a href="<?= base_url() . "Pagos/Generar/" . $cliente; ?>" class="btn btn-primary"><i
                                    class="fa fa-plus"></i> Hacer nuevo Recibo</a>
                            <a href="<?= base_url() . "Pagos/Cliente/" . $cliente; ?>" class="btn btn-default"><i
                                    class="fa fa-undo"></i> Pagos Cliente</a>
                            <a href="<?= base_url() . "Clientes/Consultar/" . $cliente . "/"; ?>"
                                class="btn btn-primary"><i class="fa fa-address-book"></i> Datos Cliente</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th># Recibo</th>
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
                                    <td><?= $item["Codigo"]; ?></td>
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
                                            <?php
                                                    $idPermiso = 114;
                                                    $accion = validarPermisoAcciones($idPermiso);
                                                    if ($accion) {
                                                        ?>
                                            <a href="<?= base_url() . "Pagos/Validar/" . $item["Codigo"] . "/"; ?>"
                                                title="Ver Recibo de Pago"><i class="fa fa-search" aria-hidden="true"
                                                    style="padding:5px;"></i></a>
                                            <?php
                                                    } 
                                                    if ($item["NomEstado"] == "Programado") {
                                                       
                                                    $idPermiso = 115;
                                                    $accion = validarPermisoAcciones($idPermiso);
                                                    if ($accion) {
                                                        ?>
                                            <a href="<?= base_url() . "Pagos/Confirmar/" . $item["Codigo"] . "/"; ?>"
                                                title="Confirmar Pago"><i class="fa fa-check" aria-hidden="true"
                                                    style="padding:5px;"></i></a>
                                            <?php
                                                    }
                                                         
                                                    $idPermiso = 116;
                                                    $accion = validarPermisoAcciones($idPermiso);
                                                    if ($accion) {
                                                        ?>
                                            <a href="<?= base_url() . "Pagos/Descartar/" . $item["Codigo"] . "/"; ?>"
                                                title="Descartar Pago"><i class="fa fa-close" aria-hidden="true"
                                                    style="padding:5px;"></i></a>
                                            <?php
                                                    }
                                                        
                                                    $idPermiso = 117;
                                                    $accion = validarPermisoAcciones($idPermiso);
                                                    if ($accion) {
                                                        if ($item["Copias"] == 0) {
                                                            echo "<a href='#ModalPrintSolo' data-toggle='modal' title='Imprimir Recibo de Pago' onclick='dataModalSolo(\"1\",\"" . money_format("%.0n", $item["Cuota"]) . "\", \"" . $item['Codigo'] . "\", \"" . $item["Pedido"] . "\");'><i class='fa fa-print' aria-hidden='true' style='padding:5px;'></i></a>"; 
                                                        } else {
                                                            $f1 = strtotime($item["FechaImpresion"]);
                                                            $f2 = strtotime(date("d-m-Y 00:00:00", time()));
                                                            if (($item["Copias"] > 1 && $item["Copias"] < 3) && $f1 != $f2) {
                                                                echo "<a href='#ModalPrintSolo' data-toggle='modal' title='Imprimir Recibo de Pago' onclick='dataModalSolo(\"1\",\"" . money_format("%.0n", $item["Cuota"]) . "\", \"" . $item['Codigo'] . "\", \"" . $item["Pedido"] . "\");'><i class='fa fa-print' aria-hidden='true' style='padding:5px;'></i></a>"; 
                                                            }
                                                        }
                                                    }
                                                        
                                                        
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
            </div>
        </div>

        <div class="modal small fade" id="ModalPrintSolo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" name="form-modal" id="form-modal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Imprimir Recibo de Pago - Único</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row hidden">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pedido</label>
                                        <input type="text" id="modal-PedidoSolo" name="modal-PedidoSolo"
                                            class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pago Programado</label>
                                        <input type="text" id="modal-ClienteSolo" name="modal-ClienteSolo"
                                            class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="error-text"><i class="fa fa-warning modal-icon"></i>¿Desea imprimir estos
                                        recibos de pago en este momento?</p>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Número de Recibos</label>
                                        <input type="text" id="modal-numSolo" name="modal-numSolo" class="form-control"
                                            readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total de Pagos</label>
                                        <input type="text" id="modal-pagSolo" name="modal-pagSolo" class="form-control"
                                            readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="margin-top: -15px;">
                            <button class="btn btn-default" id="btn-modal-cerrar" name="btn-modal-cerrar"
                                data-dismiss="modal" aria-hidden="true">Cerrar</button>
                            <button id="btn-modalSolo" name="btn-modalSolo" class="btn btn-info"><i
                                    class="fa fa-print"></i> Imprimir Recibo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        $(document).ready(function() {
            $('#<?= $Controller; ?>').DataTable({
                responsive: true,
                scrollX: true,
                bSort: false,
                language: {
                    url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                }
            });

            $('#btn-modalSolo').click(function(e) {
                e.preventDefault();
                $('#ModalPrintSolo').modal('toggle');
                imprimirReciboSolo();
            });
        });

        function dataModalSolo(num, pageTotal, cliente, pedido) {
            $('#modal-numSolo').val(num);
            $('#modal-pagSolo').val(pageTotal);
            $('#modal-ClienteSolo').val(cliente);
            $('#modal-PedidoSolo').val(pedido);
        }

        function imprimirReciboSolo() {
            var cliente = $('#modal-ClienteSolo').val();
            var pedido = $('#modal-PedidoSolo').val();
            var margen = prompt(
                "¿Quiere subir o bajar la impresión?\n0: Normal\n1: Arriba 1 punto\n2: Arriba 2 puntos\n3: Abajo 1 punto\n4: Abajo 2 puntos"
            );

            $('#message').html("");
            $("body").css({
                'cursor': 'wait'
            })
            window.open('<?= base_url(); ?>Pagos/ImprimirReciboSolo/' + pedido + '/' + cliente + '/' + margen,
                '_blank');

            $("body").css({
                'cursor': 'Default'
            })

            return false;
        }
        </script>