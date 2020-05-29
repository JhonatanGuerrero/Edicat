<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
    .aldia{
        background-color: #77dd77 !important;
        color: white;
    }
    .debe{
        background-color: #e8e847 !important;
        color: black;
    }
    .enmora{
        background-color: #dda640 !important;
        color: white;
    }
    .datacredito{
        background-color: #d15a42 !important;
        color: white;
    }
    .reportado{
        background-color: #000 !important;
        color: white;
    }
    .pazysalvo{
        background-color: #4da5a3 !important;
        color: white;
    }
    .devolucion{
        background-color: #884da5 !important;
        color: white;
    }
</style>
<div class="content">
    <div class="header">
        <?php //$this->load->view('Modules/notifications'); ?>
        <h1 class="page-title" style="font-size: 2em;"><?=$title;?> </h1>
    </div>
    <div class="main-content">
        <div class="panel panel-default">
            <a href="#page-stats" class="panel-heading" data-toggle="collapse" id="subtitleTexto"><?=$subtitle;?></a>
            <div id="page-stats" class="panel-collapse panel-body collapse in" id="subtitle">
                <div class="row">
                    <?php if ($this->session->flashdata("msg")): ?>
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissable fade in">
                                <?=$this->session->flashdata("msg");?>
                            </div>
                        </div>
                    <?php endif;?>
                    <?php if ($this->session->flashdata("error")): ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissable fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>Error</strong>
                                <br />
                                <?=$this->session->flashdata("error");?>
                            </div>
                        </div>
                    <?php endif;?>
                    <?php
                        $idPermiso = 109;
                        $btn = validarPermisoBoton($idPermiso);
                        if (!$btn): ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissable fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>Error</strong>
                                <br />
                                <?="El Usuario <b>" . $this->session->userdata('Usuario') . "</b> no tiene permisos para: <b>Buscar Clientes</b>."?>                                
                            </div>
                        </div>
                    <?php endif;?>
                    <?php
                        $idPermiso = 97;
                        $btnD = validarPermisoBoton($idPermiso);                         
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <form id="frmBuscar" method="POST">
                                <div class="col-md-12">
                                    <div class="col-md-4 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="nombre">Nombre:</label>                                            
                                            <input type="text" name="nombre" id="nombre" class="form-control" <?php if (!$btn) { ?> readonly="true" <?php } ?> >
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="cedula">Cédula:</label>
                                            <input type="text" name="cedula" id="cedula" class="form-control" <?php if (!$btn) { ?> readonly="true" <?php } ?> >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="direccion">Direccion:</label>
                                            <input type="text" name="direccion" id="direccion" class="form-control" <?php if (!$btn) { ?> readonly="true" <?php } ?> >
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono:</label>
                                            <input type="text" name="telefono" id="telefono" class="form-control" <?php if (!$btn) { ?> readonly="true" <?php } ?> >
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="ubicacion">Ubicacion:</label>
                                            <input type="text" name="ubicacion" id="ubicacion" class="form-control" <?php if (!$btn) { ?> readonly="true" <?php } ?> >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="selectEstados">Estado:</label>
                                            <select name="selectEstados" id="selectEstados" class="form-control" <?php if (!$btn) { ?> disabled <?php } ?>>
                                                <option value=""></option>
                                                <?php
                                                    if ($Lista1 != false) {
                                                        foreach ($Lista1 as $value) {
                                                            echo "<option value='" . $value["Codigo"] . "'>" . $value["Nombre"] . "</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group text-center">
                                            <br />
                                            <button id="btn-buscar" name="btn-buscar" class="btn btn-primary <?php if (!$btn) { ?> disabled <?php } ?>"><i class="fa fa-search"></i> Buscar Clientes</button>
                                            <button id="btn-limpiar-filtro" name="btn-limpiar-filtro" class="btn btn-default <?php if (!$btn) { ?> disabled <?php } ?>"><i class="fa fa-eraser"></i> Limpiar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="message">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="panel panel-default hidden" id="panel-result">
            <a href="#page-result" class="panel-heading" data-toggle="collapse">Resultado de Búsqueda</a>
            <div id="page-result" class="panel-collapse panel-body collapse in">
                <div class="row">
                    <div class="col-md-12">
                        <table id="<?=$Controller;?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Télefono</th>
                                    <th>Saldo</th>
                                    <th>Próximo Pago</th>
                                    <th>Ubicación Física</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                        </table>
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
                            <h3 id="myModalLabel">Devolución Cliente/Pedido </h3>
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
                                        <input type="number" id="modal-val" name="modal-val" class="form-control" <?php if (!$btnD) { ?> readonly="true" <?php } ?> value="" min="0" style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cobrador:</label>
                                        <select name="modal-cobrador" id="modal-cobrador" class="form-control required" <?php if (!$btnD) { ?> disabled <?php } ?> style="background-color:#ffffff;">
                                            <option value=""></option>
                                            <?php
                                            foreach ($Lista2 as $item):
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
                                        <textarea rows="6" class="form-control" name="modal-obs" id="modal-obs" <?php if (!$btnD) { ?> disabled <?php } ?> style="resize: none; background-color:#ffffff;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row <?php if (!$btnD) { ?> hidden <?php } ?>">
                                <div class="col-md-12">
                                    <p class="error-text" style="color:red;"><i class="fa fa-warning modal-icon"></i>¿Desea hacer la devolución de este Cliente? <br><i>*Recuerde que no se podrá revertir esta acción*</i></p>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <br>
                                <?php if (!$btnD) { ?>
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissable fade in">
                                            No tiene <b>Permisos</b> para hacer una <b>devolución</b>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div id="modal-message">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="margin-top: -15px;">
                            <button id="btn-modal-cerrar" name="btn-modal-cerrar" class="btn btn-default <?php if (!$btnD) { ?> hidden <?php } ?>" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                            <button id="btn-modal" name="btn-modal" class="btn btn-primary <?php if (!$btnD) { ?> hidden <?php } ?>"><i class="fa fa-reply-all"></i> Devolver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <script>
            $(document).ready(function () {
                $('#<?=$Controller;?>').DataTable({
                    responsive: true,
                    scrollX: true,
                    language: {
                        url: "<?=base_url('Public/assets/');?>/lib/Datetables.js/Spanish.json"
                    }
                });

                $('#form-modal').submit(function (e) {
                    e.preventDefault();
                    devolucion();
                });

                $('#btn-modal').submit(function (e) {
                    e.preventDefault();
                    devolucion();
                });

                $('#frmBuscar').submit(function (e) {
                    e.preventDefault();
                    buscar();
                });

                $('#btn-buscar').click(function (e) {
                    e.preventDefault();
                    buscar();
                });


                $('#btn-limpiar-filtro').click(function (e) {
                    e.preventDefault();
                    $('#message').html("");
                    $('#nombre').val("");
                    $('#cedula').val("");
                    $('#direccion').val("");
                    $('#telefono').val("");
                    $('#selectEstados').val("");
                    $('#ubicacion').val("");
                    $('#panel-result').addClass("hidden");
                });
            });

            function buscar() {
                var table =$('#<?=$Controller;?>').DataTable();
                table.clear().draw();
                $('#message').html("");
                $('#btn-buscar').html('<i class="fa-li fa fa-spinner fa-pulse"></i> Buscando...');
                var nombre = $('#nombre').val();
                var cedula = $('#cedula').val();
                var direccion = $('#direccion').val();
                var telefono = $('#telefono').val();
                var selectEstados = $('#selectEstados').val();
                var ubicacion = $('#ubicacion').val();

                $("body").css({
                    'cursor': 'wait'
                })

                listarFiltro(nombre, cedula, direccion, telefono, selectEstados, ubicacion);

                $("body").css({
                    'cursor': 'Default'
                })

                $('#btn-buscar').html('<i class="fa fa-search"></i> Buscar Clientes');
                return false;
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


            function listarFiltro(nombre, cedula, direccion, telefono, selectEstados, ubicacion) {
                $('#<?=$Controller;?>').DataTable({
                    bDestroy: true,
                    responsive: true,
                    scrollX: true,
                    columns: [
                        {data: "Nombre"},
                        {data: "Direccion"},
                        {data: "telefono"},
                        {data: "saldo"},
                        {data: "DiaCobro"},
                        {data: "PaginaFisica"},
                        {data: "Estado"},
                        {data: "btn"}
                    ],
                    ajax: {
                        method: 'post',
                        url: "<?=base_url();?>Clientes/SearchJson/",
                        data: {
                            nombre: nombre, cedula: cedula, direccion: direccion, telefono: telefono, estado: selectEstados, ubicacion: ubicacion
                        }
                    },
                    language: {
                        url: "<?=base_url('Public/assets/');?>/lib/Datetables.js/Spanish.json"
                    },
                    createdRow: function (row, data, dataIndex) {
                        if (data["Estado"] == "Al día") {
                            $('td', row).addClass('aldia');
                            $('i', row).addClass('aldia');
                        } else if (data["Estado"] == "Debe") {
                            $('td', row).addClass('debe');
                            $('i', row).addClass('debe');
                        } else if (data["Estado"] == "En Mora") {
                            $('td', row).addClass('enmora');
                            $('i', row).addClass('enmora');
                        } else if (data["Estado"] == "DataCredito") {
                            $('td', row).addClass('datacredito');
                            $('i', row).addClass('datacredito');
                        } else if (data["Estado"] == "Devolución") {
                            $('td', row).addClass('devolucion');
                            $('i', row).addClass('devolucion');
                        } else if (data["Estado"] == "Reportado") {
                            $('td', row).addClass('reportado');
                            $('i', row).addClass('reportado');
                        } else if (data["Estado"] == "Paz y Salvo") {
                            $('td', row).addClass('pazysalvo');
                            $('i', row).addClass('pazysalvo');
                        }
                    }
                });
                $('#panel-result').removeClass("hidden");
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
                            var method = "<?=base_url() . 'Devoluciones/Generar/';?>";
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
