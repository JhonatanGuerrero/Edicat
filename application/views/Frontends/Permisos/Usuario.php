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
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form id="form-FiltrarControl" name="form-FiltrarControl" method="POST" >
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="controlador">Módulo</label>
                                    <select name="controlador" id="controlador" class="form-control span12">
                                        <option disabled selected value>Módulo</option>
                                        <option value="*">Todos</option>
                                        <option value="Clientes">Clientes</option>
                                        <option value="Cobradores">Cobradores</option>
                                        <option value="Devolución">Devolución</option>
                                        <option value="Direcciones">Direcciones</option>
                                        <option value="Estados">Estados</option>
                                        <option value="Eventos">Eventos</option>
                                        <option value="Log">Log</option>
                                        <option value="Login">Login/Index</option>
                                        <option value="Menu">Menu</option>
                                        <option value="Pagos">Pagos</option>
                                        <option value="Pedidos">Pedidos</option>
                                        <option value="Perfiles">Perfiles</option>
                                        <option value="Permisos">Permisos</option>
                                        <option value="Productos">Productos</option>
                                        <option value="Referencias">Referencias</option>
                                        <option value="Reportes">Reportes</option>
                                        <option value="Tarifas">Tarifas</option>
                                        <option value="TiposDocumentos">TiposDocumentos</option>
                                        <option value="Usuarios">Usuarios</option>
                                        <option value="Vendedores">Vendedores</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipos">Tipos de Permisos</label>
                                    <select name="tipos" id="tipos" class="form-control span12">
                                        <option value="*">Todos</option>
                                        <option value="101">Página</option>
                                        <option value="102">Acción</option>
                                        <option value="103">Botón</option>
                                        <option value="104">Menú</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>                            
                </div>
                <div class="panel panel-default">
                    <div id="page-stats" class="panel-collapse panel-body collapse in">
                        <form method="POST" id="formPermisos">
                            <div class="col-md-12" id="msgErrors">
                            </div>
                            <div class="row" id="content">
                            </div>                            
                        </form>                        
                    </div>
                    <div class="col-md-12" id="msg">
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

                $(document).on('click', '.checkboxPermisos', function (e) {
                    var id = $(this).val();
                    guardarPermisos(id);
                });

                $('#controlador').change(function () {
                    var controlador = $('#controlador').val();
                    var tipo = $('#tipos').val();
                    buscarPermiso(controlador, tipo);
                });
                
                $('#tipos').change(function () {
                    var controlador = $('#controlador').val();
                    var tipo = $('#tipos').val();
                    buscarPermiso(controlador, tipo);
                });
            });

            function buscarPermiso(controlador, tipo) {
                var usuarioPermiso = <?= $usuarioPermisos; ?>;
                if (controlador == null) {
                    $('#msgErrors').html(
                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />Seleccione el Módulo para continuar\n\
                            </div>');
                } else {
                    var method = "<?= base_url() . 'Mantenimiento/Permisos/SearchpermUserControler/'; ?>";
                    $("body").css({
                        'cursor': 'wait'
                    })
                    var data = new Array();
                    $.ajax({
                        type: 'POST',
                        url: method,
                        data: {controlador: controlador, tipo: tipo, usuarioPermiso: usuarioPermiso},
                        cache: false,
                        beforeSend: function () {
                            $('#msgErrors').html('');
                            $('#msg').html(
                                    '<div class="alert alert-success alert-dismissable fade in">\n\
                                        <strong>Buscando...</strong>\n\
                                    </div>');
                        },
                        success: function (data) {
                            $('#msg').html('');
                            if (data == 0) {
                                $('#msgErrors').html(
                                        '<div class="alert alert-danger alert-dismissable fade in">\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />No se encontraron permisos del Controlador\n\
                                        </div>');
                                $("#content").html('');
                            } else {
                                $("#content").html(data);
                            }
                        }
                    });
                    $("body").css({
                        'cursor': 'Default'
                    })

                    return false;
                }
            }

            function guardarPermisos(idPermiso) {
                var nombrePermiso = $('#permiso_' + idPermiso).next('label').text().trim();
                var usuarioPermiso = <?= $usuarioPermisos; ?>;
                var method = "<?= base_url() . 'Mantenimiento/Permisos/guardarPermisosUsuarios/'; ?>";
                $("body").css({
                    'cursor': 'wait'
                })

                var data = new Array();
                $.ajax({
                    type: 'POST',
                    url: method,
                    data: {idPermiso: idPermiso, usuarioPermiso: usuarioPermiso},
                    cache: false,
                    beforeSend: function () {
                        $('#msgErrors').html('');
                        $('#msg').html(
                                '<div class="alert alert-success alert-dismissable fade in">\n\
                                    <strong>Guardando...</strong>\n\
                                </div>');
                    },
                    success: function (data) {
                        $('#msg').html('');
                        $('#msgErrors').html('');
                        if (data == 0) {
                            alert("Error al guardar el permiso");
                            location.reload();
                        } else if (data == 1) {
                            var update = "";
                            if ($('#permiso_' + idPermiso).is(':checked')) {
                                update = '<b>Permiso Asignado: ' + nombrePermiso + '</b>';
                            } else {
                                update = '<b>Permiso Retirado: ' + nombrePermiso + '</b>';
                            }
                            $('#msg').html(
                                    '<div class="alert alert-success alert-dismissable fade in">\n\
                                    ' + update + '\n\
                                </div>');
                        } else {
                            $('#msgErrors').html(
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
        </script>
