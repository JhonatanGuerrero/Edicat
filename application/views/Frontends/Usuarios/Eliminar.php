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
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-toolbar list-toolbar">
                            <a href="<?= base_url() . "Mantenimiento/Usuarios/Admin/" ?>" class="btn btn-danger"><i class="fa fa-undo"></i> Volver a Usuarios</a>
                        </div>
                    </div>  
                </div>   
                <div class="btn-toolbar list-toolbar">
                    <form id="frmEliminarUsuario" name="frmEliminarUsuario" method="POST" role="form">
                        <div class="row">
                            <div class="col-md-4 col-md-offset-2">
                                <div class="form-group hidden">
                                    <label for="EliminarCodigo">Codigo</label>
                                    <input type="text" class="form-control span12" disabled value="<?= $ListaDatos[0]['Codigo']; ?>" id="EliminarCodigo" name="EliminarCodigo">
                                </div>                            
                                <div class="form-group">
                                    <label for="EliminarUsuario">Usuario</label>
                                    <input type="text" class="form-control span12" disabled value="<?= $ListaDatos[0]['Usuario']; ?>" id="EliminarUsuario" name="EliminarUsuario">
                                </div>
                            </div>
                            <div class="col-md-8  col-md-offset-2">
                                <div class="form-group">
                                    <label for="EliminarNombre">Nombre</label>
                                    <input type="text" class="form-control span12" value="<?= $ListaDatos[0]['Nombre']; ?>" id="EliminarNombre" name="EliminarNombre">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="display:flex;justify-content:center;">
                                <button type="submit" class="btn btn-primary" id="btnEliminarUsuario">
                                    <i class="fa fa-trash"></i>&nbsp; Eliminar
                                </button>      
                            </div>  
                        </div> 
                    </form>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 hidden" id="Confirmacion"> 
                            <div class="alert alert-warning alert-dismissable fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <h3><strong>Confirmación</strong><br /></h3>
                                <p>
                                    ¿Está seguro que desea eliminar este usuario?                                    
                                </p>
                                <div class="btn-toolbar list-toolbar">
                                    <a href="#" class="btn btn-default pull-right" id="btnConfirmaEliminar">Eliminar</a>
                                </div>                                
                            </div>
                        </div>
                    </div>
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
            $('#frmEliminarUsuario').submit(function (e) {
                e.preventDefault();
                confirmaEliminar();
            });
            $('#btnEliminarUsuario').click(function (e) {
                e.preventDefault();
                confirmaEliminar();
            });
            function confirmaEliminar() {
                $('#Confirmacion').removeClass("hidden");
            }
            $('#btnConfirmaEliminar').click(function (e) {
                e.preventDefault();
                eliminarusuario();
            });
            function eliminarusuario() {
                $('#message').html("");
                var user_cod = $('form[name=frmEliminarUsuario] input[name=EliminarCodigo]')[0].value.trim();
                var user_user = $('form[name=frmEliminarUsuario] input[name=EliminarUsuario]')[0].value.trim();
                var user_name = $('form[name=frmEliminarUsuario] input[name=EliminarNombre]')[0].value.trim();
                if (user_cod.toString().length <= 0) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />No se encuentra el Código del Usuario. Actualice la página antes de continuar.\n\
                            </div>');
                } else {
                    if (user_user.toString().length <= 0) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />No se encuentra el Usuario. Actualice la página antes de continuar.\n\
                                </div>');
                    } else {
                        if (user_name.toString().length <= 0) {
                            $('#message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Digite el Nombre para continuar\n\
                                    </div>');
                        } else {
                            var method = "<?= base_url() . 'Mantenimiento/Usuarios/DeleteUser/'; ?>";
                            $("body").css({
                                'cursor': 'wait'
                            })

                            $.ajax({
                                type: 'POST',
                                url: method,
                                data: {user_cod: user_cod, user_user: user_user, user_name: user_name},
                                cache: false,
                                beforeSend: function () {
                                    $('#message').html("");
                                    $("#btnEliminarUsuario").html("Eliminando...");
                                },
                                success: function (data) {
                                    $("#btnEliminarUsuario").html('<i class="fa fa-trash"></i>&nbsp; Eliminar');
                                    if (data == 1) {
                                        $('#message').html(
                                                '<div class="alert alert-success alert-dismissable fade in">\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>El usuario \"<b>' + user_user + '</b>\" fue Eliminado exitosamente</strong>\n\
                                                </div>');
                                        alert('El usuario \"' + user_user + '\" fue Eliminado exitosamente');
                                        location.href = "<?= base_url(); ?>Mantenimiento/Usuarios/Admin";
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
        });
    </script>
