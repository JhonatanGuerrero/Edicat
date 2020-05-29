<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="dialog">
    <div class="panel panel-default">
        <p class="panel-heading no-collapse text-center">Iniciar Sesión</p>
        <div class="panel-body">
            <form id="login" name="login" method="POST" role="form">
                <div class="form-group">
                    <label for="userLogin">Usuario</label>
                    <input type="text" class="form-control span12" value="" id="userLogin" name="userLogin">
                </div>
                <div class="form-group">
                    <label for="passLogin">Contraseña</label>
                    <input type="password" class="form-control span12 form-control" value="" id="passLogin" name="passLogin">
                </div>
                <input type="submit" class="btn btn-lg btn-primary btn-block" id="btnLogin" name="btnLogin" value="Iniciar" />
                <!--<label class="remember-me"><input type="checkbox"> Remember me</label>-->
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
    <p class="pull-right">Desarrollado para Ediciones Católicas - <?= date("Y"); ?></p>
<!--    <p><a href="#">¿Olvidó su Contraseña?</a></p>-->
    <br>
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
<script>
    $(document).on("ready", main);
    $(document).ready(function () {
        //main();
        $('#userLogin').focus();
    });
    function main() {
        $('#login').submit(function (e) {
            e.preventDefault();
            login();
        });
        $('#btnLogin').click(function (e) {
            e.preventDefault();
            login();
        });
    }
    function login() {
        $('#message').html("");
        var user_name = $('form[name=login] input[name=userLogin]')[0].value.trim();
        var user_pass = $('form[name=login] input[name=passLogin]')[0].value.trim();
        if (user_name.toString().length <= 0) {
            $('#message').html(
                    '<div class="alert alert-danger alert-dismissable fade in">\n\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                        <strong>Error</strong><br />Digite Usuario para continuar\n\
                    </div>');
        } else {
            if (user_pass.toString().trim().length <= 0) {
                $('#message').html(
                        '<div class="alert alert-danger alert-dismissable fade in">\n\
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                            <strong>Error</strong><br />Digite Contraseña para continuar\n\
                        </div>');
            } else {
                var method = "<?= base_url() . 'Login/signIn/'; ?>";
                $("body").css({
                    'cursor': 'wait'
                })

                $.ajax({
                    type: 'POST',
                    url: method,
                    data: {user_name: user_name, user_pass: user_pass},
                    cache: false,
                    beforeSend: function () {
                        $('#message').html("");
                        $("#btnLogin").val("Conectando...");
                    },
                    success: function (data) {
                        $("#btnLogin").val("Iniciar");
                        if (data == 1) {
                            $('#message').html(
                                    '<div class="alert alert-success alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>¡Bienvenido! <b>' + user_name + '</b></strong>\n\
                                        <ul class="fa-ul">\n\
                                            <li>Validando los Estados de los Clientes... <i class="fa-li fa fa-spinner fa-pulse"></i></li>\n\
                                        </ul>\n\
                                    </div>');
                            var uri = window.location.pathname.replace("/Login/index/", "/");
                            location.href = "<?= base_url("Pagos/Revision"); ?>" + uri;
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
</script>
