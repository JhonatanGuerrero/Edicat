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
                        <p>A continuación, se indica la cantidad de Pagos referente a los estados que pueden adquirir en el sistema.</p>
                    </div>
                    <div class="col-md-6 col-md-offset-3"> 
                        <table style="border:none;width:100%;">
                            <tbody> 
                                <tr>
                                    <td>
                                        <div style="color:#1354d2;font-size:16px;padding:10px;">
                                            Pagos o Llamadas: 
                                            <div class="pull-right">
                                                <label id="contadorPago1" style="font-weight:bold;"><?= $Pagos["Llamadas"]; ?></label>
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
                                        <div style="color:#2863d4;font-size:16px;padding:10px;">
                                            Pagos Programados o Visitas: 
                                            <div class="pull-right">
                                                <label id="contadorPago2" style="font-weight:bold;"><?= $Pagos["PagPro"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#4679da;font-size:16px;padding:10px;">
                                            Pagos Realizados o Confirmados: 
                                            <div class="pull-right">
                                                <label id="contadorPago3" style="font-weight:bold;"><?= $Pagos["Confir"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>                                    
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#6793e6;font-size:16px;padding:10px;">
                                            Pagos Descartados: 
                                            <div class="pull-right">
                                                <label id="contadorPago4" style="font-weight:bold;"><?= $Pagos["Desc"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#7590d6;font-size:16px;padding:10px;">
                                            Pagos En Espera: 
                                            <div class="pull-right">
                                                <label id="contadorPago5" style="font-weight:bold;"><?= $Pagos["NoPago"]; ?></label>
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
                                        <div style="color:#889ec5;font-size:16px;padding:10px;">
                                            Pagos Programados o Visitas (Histórico): 
                                            <div class="pull-right">
                                                <label id="contadorPago6" style="font-weight:bold;"><?= $Pagos["ProgH"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#7789ad;font-size:16px;padding:10px;">
                                            Pagos Registrados o Confirmados (Histórico): 
                                            <div class="pull-right">
                                                <label id="contadorPago7" style="font-weight:bold;"><?= $Pagos["Todos"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#5a6477;font-size:16px;padding:10px;">
                                            Pagos Descartados (Histórico): 
                                            <div class="pull-right">
                                                <label id="contadorPago8" style="font-weight:bold;"><?= $Pagos["DescH"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="color:#545b69;font-size:16px;padding:10px;">
                                            Pagos En Espera (Histórico):
                                            <div class="pull-right">
                                                <label id="contadorPago9" style="font-weight:bold;"><?= $Pagos["NoPagoH"]; ?></label>
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
                var method = "<?= base_url(); ?>Pagos/ConteoPagosPost/";
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
                        var pagos = JSON.parse(data);
                        
                        $('#contadorPago1').text(pagos.Llamadas);
                        $('#contadorPago2').text(pagos.PagPro);
                        $('#contadorPago3').text(pagos.Confir);
                        $('#contadorPago4').text(pagos.Desc);
                        $('#contadorPago5').text(pagos.NoPago);
                        $('#contadorPago6').text(pagos.ProgH);
                        $('#contadorPago7').text(pagos.Todos);
                        $('#contadorPago8').text(pagos.DescH);
                        $('#contadorPago9').text(pagos.NoPagoH);
                    }

                });
                $("body").css({
                    'cursor': 'Default'
                })

                return false;
            }
        </script>
