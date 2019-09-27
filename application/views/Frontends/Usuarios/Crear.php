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
                    <form id="frmCrearUsuario" name="frmCrearUsuario" method="POST" role="form">
                        <div class="row">
                            <div class="col-md-4 col-md-offset-2">
                                <div class="form-group">
                                    <label for="CrearUsuario">Usuario</label>
                                    <input type="text" class="form-control span12" value="" id="CrearUsuario" name="CrearUsuario">
                                </div>
                            </div>                            
                            <div class="col-md-8  col-md-offset-2">
                                <div class="form-group">
                                    <label for="CrearNombre">Nombre</label>
                                    <input type="text" class="form-control span12" value="" id="CrearNombre" name="CrearNombre">
                                </div>
                            </div>   
                            <div class="col-md-4 col-md-offset-2" style="border-right: 2px dashed;">
                                <div class="form-group">
                                    <label for="CrearPass1">Contraseña</label>
                                    <input type="password" class="form-control span12" value="" id="CrearPass1" name="CrearPass1">
                                </div>
                                <div class="form-group">
                                    <label for="CrearPass2">Confirme Contraseña</label>
                                    <input type="password" class="form-control span12" value="" id="CrearPass2" name="CrearPass2">
                                </div>
                            </div>  

                            <div class="col-md-4 col-md-offset-0">
                                <div class="form-group">
                                    <label for="CrearTipoDoc">Tipo Documento</label>
                                    <select name="CrearTipoDoc" id="CrearTipoDoc" class="form-control span12" >
                                        <option value=""></option>
                                        <?php
                                        foreach ($Lista1 as $item):
                                            echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>                             
                                </div>
                                <div class="form-group">
                                    <label for="CrearDocumento">Documento</label>
                                    <input type="number" class="form-control span12" min="0" value="" id="CrearDocumento" name="CrearDocumento">
                                </div>
                            </div>   
                            <div class="col-md-4 col-md-offset-2">
                                <div class="form-group">
                                    <label for="CrearPerfil">Perfil</label>
                                    <select name="CrearPerfil" id="CrearPerfil" class="form-control span12" >
                                        <option value=""></option>
                                        <?php
                                        foreach ($Lista2 as $item):
                                            echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>   
                            <div class="col-md-4 col-md-offset-0">
                                <div class="form-group">
                                    <label for="CrearEstado">Estado</label>
                                    <select name="CrearEstado" id="CrearEstado" class="form-control span12" >
                                        <option value=""></option>
                                        <?php
                                        foreach ($Lista3 as $item):
                                            echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>   
                            <div class="col-md-4 col-md-offset-2">
                                <div class="form-group">
                                    <label for="CrearCambioPass">Cambio Contraseña</label>
                                    <select name="CrearCambioPass" id="CrearCambioPass" class="form-control span12" >
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>                                        
                                    </select>
                                </div>
                            </div>   
                            <div class="col-md-4 col-md-offset-0">
                                <div class="form-group">
                                    <label for="Administrador">Administrador</label>
                                    <select name="Administrador" id="Administrador" class="form-control span12" >
                                        <?php
                                        foreach ($ListaAdmin as $item):
                                            echo '<option value="' . $item['Cod'] . '">' . $item['Nombre'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>

                                </div>
                            </div> 
                        </div> 
                        <div class="row">
                            <div class="col-md-12" style="display:flex;justify-content:center;">
                                <button type="submit" class="btn btn-primary" id="btnCrearUsuario">
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
            $('#<?= $Controller; ?>').DataTable({
                responsive: true,
                scrollX: true,
                language: {
                    url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                }
            });
            $('#frmCrearUsuario').submit(function (e) {
                e.preventDefault();
                crearusuario();
            });
            $('#btnCrearUsuario').click(function (e) {
                e.preventDefault();
                crearusuario();
            });
            function crearusuario() {
                $('#message').html("");
                var user_user = $('form[name=frmCrearUsuario] input[name=CrearUsuario]')[0].value.trim();
                var user_pass1 = $('form[name=frmCrearUsuario] input[name=CrearPass1]')[0].value.trim();
                var user_pass2 = $('form[name=frmCrearUsuario] input[name=CrearPass2]')[0].value.trim();
                var user_name = $('form[name=frmCrearUsuario] input[name=CrearNombre]')[0].value.trim();
                var e_tipdoc = document.getElementById("CrearTipoDoc");
                var user_tipdoc = e_tipdoc.options[e_tipdoc.selectedIndex].value;
                var user_docu = $('form[name=frmCrearUsuario] input[name=CrearDocumento]')[0].value.trim();
                var e_perf = document.getElementById("CrearPerfil");
                var user_perf = e_perf.options[e_perf.selectedIndex].value;
                var e_est = document.getElementById("CrearEstado");
                var user_est = e_est.options[e_est.selectedIndex].value;
                var e_camPass = document.getElementById("CrearCambioPass");
                var user_camPass = e_camPass.options[e_camPass.selectedIndex].value;
                var Administrador = $('#Administrador').val();

                if (user_user.toString().length <= 0) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />Digite el Usuario para continuar\n\
                            </div>');
                } else {
                    if (user_pass1.toString().length <= 0) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />Digite la Contraseña para continuar\n\
                                </div>');
                    } else {
                        if (user_pass2.toString().length <= 0) {
                            $('#message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Confirme la Contraseña para continuar\n\
                                    </div>');
                        } else {
                            var regla = validarpass(user_pass1.toString());
                            if (regla.toString().length > 0) {
                                $('#message').html(
                                        '<div class="alert alert-danger alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />La Contraseña ' + regla + '\n\
                                    </div>');
                            } else {
                                if (user_pass2.toString() !== user_pass1.toString()) {
                                    $('#message').html(
                                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Las contraseñas no Coinciden\n\
                                    </div>');
                                } else {
                                    if (user_name.toString().length <= 0) {
                                        $('#message').html(
                                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />Digite el Nombre para continuar\n\
                                        </div>');
                                    } else {
                                        if (user_tipdoc.toString().length <= 0) {
                                            $('#message').html(
                                                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                <strong>Error</strong><br />Seleccione el Tipo de Documento para continuar\n\
                                            </div>');
                                        } else {
                                            if (user_docu.toString().length <= 0) {
                                                $('#message').html(
                                                        '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                    <strong>Error</strong><br />Digite el Documento para continuar\n\
                                                </div>');
                                            } else {
                                                if (user_perf.toString().length <= 0) {
                                                    $('#message').html(
                                                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                        <strong>Error</strong><br />Seleccione el Perfil para continuar\n\
                                                    </div>');
                                                } else {
                                                    if (user_est.toString().length <= 0) {
                                                        $('#message').html(
                                                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                            <strong>Error</strong><br />Seleccione el Estado para continuar\n\
                                                        </div>');
                                                    } else {
                                                        user_camPass = (user_camPass != 0) ? 1 : 0;
                                                        var method = "<?= base_url() . 'Mantenimiento/Usuarios/NewUser/'; ?>";
                                                        $("body").css({
                                                            'cursor': 'wait'
                                                        })

                                                        $.ajax({
                                                            type: 'POST',
                                                            url: method,
                                                            data: {user_user: user_user, user_pass1: user_pass1, user_pass2: user_pass2, user_name: user_name, user_tipdoc: user_tipdoc, user_docu: user_docu, user_perf: user_perf, user_est: user_est, user_camPass: user_camPass, Administrador: Administrador},
                                                            cache: false,
                                                            beforeSend: function () {
                                                                $('#message').html("");
                                                                $("#btnCrearUsuario").html("Guardando...");
                                                            },
                                                            success: function (data) {
                                                                $("#btnCrearUsuario").html('<i class="fa fa-save"></i>&nbsp; Guardar');
                                                                if (data == 1) {
                                                                    $('#message').html(
                                                                            '<div class="alert alert-success alert-dismissable fade in">\n\
                                                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                                        <strong>El usuario \"<b>' + user_user + '</b>\" fue creado exitosamente</strong>\n\
                                                                    </div>');
                                                                    alert('El usuario \"' + user_user + '\" fue creado exitosamente');
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
                                    }
                                }
                            }
                        }
                    }
                }



            }

            function validarpass(pswd) {
                var regla = "";
                if (!pswd.match(/\d/)) {
                    regla = "debe tener al menos un número";
                }
                if (!pswd.match(/[A-Z]/)) {
                    regla = "debe tener al menos una letra mayúscula";
                }
                if (!pswd.match(/[a-z]/)) {
                    regla = "debe tener al menos una letra minúscula";
                }
                if (pswd.length < 8) {
                    regla = "debe tener al menos 8 caracteres";
                }

                return regla;
            }
        });
    </script>
