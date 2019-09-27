$(document).on("ready", main);

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
            var method = "<?php echo base_url(); ?>"+$(this).attr("method");
            $("body").css({
                'cursor': 'wait'
            })
            $.ajax({
                type: $(this).attr("action"),
                url: method,
                data: {user_name: user_name, user_pass: user_pass},
                cache: false,
                beforeSend: function () {
                    $('#message').html("");
                    $("#btnLogin").text("Conectando...");
                },
                success: function (data) {
                    alert(data);
//                    $("#btnLogin").text("Iniciar");
//                    if (data == 1) {
//                        $('#message').html(
//                                '<div class="alert alert-success alert-dismissable fade in">\n\
//                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
//                                        <strong>¡Bienvenido! <b>' + user_name + '</b></strong>\n\</div>');
//                        //location.href = <?= base_url(); ?>;
//                    } else {
//                        $('#message').html(
//                                '<div class="alert alert-danger alert-dismissable fade in">\n\
//                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
//                                        <strong>Error</strong><br />' + data + '\n\
//                                    </div>');
//                    }
                }
            });
            $("body").css({
                'cursor': 'Default'
            })

            return false;
        }
    }
}
