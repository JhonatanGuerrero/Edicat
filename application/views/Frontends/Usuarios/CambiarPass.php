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
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form id="login" name="login" method="POST" role="form">
                                    <div class="form-group hidden">
                                        <label for="codUserLogin">Codigo</label>
                                        <input type="text" class="form-control span12" value="<?= $cod; ?>" id="codUserLogin" name="codUserLogin" readonly style="background-color: #ffffff;">
                                    </div>
                                    <div class="form-group">
                                        <label for="userLogin">Usuario</label>
                                        <input type="text" class="form-control span12" value="<?= $user; ?>" id="userLogin" name="userLogin" readonly style="background-color: #ffffff;">
                                    </div>
                                    <div class="form-group">
                                        <label for="passLogin">Contraseña Actual</label>
                                        <input type="password" class="form-control span12 form-control" value="" id="passLogin" name="passLogin">
                                    </div>
                                    <div class="form-group">
                                        <label for="passLoginNew1">Contraseña Nueva</label>
                                        <input type="password" class="form-control span12 form-control" value="" id="passLoginNew1" name="passLoginNew1">
                                    </div>
                                    <div class="form-group">
                                        <label for="passLoginNew2">Confirme Nueva Contraseña</label>
                                        <input type="password" class="form-control span12 form-control" value="" id="passLoginNew2" name="passLoginNew2">
                                    </div>
                                    <button id="btnChange" name="btnChange" class="btn btn-lg btn-primary btn-block"><i class="fa fa-key"></i>&nbsp; Cambiar Contraseña</button>                                    
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="message">
                                <?php
                                if ($this->session->flashdata("error")) {
                                    echo '<div class="alert alert-danger" role="alert">' . $this->session->flashdata("error") . '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>
        </div>  
        <script>
            $(document).ready(function () {
                $('#passLogin').focus();
                $('#btnChange').click(function (e) {
                    e.preventDefault();
                    CambioContraseña();
                });
            });

            function CambioContraseña() {
                var codigo = $('#codUserLogin').val();
                var usuario = $('#userLogin').val();
                var passAct = $('#passLogin').val();
                var passNew1 = $('#passLoginNew1').val();
                var passNew2 = $('#passLoginNew2').val();

                if (usuario.toString().length <= 0) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />Debe Indicar Usuario\n\
                            </div>');
                } else {
                    if (passAct.toString().length <= 0) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />Debe Indicar la Contraseña Actual\n\
                                </div>');
                    } else {
                        if (passNew1.toString().length <= 0) {
                            $('#message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Debe Indicar una Nueva Contraseña\n\
                                    </div>');
                        } else {
                            if (passNew2.toString().length <= 0) {
                                $('#message').html(
                                        '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />Debe Confirmar la Contraseña Nueva\n\
                                        </div>');
                            } else {
                                var regla = validarpass(passNew1.toString());
                                if (regla.toString().length > 0) {
                                    $('#message').html(
                                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />La Contraseña ' + regla + '\n\
                                        </div>');
                                } else {
                                    if (passNew2 != passNew1) {
                                        $('#message').html(
                                                '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                   <strong>Error</strong><br />Las Contraseñas No Coinciden\n\
                                               </div>');
                                    } else {
                                        if (passAct == passNew1) {
                                            $('#message').html(
                                                    '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                   <strong>Error</strong><br />La Nueva Contraseña y la Actual son la misma\n\
                                               </div>');
                                        } else {
                                            $('#message').html("");
                                            var method = "<?= base_url(); ?>Mantenimiento/Usuarios/ChangePassUser/";
                                            $("body").css({
                                                'cursor': 'wait'
                                            })
                                            $.ajax({
                                                type: 'post',
                                                url: method,
                                                data: {
                                                    codigo: codigo, usuario: usuario, passAct: passAct, passNew1: passNew1, passNew2: passNew2
                                                },
                                                cache: false,
                                                beforeSend: function () {
                                                    $('#message').html("");
                                                    $('#btnChange').html('<i class="fa fa-key"></i>&nbsp; Cambiando Pass...');
                                                },
                                                success: function (data) {
                                                    $('#btnChange').html('<i class="fa fa-key"></i>&nbsp; Cambiar Contraseña');
                                                    if (data == 1) {
                                                        $('#message').html(
                                                                '<div class="alert alert-success alert-dismissable fade in">\n\
                                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                                    <strong>Se cambió la Contraseña Exitosamente</strong>\n\
                                                                </div>');
                                                        location.href = "<?= base_url("Mantenimiento/Usuarios/Consultar/") . $cod . "/"; ?>";
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
        </script>