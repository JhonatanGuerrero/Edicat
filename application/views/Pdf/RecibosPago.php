<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es-co">
    <head>
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Plataforma">
        <meta name="author" content="Jhonatan Guerrero">
        <title>Recibos de Caja</title>
        <link rel="icon" type="image/png" href="<?= base_url('Public'); ?>/images/logo/01.png"/>

        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href="<?= base_url('Public'); ?>/assets/lib/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url('Public'); ?>/assets/lib/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url('Public'); ?>/assets/lib/font-awesome/4.3/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?= base_url('Public'); ?>/assets/stylesheets/theme.css" rel="stylesheet" type="text/css" >
        <link href="<?= base_url('Public'); ?>/assets/stylesheets/premium.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url('Public'); ?>/assets/lib/Datetables.js/datatables.min.css" rel="stylesheet" type="text/css"/>        
        <link href="<?= base_url('Public'); ?>/assets/lib/bootstrap-datetimepicker/dist/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
        <link href="<?= base_url('Public'); ?>/assets/stylesheets/own.css" rel="stylesheet" type="text/css"/>
        <link href="<?= base_url('Public'); ?>/assets/lib/formToWizard/css/jquery.steps.css" rel="stylesheet" type="text/css"/>

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

       
        <style>*{color:#2196f3;}p{margin:0px;padding:0px;color:black;}</style>
    </head>
    <body>
        <table style="border:2px solid #2196f3;border-radius:2px;width:500px;">
            <tr>
                <td style="border:none;width:70px;">
                    <img src="<?= base_url();?>/Public/images/logo/01.png" width="80">                    
                </td>
                <td style="border:none;vertical-align:top;">
                    <img src="<?= base_url();?>/Public/images/logo/02.png" width="70" style="">
                    <img src="<?= base_url();?>/Public/images/logo/03.png" width="70" style="">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table style="border:2px solid #2196f3;border-radius:5px;width:500px;">
                        <tr style="border:none;">
                            <td style="width:70px;height:20px;">
                                Fecha:
                            </td>
                            <td style="border-bottom:1px solid;max-height:20px;">
                                <p>10-07-2018</p> 
                            </td>
                        </tr>
                        <tr style="border:none;">
                            <td style="width:70px;">
                                Nombre: 
                            </td>
                            <td style="border-bottom:1px solid;">
                                <p style="font-family:blackjack;">Gabriela fantilla Sanabria</p>
                            </td>
                        </tr>
                        <tr style="border:none;">
                            <td style="width:70px;">
                                Dirección: 
                            </td>
                            <td style="border-bottom:1px solid;">
                                <p>Tr 76 # 14-12</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table style="border:2px solid #2196f3;border-radius:5px;width:500px;">
                        <tr>
                            <td>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
    </body>
</html>