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
                        <div class="btn-toolbar list-toolbar">
                            <a href="<?= base_url() . "Mantenimiento/Usuarios/Admin/" ?>" class="btn btn-danger"><i class="fa fa-undo"></i> Volver a Usuarios</a>
                        </div>
                    </div>  
                </div>   
                <div class="btn-toolbar list-toolbar">
                    <form id="frmActualizarUsuario" name="frmActualizarUsuario" method="POST" role="form">
                        <div class="row">
                            <div class="col-md-4 col-md-offset-2">
                                <div class="form-group hidden">
                                    <label for="ActualizarCodigo">Codigo</label>
                                    <input type="text" class="form-control span12" disabled value="<?= $ListaDatos[0]['Codigo']; ?>" id="ActualizarCodigo" name="ActualizarCodigo">
                                </div>                            
                                <div class="form-group">
                                    <label for="ActualizarUsuario">Usuario</label>
                                    <input type="text" class="form-control span12" disabled value="<?= $ListaDatos[0]['Usuario']; ?>" id="ActualizarUsuario" name="ActualizarUsuario">
                                </div>
                            </div>
                            <div class="col-md-8  col-md-offset-2">
                                <div class="form-group">
                                    <label for="ActualizarNombre">Nombre</label>
                                    <input type="text" class="form-control span12" value="<?= $ListaDatos[0]['Nombre']; ?>" id="ActualizarNombre" name="ActualizarNombre">
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-2">
                                <div class="form-group">
                                    <label for="ActualizarTipoDoc">Tipo Documento</label>
                                    <select disabled name="ActualizarTipoDoc" id="ActualizarTipoDoc" class="form-control span12" >
                                        <?php
                                        foreach ($Lista1 as $item):
                                            echo '<option ' . ($item['Codigo'] == $ListaDatos[0]['TipoDocumentoId'] ? 'selected="selected"' : '') . ' value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>                             
                                </div>
                                <div class="form-group">
                                    <label for="ActualizarDocumento">Documento</label>
                                    <input type="number" class="form-control span12"disabled value="<?= $ListaDatos[0]['Documento']; ?>" id="ActualizarDocumento" name="ActualizarDocumento">
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-0">
                                <div class="form-group">
                                    <label for="ActualizarPerfil">Perfil</label>
                                    <select name="ActualizarPerfil" id="ActualizarPerfil" class="form-control span12" >
                                        <?php
                                        foreach ($Lista2 as $item):
                                            echo '<option ' . ($item['Codigo'] == $ListaDatos[0]['PerfilId'] ? 'selected="selected"' : '') . ' value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ActualizarEstado">Estado</label>
                                    <select name="ActualizarEstado" id="ActualizarEstado" class="form-control span12" >
                                        <?php
                                        foreach ($Lista3 as $item):
                                            echo '<option ' . ($item['Codigo'] == $ListaDatos[0]['EstadoId'] ? 'selected="selected"' : '') . ' value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-2">
                                <div class="form-group">
                                    <label for="ActualizarCambioPass">Cambio Contraseña</label>
                                    <select name="ActualizarCambioPass" id="ActualizarCambioPass" class="form-control span12" >
                                        <option <?= ($ListaDatos[0]['CambioPass'] == 1 ? 'selected="selected"' : ''); ?> value="1">Sí</option>
                                        <option <?= ($ListaDatos[0]['CambioPass'] == 0 ? 'selected="selected"' : ''); ?> value="0">No</option>                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-2">
                                <div class="form-group">
                                    <label for="ActualizarUsuarioCreacion">Usuario de Creación</label>
                                    <input type="text" disabled class="form-control span12" value="<?= $ListaDatos[0]['UsuarioCreacion']; ?>" id="ActualizarUsuarioCreacion" name="ActualizarUsuarioCreacion">
                                </div>
                                <div class="form-group">
                                    <label for="ActualizarFechaCreacion">Fecha de Creación</label>
                                    <input type="text" disabled class="form-control span12" value="<?= $ListaDatos[0]['FechaCreacion']; ?>" id="ActualizarFechaCreacion" name="ActualizarFechaCreacion">
                                </div>  
                            </div>
                            <div class="col-md-4 col-md-offset-0">
                                <div class="form-group">
                                    <label for="ActualizarUsuarioModificacion">Usuario de Modificación</label>
                                    <input type="text" disabled class="form-control span12" value="<?= $ListaDatos[0]['UsuarioModificacion']; ?>" id="ActualizarUsuarioModificacion" name="ActualizarUsuarioModificacion">
                                </div>
                                <div class="form-group">
                                    <label for="ActualizarFechaModificacion">Fecha de Modificación</label>
                                    <input type="text" disabled class="form-control span12" value="<?= $ListaDatos[0]['FechaModificacion']; ?>" id="ActualizarFechaModificacion" name="ActualizarFechaModificacion">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="display:flex;justify-content:center;">
                                <button type="submit" class="btn btn-primary" id="btnActualizarUsuario">
                                    <i class="fa fa-save"></i>&nbsp; Actualizar
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
            $('#frmActualizarUsuario').submit(function (e) {
                e.preventDefault();
            });
            $('#btnActualizarUsuario').click(function (e) {
                e.preventDefault();
                actualizarusuario();
            });
            function actualizarusuario() {
                $('#message').html("");
                var user_cod = $('form[name=frmActualizarUsuario] input[name=ActualizarCodigo]')[0].value.trim();
                var user_user = $('form[name=frmActualizarUsuario] input[name=ActualizarUsuario]')[0].value.trim();
                var user_name = $('form[name=frmActualizarUsuario] input[name=ActualizarNombre]')[0].value.trim();
                var e_perf = document.getElementById("ActualizarPerfil");
                var user_perf = e_perf.options[e_perf.selectedIndex].value;
                var e_est = document.getElementById("ActualizarEstado");
                var user_est = e_est.options[e_est.selectedIndex].value;
                var e_camPass = document.getElementById("ActualizarCambioPass");
                var user_camPass = e_camPass.options[e_camPass.selectedIndex].value;
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
                                    var method = "<?= base_url() . 'Mantenimiento/Usuarios/UpdateUser/'; ?>";
                                    $("body").css({
                                        'cursor': 'wait'
                                    })

                                    $.ajax({
                                        type: 'POST',
                                        url: method,
                                        data: {user_cod: user_cod, user_user: user_user, user_name: user_name, user_perf: user_perf, user_est: user_est, user_camPass: user_camPass},
                                        cache: false,
                                        beforeSend: function () {
                                            $('#message').html("");
                                            $("#btnActualizarUsuario").html("Guardando...");
                                        },
                                        success: function (data) {
                                            $("#btnActualizarUsuario").html('<i class="fa fa-save"></i>&nbsp; Actualizar');
                                            if (data == 1) {
                                                $('#message').html(
                                                        '<div class="alert alert-success alert-dismissable fade in">\n\
                                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                            <strong>El usuario \"<b>' + user_user + '</b>\" fue actualizado exitosamente</strong>\n\
                                                        </div>');
                                                alert('El usuario \"' + user_user + '\" fue actualizado exitosamente');
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
        }
        );
    </script>
