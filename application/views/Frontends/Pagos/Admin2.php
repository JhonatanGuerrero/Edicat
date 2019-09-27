<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .green{
        background-color: green;
        color: white;
    }
    .red{
        background-color: red;
        color: white;
    }
    .orange{
        background-color: orange;
        color: white;
    }
</style>
<div class="content">
    <div class="header">        
        <?php //$this->load->view('Modules/notifications'); ?>
        <h1 class="page-title" style="font-size: 2em;"><?= $title; ?>  </h1>
    </div>            
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Direccion</th>
                            <th>Teléfonos</th>
                            <th>Cuota</th>
                            <th>Saldo</th>
                            <th>Último Pago</th>
                            <th>Próximo Pago</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($datosPagos) > 0 && $datosPagos != false) {
                            foreach ($datosPagos as $pagos) {
                                ?>
                                <tr class="<?= $pagos ["Color"];?>">
                                    <td class="<?= $pagos ["Color"];?>">
                                        <p><?= $pagos["Nombre"]; ?></p>
                                    </td>
                                    <td class="<?= $pagos ["Color"];?>">
                                        <p><?= $pagos["Direccion"]; ?></p>
                                    </td>
                                    <td class="<?= $pagos ["Color"];?>">
                                        <p><?= $pagos["telefono"]; ?></p>
                                    </td>
                                    <td class="<?= $pagos ["Color"];?>">
                                        <p><?= $pagos["cuota"]; ?></p>
                                    </td>
                                    <td class="<?= $pagos ["Color"];?>">
                                        <p><?= money_format("%.0n", $pagos["saldo"]); ?></p>
                                    </td>
                                    <td class="<?= $pagos ["Color"];?>">
                                        <p><?= $pagos["fechaUltimoPago"]; ?></p>
                                    </td>
                                    <td class="<?= $pagos ["Color"];?>">
                                        <p><?= $pagos["DiaCobro"]; ?></p>
                                    </td>
                                    <td class="text-center dataTables_empty <?= $pagos ["Color"];?>">
                                        <div class="btn-group">
                                            <a href="#ModalCall" data-toggle="modal" title="Reportar Llamada" onclick="DatosModal('<?= $pagos["Pedidos"]; ?>', '<?= $pagos["codCliente"]; ?>', '<?= $pagos["Nombre"]; ?>');"><i class="fa fa-phone <?= $pagos ["Color"];?>" aria-hidden="true" style="padding:5px;"></i></a>
                                            <a href="<?= base_url() . "Cobradores/GestionHoy/" . $pagos["codCliente"] . "/"; ?>" title="Gestión de Llamada"><i class="fa fa-list-ul <?= $pagos ["Color"];?>" aria-hidden="true" style="padding:5px;"></i></a>
                                            <a href="<?= base_url() . "Pagos/Generar/" . $pagos["codCliente"] . "/"; ?>" title="Pagar"><i class="fa fa-motorcycle <?= $pagos ["Color"];?>" aria-hidden="true" style="padding:5px;"></i></a>
                                        </div>                                        
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?> 
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="btn-group">
                                        <p>No se encontraron Clientes que deban pagar entre Hoy, Mañana y Pasado Mañana</p>
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

        <div class="modal  fade" id="ModalCall" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" name="form-modal" id="form-modal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Confirmar Llamada</h3>
                        </div>
                        <div class="modal-body">  
                            <div class="row hidden">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pedido</label>
                                        <input type="text" id="modal-pedido" name="modal-pedido" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cliente</label>
                                        <input type="text" id="modal-cliente" name="modal-cliente" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" id="modal-nombre" name="modal-nombre" class="form-control" readonly>
                            </div>
                            <p class="error-text"><i class="fa fa-phone modal-icon"></i></p>
                            <div class="row" >
                                <div class="col-md-12">
                                    <div class="form-group">                                
                                        <label>Indique el motivo de la llamada realizada:</label>
                                        <select name="motivo" id="motivo" class="form-control">
                                            <?php
                                            foreach ($Lista1 as $item):
                                                echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Programar llamada para:</label>
                                        <input type="text" value="" class="form-control datepicker8" id="FechaProgramada" name="FechaProgramada">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <textarea value="" rows="6" class="form-control" name="Observaciones" id="Observaciones" style="resize: none;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="message">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="margin-top: -15px;">
                            <button class="btn btn-default" id="btn-modal-cerrar" name="btn-modal-cerrar" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                            <button class="btn btn-primary" id="btn-modal" name="btn-modal"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                var todayDate = new Date().getDate();
                $('.datepicker8').datetimepicker({
                    format: 'DD/MM/YYYY',
                    locale: 'es',
                    minDate: new Date(new Date().setDate(todayDate)),
                    maxDate: new Date(new Date().setDate(todayDate + 30))
                });
                $('#<?= $Controller; ?>').DataTable({
                    responsive: true,
                    scrollX: true,
                    bSort: false,
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    }
                });

                $('#form-modal').submit(function (e) {
                    e.preventDefault();
                    GuardarGestion();
                });

                $('#btn-modal').click(function (e) {
                    e.preventDefault();
                    GuardarGestion();
                });

            });

            function DatosModal(pedido, cliente, nombre) {
                $('#message').html("");
                $('#modal-pedido').val(pedido);
                $('#modal-cliente').val(cliente);
                $('#modal-nombre').val(nombre);
                $('#motivo').val("");
                $('#FechaProgramada').val("");
                $('#Observaciones').val("");
            }

            function GuardarGestion() {
                var pedido = $('#modal-pedido').val();
                var cliente = $('#modal-cliente').val();
                var motivo = $('#motivo').val();
                var nombremotivo = $('#motivo option:selected').text();
                var fechaprogramada = $('#FechaProgramada').val();
                var observaciones = $('#Observaciones').val();

                if (pedido.toString().length <= 0) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />No existe Pedido\n\
                            </div>');
                } else {
                    if (cliente.toString().length <= 0) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />No existe Cliente\n\
                                </div>');
                    } else {
                        if (motivo == "" || motivo == null) {
                            $('#message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Debe indicar un motivo de llamada\n\
                                    </div>');
                        } else {
                            if (observaciones == "") {
                                $('#message').html(
                                        '<div class="alert alert-danger alert-dismissable fade in">\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />Debe indicar una Observación\n\
                                        </div>');
                            } else {
                                $('#message').html("");
                                var method = "<?= base_url(); ?>Cobradores/AddCall/";
                                $("body").css({
                                    'cursor': 'wait'
                                })
                                $.ajax({
                                    type: 'post',
                                    url: method,
                                    data: {
                                        pedido: pedido, cliente: cliente, motivo: motivo, nombremotivo: nombremotivo, fechaprogramada: fechaprogramada, observaciones: observaciones
                                    },
                                    cache: false,
                                    beforeSend: function () {
                                        $('#message').html("");
                                        $('#btn-modal').attr('disabled', 'disabled');
                                        $('#btn-modal').html('<i class="fa fa-save"></i> Guardando...');
                                    },
                                    success: function (data) {
                                        $('#btn-modal').html('<i class="fa fa-save"></i> Guardar');
                                        if (data == 1) {
                                            $('#message').html(
                                                    '<div class="alert alert-success alert-dismissable fade in">\n\
                                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                        <strong>Se generó el reporte de la llamada. \nDireccionando...</strong>\n\
                                                    </div>');
                                            setTimeout(function () {
                                                $('#btn-modal-cerrar').click();
                                                window.location.reload();
                                            }, 1500);
                                        } else {
                                            $('#btn-modal').prop("disabled", false);
                                            $('#message').html(
                                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                        <strong>Error</strong><br />' + data + '\n\
                                                    </div>');
                                        }
                                    }

                                });
                                $("body").css({
                                    'cursor': 'Default'
                                })

                                return false;
                            }
                        }
                    }
                }
            }


        </script>

