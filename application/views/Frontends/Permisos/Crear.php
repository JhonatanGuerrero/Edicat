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
                    <div class="col-md-12" id="message">
                        <?php
                        if ($this->session->flashdata("error")) {
                            echo '<div class="alert alert-danger" role="alert">' . $this->session->flashdata("error") . '</div>';
                        }
                        ?>
                    </div>
                </div> 
                <div class="btn-toolbar list-toolbar">
                    <form id="frmCrearPermiso" name="frmCrearPermiso" method="POST" role="form">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control span12" value="" id="nombre" name="nombre">
                                </div>
                            </div>                            
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group">
                                    <label for="tipo">Tipo</label>
                                    <select name="tipoPermiso" id="tipoPermiso" class="form-control span12" >
                                        <option disabled selected value>Tipo de Permiso</option>
                                        <?php
                                        foreach ($ListaTipoPer as $item):
                                            echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>  
                                </div>
                            </div>   
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group">
                                    <label for="tipo">Controlador</label>
                                    <select name="controlador" id="controlador" class="form-control span12">
                                        <option disabled selected value>Controlador</option>
                                        <option value="Clientes">Clientes</option>
                                        <option value="Cobradores">Cobradores</option>
                                        <option value="Devolución">Devolución</option>
                                        <option value="Direcciones">Direcciones</option>
                                        <option value="Estados">Estados</option>
                                        <option value="Eventos">Eventos</option>
                                        <option value="Log">Log</option>
                                        <option value="Login">Login/Index</option>
                                        <option value="Menú">Menú</option>
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
                        </div> 
                        <div class="row">
                            <div class="col-md-12" style="display:flex;justify-content:center;">
                                <button type="submit" class="btn btn-primary" id="btnCrearPermiso" name="btnCrearPermiso">
                                    <i class="fa fa-save"></i>&nbsp; Guardar
                                </button>     
                            </div>  
                        </div>  
                    </form>                              
                </div>                              
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#frmCrearPermiso').submit(function (e) {
                e.preventDefault();
                crearPermiso();
            });
            $('#btnCrearPermiso').click(function (e) {
                e.preventDefault();
                crearPermiso();
            });
        });

        function crearPermiso() {
            var nombre = $('#nombre').val();
            var tipo = $('#tipoPermiso').val();
            var controlador = $('#controlador').val();

            //alert(nombre + " - " + tipo + " - " + controlador);
            $('#message').html("");
            if (nombre.toString().length <= 0) {
                $('#message').html(
                        '<div class="alert alert-danger alert-dismissable fade in">\n\
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                            <strong>Error</strong><br />Digite el Nombre para continuar\n\
                        </div>');
            } else {
                if (tipo == null) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                   <strong>Error</strong><br />Seleccione el tipo de Permiso para continuar\n\
                               </div>');
                } else {
                    if (controlador == null) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                   <strong>Error</strong><br />Seleccione el controlador para continuar\n\
                               </div>');
                    } else {
                        var method = "<?= base_url() . 'Mantenimiento/Permisos/NewPermission/'; ?>";
                        $("body").css({
                            'cursor': 'wait'
                        })

                        $.ajax({
                            type: 'POST',
                            url: method,
                            data: {nombre:nombre, tipo:tipo, controlador: controlador},
                            cache: false,
                            beforeSend: function () {
                                $('#message').html("");
                                $("#btnCrearPermiso").html("Guardando...");
                            },
                            success: function (data) {
                                $("#btnCrearPermiso").html('<i class="fa fa-save"></i>&nbsp; Guardar');
                                if (data == 1) {
                                    $('#message').html(
                                            '<div class="alert alert-success alert-dismissable fade in">\n\
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                <strong>El permiso \"<b>' + nombre + '</b>\" fue creado exitosamente</strong>\n\
                                            </div>');
                                    alert('El permiso \"' + nombre + '\" fue creado exitosamente');
                                    location.href = "<?= base_url(); ?>Mantenimiento/Permisos/Admin";
                                } else {
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
    </script>