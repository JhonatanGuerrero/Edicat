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
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Télefono</th>
                                    <th>Barrio</th>
                                    <th>Ubicación Física</th>
                                    <th>Estado</th>
                                    <th>Saldo</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($ListaDatos) > 0 && $ListaDatos != false) {
                                    //var_dump($ListaDatos);
                                    foreach ($ListaDatos as $item) {
                                        ?>
                                        <tr>
                                            <td><a href="#" class="hoverData" style="color:#333;" id="<?= $item["CodCliente"]; ?>"><?= $item["Nombre"]; ?></a></td>
                                            <td><a href="#" class="hoverData" style="color:#333;" id="<?= $item["CodCliente"]; ?>"><?= $item["Direccion"]; ?></a></td>
                                            <td><a href="#" class="hoverData" style="color:#333;" id="<?= $item["CodCliente"]; ?>"><?= $item["Telefono"]; ?></a></td>
                                            <td><a href="#" class="hoverData" style="color:#333;" id="<?= $item["CodCliente"]; ?>"><?= $item["Barrio"]; ?></a></td>
                                            <td><a href="#" class="hoverData" style="color:#333;" id="<?= $item["CodCliente"]; ?>"><?= $item["PaginaFisica"]; ?></a></td>
                                            <td><a href="#" class="hoverData" style="color:#333;" id="<?= $item["CodCliente"]; ?>"><?= $item["EstNombre"]; ?></a></td>
                                            <td><a href="#" class="hoverData" style="color:#333;" id="<?= $item["CodCliente"]; ?>"><?= money_format("%.0n", $item["Saldo"]); ?></a></td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                     <?php
                                                    $idPermiso = 15;
                                                    $accion = validarPermisoAcciones($idPermiso);
                                                    if ($accion) {
                                                        ?>
                                                        <a href="<?= base_url() . "Clientes/Consultar/" . $item["CodCliente"] . "/"; ?>" title="Consultar Cliente"><i class="fa fa-search" aria-hidden="true" style="padding:5px;"></i></a>
                                                        <?php
                                                    }
                                                    
                                                    $idPermiso = 19;
                                                    $accion = validarPermisoAcciones($idPermiso);
                                                    if ($accion) {
                                                                                                        
                                                        if ($item["EstNombre"] == "Debe") {
                                                            ?>
                                                            <a href="<?= base_url() . "Pagos/Generar/" . $item["CodCliente"] . "/"; ?>" title="Pagar Saldo"><i class="fa fa-motorcycle" aria-hidden="true" style="padding:5px;"></i></a>
                                                            <?php
                                                        } else if ($item["EstNombre"] == "En Mora") {
                                                            ?>
                                                            <a href="<?= base_url() . "Pagos/PagarMora/" . $item["Codigo"] . "/"; ?>" title="Quitar Mora"><i class="fa fa-motorcycle" aria-hidden="true" style="padding:5px;"></i></a>
                                                            <?php
                                                        } 
                                                    }
                                                    
                                                    $idPermiso = 90;
                                                    $accion = validarPermisoAcciones($idPermiso);
                                                    if ($accion) {
                                                    ?>
                                                        <a href="#ModalDevol" data-toggle="modal" title="Devolución del Cliente" onclick="DatosModal('<?= $item["Codigo"]; ?>', '<?= $item["Cliente"]; ?>', '<?= $item["Nombre"]; ?>', '<?= $item["Saldo"]; ?>', '<?= $item["Cuotas"]; ?>');"><i class="fa fa-reply-all" aria-hidden="true" style="padding:5px;"></i></a>
                                                    <?php
                                                    }
                                                    
                                                    $idPermiso = 26;
                                                    $accion = validarPermisoAcciones($idPermiso);
                                                    if ($accion) {
                                                        ?>
                                                        <a href = "<?= base_url() . "Clientes/Log/" . $item["Codigo"] . "/"; ?>" title = "Registros del Cliente"><i class = "fa fa-list-alt" aria-hidden = "true" style = "padding:5px;"></i></a>
                                                        <?php
                                                    }
                                                    ?>
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

        <div class="modal small fade" id="validandoPagos" tabindex="-1" role="dialog" aria-labelledby="validandoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="validandoLabel">Validando Pagos</h3>
                    </div>
                    <div class="modal-body">
                        <p class="error-text"><i class="fa fa-warning modal-icon"></i>Se cambiarán los estados de los Clientes con más de 45 días sin pago. ¿Desea continuar?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                        <button class="btn btn-primary" id="btnValidarPagos" name="btnValidarPagos"><i class="fa fa-check-square-o"></i> Continuar</button>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="modal fade" id="ModalDevol" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" name="form-modal" id="form-modal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Devolución Cliente/Pedido</h3>
                        </div>
                        <div class="modal-body">     
                            <div class="row hidden">                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pedido</label>
                                        <input type="text" id="modal-pedido" name="modal-pedido" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cliente</label>
                                        <input type="text" id="modal-cliente" name="modal-cliente" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                            </div>                              
                            <div class="row">                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" id="modal-nombre" name="modal-nombre" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cuotas Pagadas</label>
                                        <input type="text" id="modal-cuotas" name="modal-cuotas" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Saldo a Pagar</label>
                                        <input type="text" id="modal-saldo" name="modal-saldo" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                            </div>  
                            <hr>
                            <div class="row">                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Valor a Pagar</label>
                                        <input type="number" id="modal-val" name="modal-val" class="form-control" value="" min="10000" style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cobrador:</label>
                                        <select name="modal-cobrador" id="modal-cobrador" class="form-control required">
                                            <option value=""></option>
                                            <?php
                                            foreach ($Lista1 as $item):
                                                echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                            endforeach;
                                            ?>                                    
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Notas/Observaciones</label>
                                        <textarea rows="6" class="form-control" name="modal-obs" id="modal-obs" style="resize: none;"></textarea>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-md-12">
                                    <p class="error-text"><i class="fa fa-warning modal-icon"></i>¿Desea hacer la devolución de este Cliente? <br><i>*Recuerde que no se podrá revertir esta acción*</i></p>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <br>
                                <div id="modal-message">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="margin-top: -15px;">
                            <button class="btn btn-default" id="btn-modal-cerrar" name="btn-modal-cerrar" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                            <button id="btn-modal" name="btn-modal" class="btn btn-primary"><i class="fa fa-reply-all"></i> Devolver</button>
                        </div>
                    </form>
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
                
                $('.hoverData').tooltip({
                    title: hoverdata,
                    html: true,
                    placement: "right"
                });

                $('#form-modal').submit(function (e) {
                    e.preventDefault();
                    devolucion();
                });

                $('#btn-modal').submit(function (e) {
                    e.preventDefault();
                    devolucion();
                });
            });
            
            function hoverdata() {
                var hoverdata = '';
                var element = $(this);
                var id = element.attr("id");
                var method = "<?= base_url() . 'Clientes/dataClienteHover/'; ?>";
                $.ajax({
                    url: method,
                    method: "POST",
                    async: false,
                    data: {id: id},
                    success: function (data) {
                        hoverdata = data;
                    }
                });
                return hoverdata;
            }
            
            function DatosModal(pedido, cliente, nombre, saldo, cuotas) {
                $('#modal-pedido').val(pedido);
                $('#modal-cliente').val(cliente);
                $('#modal-nombre').val(nombre);
                $('#modal-saldo').val(saldo);
                $('#modal-cuotas').val(cuotas);
                $('#modal-val').val("");
                $('#modal-cobrador').val("");
                $('#modal-obs').val("");
                $('#modal-message').html("");
            }

            function devolucion() {
                var valor = $('#modal-val').val();
                if (valor == "") {
                    $('#modal-message').html(
                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />Debe Indicar un valor para la devolución. <b>Mínimo $ 10.000</b>\n\
                            </div>');

                } else {
                    var pedido = $('#modal-pedido').val();
                    var cliente = $('#modal-cliente').val();
                    var nombre = $('#modal-nombre').val();
                    var saldo = $('#modal-saldo').val();
                    var cuotas = $('#modal-cuotas').val();
                    var cobrador = $('#modal-cobrador').val();
                    var observaciones = $('#modal-obs').val();

                    if (cobrador.toString().length <= 0) {
                        $('#modal-message').html(
                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />Debe Indicar el Cobrador que hará la devolución. \n\
                                </div>');
                    } else {
                        if (observaciones.toString().length <= 0) {
                            $('#modal-message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Debe Indicar una Nota para la devolución. \n\
                                    </div>');
                        } else {
                            var method = "<?= base_url() . 'Devoluciones/Generar/'; ?>";
                            $("body").css({
                                'cursor': 'wait'
                            })

                            $.ajax({
                                type: 'POST',
                                url: method,
                                data: {
                                    pedido: pedido, cliente: cliente, nombre: nombre, saldo: saldo, cuotas: cuotas,
                                    valor: valor, cobrador: cobrador, observaciones: observaciones
                                },
                                cache: false,
                                beforeSend: function () {
                                    $('#modal-message').html("");
                                    $("#btn-modal").html('Devolviendo...');
                                },
                                success: function (data) {
                                    $("#btn-modal").html('<i class="fa fa-reply-all"></i> Devolver');
                                    if (data == 1) {
                                        $('#modal-message').html(
                                                '<div class="alert alert-success alert-dismissable fade in">\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>Devolución Exitosa</strong><br />\n\
                                                </div>');
                                        setTimeout(function () {
                                            $('#btn-modal-cerrar').click();
                                            window.location.reload();
                                        }, 1000);
                                    } else {
                                        $('#modal-message').html(
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
        </script>