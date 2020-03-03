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
                        <form>
                            <div class="col-md-3 col-md-offset-2">
                                <div class="form-group">
                                    <label>Fecha Inicial</label>
                                    <input type="text" class="form-control datepicker1" id="FechaIni" name="FechaIni" style="background-color: #ffffff;">
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha Final</label>
                                    <input type="text" class="form-control datepicker1" id="FechaFin" name="FechaFin" style="background-color: #ffffff;">
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Filtrar</label>
                                    <div class="btn-toolbar list-toolbar">
                                        <button id="btn-filtro" name="btn-filtro" class="btn btn-primary"><i class="fa fa-search"></i> Filtrar Fechas</button>
                                    </div>
                                </div>
                            </div> 
                        </form>
                    </div>
                    <div class="col-md-12">
                        <p>A continuación, se indica la cantidad de Clientes referente a los estados que pueden adquirir en el sistema.</p>
                    </div>
                    <div class="col-md-6 col-md-offset-3"> 
                        <table style="border:none;width:100%;">
                            <tbody> 
                                <tr>
                                    <td>
                                        <div style="color:#1354d2;font-size:16px;padding:10px;">
                                            Clientes Nuevos: 
                                            <div class="pull-right">
                                                <label id="contadorClienteNuevo" style="font-weight:bold;"><?= $Clientes["Nuevo"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#1354d2;font-size:16px;padding:10px;">
                                            Clientes Al día: 
                                            <div class="pull-right">
                                                <label id="contadorCliente1" style="font-weight:bold;"><?= $Clientes["Aldía"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#2863d4;font-size:16px;padding:10px;">
                                            Clientes Deben: 
                                            <div class="pull-right">
                                                <label id="contadorCliente2" style="font-weight:bold;"><?= $Clientes["Deben"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>                                    
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#4679da;font-size:16px;padding:10px;">
                                            Clientes En mora: 
                                            <div class="pull-right">
                                                <label id="contadorCliente3" style="font-weight:bold;"><?= $Clientes["Mora"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#6793e6;font-size:16px;padding:10px;">
                                            Clientes Datacrédito: 
                                            <div class="pull-right">
                                                <label id="contadorCliente4" style="font-weight:bold;"><?= $Clientes["dataC"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#7590d6;font-size:16px;padding:10px;">
                                            Clientes Reportados: 
                                            <div class="pull-right">
                                                <label id="contadorCliente5" style="font-weight:bold;"><?= $Clientes["Reportados"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#889ec5;font-size:16px;padding:10px;">
                                            Clientes Paz y Salvo: 
                                            <div class="pull-right">
                                                <label id="contadorCliente6" style="font-weight:bold;"><?= $Clientes["Paz"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#7789ad;font-size:16px;padding:10px;">
                                            Clientes Registrados: 
                                            <div class="pull-right">
                                                <label id="contadorCliente7" style="font-weight:bold;"><?= $Clientes["Registrados"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div style="color:#545b69;font-size:16px;padding:10px;">
                                            Clientes Eliminados: 
                                            <div class="pull-right">
                                                <label id="contadorCliente8" style="font-weight:bold;"><?= $Clientes["Eliminados"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>                            
                </div>
            </div>
        </div>  
        <script>
            $(document).ready(function () {
                var todayDate = new Date().getDate();
                var HomeDate = new Date(2018, 5 - 1, 30, 0, 0, 0);
                $('.datepicker1').datetimepicker({
                    format: 'DD/MM/YYYY',
                    locale: 'es',
                    minDate: HomeDate,
                    maxDate: new Date(new Date().setDate(todayDate))
                });

                $('#btn-filtro').click(function (e) {
                    e.preventDefault();
                    filtrar();
                });
            });

            function filtrar() {
                var pag_fec1 = $('#FechaIni').val();
                var pag_fec2 = $('#FechaFin').val();
                
                $('#message').html("");
                var method = "<?= base_url(); ?>Clientes/ConteoClientesPost/";
                $("body").css({
                    'cursor': 'wait'
                })
                $.ajax({
                    type: 'post',
                    url: method,
                    data: {
                        pag_fec1: pag_fec1, pag_fec2: pag_fec2
                    },
                    cache: false,
                    beforeSend: function () {
                        $('#message').html("");
                        $('#btn-filtro').html('<i class="fa fa-search"></i> Filtrando...');
                    },
                    success: function (data) {
                        $('#btn-filtro').html('<i class="fa fa-search"></i> Filtrar Fechas');
                        var clientes = JSON.parse(data);
                        
                        $('#contadorClienteNuevo').text(clientes.Nuevo);
                    }

                });
                $("body").css({
                    'cursor': 'Default'
                })

                return false;
            }
        </script>
