<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es-co">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Plataforma">
        <meta name="author" content="Jhonatan Guerrero">
        <title><?= $title; ?></title>
        <link rel="icon" type="image/png" href="<?= base_url('Public'); ?>/images/logo/01.png"/>

        <!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>-->
        <link href="<?= base_url('Public'); ?>/assets/lib/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url('Public'); ?>/assets/lib/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url('Public'); ?>/assets/lib/font-awesome/4.3/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?= base_url('Public'); ?>/assets/stylesheets/theme.css" rel="stylesheet" type="text/css" >
        <link href="<?= base_url('Public'); ?>/assets/stylesheets/premium.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url('Public'); ?>/assets/lib/Datetables.js/datatables.min.css" rel="stylesheet" type="text/css"/>        
        <link href="<?= base_url('Public'); ?>/assets/lib/bootstrap-datetimepicker/dist/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
        <link href="<?= base_url('Public'); ?>/assets/stylesheets/own.css" rel="stylesheet" type="text/css"/>
        <link href="<?= base_url('Public'); ?>/assets/lib/formToWizard/css/jquery.steps.css" rel="stylesheet" type="text/css"/>
        <link href="<?= base_url('Public'); ?>/assets/stylesheets/jquery-ui.css" rel="stylesheet" type="text/css"/>

        <style>
            @font-face {
                font-family: "blackjack";
                src: url("<?= base_url('Public'); ?>/assets/fonts/blackjack.otf");
            }
            @font-face {
                font-family: "Yesteryear";
                src: url("<?= base_url('Public'); ?>/assets/fonts/Yesteryear-Regular.ttf");
            }
            .ui-widget {
                font-family: Arial,Helvetica,sans-serif;
                font-size: 1em;
            }
            .ui-widget .ui-widget {
                font-size: 1em;
            }
            .ui-widget input,
            .ui-widget select,
            .ui-widget textarea,
            .ui-widget button {
                font-family: Arial,Helvetica,sans-serif;
                font-size: 1em;
            }
            .ui-widget.ui-widget-content {
                border: 1px solid #c5c5c5;
            }
            .ui-widget-content {
                border: 1px solid #dddddd;
                background: #ffffff;
                color: #333333;
            }
            .ui-widget-content a {
                color: #333333;
            }
            .ui-widget-header {
                border: 1px solid #dddddd;
                background: #e9e9e9;
                color: #333333;
                font-weight: bold;
            }
            .ui-widget-header a {
                color: #333333;
            }
        </style>


        <script src="<?= base_url('Public'); ?>/assets/lib/jquery-1.11.1.min.js" type="text/javascript"></script>
        <script src="<?= base_url('Public'); ?>/assets/lib/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?= base_url('Public'); ?>/assets/lib/char.js/dist/Chart.bundle.js" type="text/javascript"></script>
        <script src="<?= base_url('Public'); ?>/assets/lib/char.js/dist/utils.js" type="text/javascript"></script>
        <script src="<?= base_url('Public'); ?>/assets/lib/Datetables.js/datatables.min.js" type="text/javascript"></script>
        <script src="<?= base_url('Public'); ?>/assets/lib/bootstrap/js/bootstrap.js"></script>
        <script src="<?= base_url('Public'); ?>/assets/lib/bootstrap-datetimepicker/moment/moment.js"></script>
        <script src="<?= base_url('Public'); ?>/assets/lib/bootstrap-datetimepicker/dist/js/bootstrap-datetimepicker.min.js"></script>
        <script src="<?= base_url('Public'); ?>/assets/lib/bootstrap-datetimepicker/dist/js/es.js"></script>
        <script src="<?= base_url('Public'); ?>/assets/lib/formToWizard/jquery.formtowizard.js"></script>
        <script src="<?= base_url('Public'); ?>/Js/utilidades.js" type="text/javascript"></script>
        <script src="<?= base_url('Public'); ?>/assets/lib/formToWizard/jquery.steps.min.js" type="text/javascript"></script>
        <!--<script src="<?= base_url('Public'); ?>/assets/lib/jquery-ui.js" type="text/javascript"></script>-->


        <script src="<?= base_url('Public'); ?>/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="<?= base_url('Public'); ?>/amcharts/pie.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(function () {
                var match = document.cookie.match(new RegExp('color=([^;]+)'));
                if (match)
                    var color = match[1];
                if (color) {
                    $('body').removeClass(function (index, css) {
                        return (css.match(/\btheme-\S+/g) || []).join(' ')
                    })
                    $('body').addClass('theme-' + color);
                }

                $('[data-popover="true"]').popover({html: true});

            });
        </script>
        <script type="text/javascript">
            $(function () {
                var uls = $('.sidebar-nav > ul > *').clone();
                uls.addClass('visible-xs');
                $('#main-menu').append(uls.clone());
            });
        </script>
        <script type="text/javascript">
            $("[rel=tooltip]").tooltip();
            $(function () {
                $('.demo-cancel-click').click(function () {
                    return false;
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $('.datepicker').datetimepicker({
                    format: 'DD/MM/YYYY',
                    locale: 'es'
                });
                $('.datepickerMes').datetimepicker({
                    format: 'MM/YYYY',
                    locale: 'es'
                });
                $('.datetimepicker').datetimepicker({
                    format: 'DD/MM/YYYY HH:mm:00',
                    locale: 'es'
                });
                document.body.style.cursor = 'wait';
            });
            window.onload = function () {
                document.body.style.cursor = 'default';
            }
        </script>
        <style>
            .wrap { max-width: 980px; margin: 10px auto 0; }
            #steps { margin: 80px 0 0 0 }
            .commands { overflow: hidden; margin-top: 30px; }
            .prev {float:left}
            .next, .submit {float:right}
            .error { color: #b33; }
            #progress { position: relative; height: 5px; background-color: #eee; margin-bottom: 20px; }
            #progress-complete { border: 0; position: absolute; height: 5px; min-width: 10px; background-color: #337ab7; transition: width .2s ease-in-out; }
        </style>
    </head>
    <body class=" theme-blue">