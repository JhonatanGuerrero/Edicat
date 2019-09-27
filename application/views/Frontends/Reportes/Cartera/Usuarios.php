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
                    <div class="col-md-12">
                        <form id="form-fileup" name="form-fileup">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Usuario</label>
                                    <select id="dropUsuario" name="dropUsuario" class="form-control" >
                                        <option value="*">Todos</option>
                                        <?php
                                        foreach ($ListaUsuarios as $value) {
                                            ?>
                                            <option value="<?= $value["Usuario"]; ?>"><?= $value["Nombre"]; ?></option>
                                            <?php
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Fecha Inicial</label>
                                    <input type="text" id="FechaIni" name="FechaIni" class="form-control datepicker1" required style="background-color: #ffffff;">
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Fecha Final</label>
                                    <input type="text" id="FechaFin" name="FechaFin" class="form-control datepicker1" required style="background-color: #ffffff;">
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Filtrar</label>
                                    <div class="btn-toolbar list-toolbar">
                                        <button id="btn-filtrar" name="btn-filtrar" class="btn btn-primary"><i class="fa fa-search"></i> Filtrar Fechas</button>                                            
                                    </div>    
                                </div>
                            </div>
                        </form>
                    </div>
                </div> 
                <br>
                <div class="col-md-12">
                    <p>A continuación, se indica la lo recaudado dependiendo de la gestión del Usuario seleccionado.</p>
                </div>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4"> 
                        <table style="border:none;width:100%;">
                            <tbody> 
                                <tr>
                                    <td>
                                        <div style="color:#1354d2;font-size:16px;padding:10px;">
                                            Pagos Recaudados: 
                                            <div class="pull-right">
                                                <label id="contadorPago1" style="font-weight:bold;"><?= $ListaPagos["pag"]["numPag"]; ?></label>
                                            </div>
                                        </div>  
                                        <div style="color:#1354d2;font-size:16px;padding:10px;">
                                            Valor Recaudado: 
                                            <div class="pull-right">
                                                <label id="contadorPago2" style="font-weight:bold;"><?= $ListaPagos["pag"]["valorPag"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <hr>
                                    </td>
                                </tr><tr>
                                    <td>
                                        <div style="color:#5c429a;font-size:16px;padding:10px;">
                                            Pagos Programados: 
                                            <div class="pull-right">
                                                <label id="contadorPago3" style="font-weight:bold;"><?= $ListaPagos["pagopro"]["numPagPro"]; ?></label>
                                            </div>
                                        </div>  
                                        <div style="color:#5c429a;font-size:16px;padding:10px;">
                                            Valor Estimado a Cobrar: 
                                            <div class="pull-right">
                                                <label id="contadorPago4" style="font-weight:bold;"><?= $ListaPagos["pagopro"]["valorPagPro"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <hr>
                                    </td>
                                </tr><tr>
                                    <td>
                                        <div style="color:#545b69;font-size:16px;padding:10px;">
                                            Pagos Descartados: 
                                            <div class="pull-right">
                                                <label id="contadorPago5" style="font-weight:bold;"><?= $ListaPagos["pagodes"]["numPagDes"]; ?></label>
                                            </div>
                                        </div>
                                        <div style="color:#545b69;font-size:16px;padding:10px;">
                                            Valor Descartado: 
                                            <div class="pull-right">
                                                <label id="contadorPago6" style="font-weight:bold;"><?= $ListaPagos["pagodes"]["valorPagDes"]; ?></label>
                                            </div>
                                        </div>                                       
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <a href="#pageDetails" class="panel-heading" data-toggle="collapse">Detalles de Pago</a>
                            <div id="pageDetails" class="panel-collapse panel-body collapse in">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center;margin:0px auto;">Pedido</th>
                                                    <th style="text-align:center;margin:0px auto;">Cliente</th>
                                                    <th style="text-align:center;margin:0px auto;">Valor</th>
                                                    <th style="text-align:center;margin:0px auto;">Estado</th>
                                                    <th style="text-align:center;margin:0px auto;">Fecha</th>
                                                    <th style="text-align:center;margin:0px auto;">Usuario</th>                                                    
                                                    <th style="text-align:center;margin:0px auto;">Ver</th>
                                                </tr>
                                            </thead>
                                        </table>
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
                $('#message').html("");
                var todayDate = new Date();
                var HomeDate = new Date(2018, 6 - 1, 1, 0, 0, 0);
                var MaxDate = new Date();
                MaxDate.setDate(MaxDate.getDate() + 10);

                $('.datepicker1').datetimepicker({
                    format: 'DD/MM/YYYY',
                    locale: 'es',
                    defaultDate: todayDate,
                    minDate: HomeDate,
                    maxDate: MaxDate
                });

                $('#btn-filtrar').click(function (e) {
                    e.preventDefault();
                    dataPagos();
                    filtrar();
                });

                listar();

            });

            function listar() {
                $('#<?= $Controller; ?>').DataTable({
                    bDestroy: true,
                    responsive: true,
                    scrollX: true,
                    bSort: false,
                    columns: [
                        {data: "pedido"},
                        {data: "cliente"},
                        {data: "pago"},
                        {data: "estado"},
                        {data: "fecha"},
                        {data: "usuario"},
                        {data: "btn"}
                    ],
                    ajax: {
                        method: 'post',
                        url: "<?= base_url(); ?>Reportes/reportesUsuarios/"
                    },
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    }
                });
            }

            function filtrar() {
                var pag_usu = $('#dropUsuario').val();
                var pag_fec1 = $('#FechaIni').val();
                var pag_fec2 = $('#FechaFin').val();
                
                $('#<?= $Controller; ?>').DataTable({
                    bDestroy: true,
                    responsive: true,
                    scrollX: true,
                    bSort: false,
                    columns: [
                        {data: "pedido"},
                        {data: "cliente"},
                        {data: "pago"},
                        {data: "estado"},
                        {data: "fecha"},
                        {data: "usuario"},
                        {data: "btn"}
                    ],
                    ajax: {
                        method: 'post',
                        url: "<?= base_url(); ?>Reportes/reportesUsuariosFiltro/",
                        data: {
                            pag_usu: pag_usu, pag_fec1: pag_fec1, pag_fec2: pag_fec2
                        }
                    },
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    }
                });
            }

            function dataPagos() {
                var pag_usu = $('#dropUsuario').val();
                var pag_fec1 = $('#FechaIni').val();
                var pag_fec2 = $('#FechaFin').val();
                
                $('#message').html("");
                var method = "<?= base_url(); ?>Reportes/datosCarteraUsuariosPost/";
                $("body").css({
                    'cursor': 'wait'
                })
                $.ajax({
                    type: 'post',
                    url: method,
                    data: {
                        pag_usu: pag_usu, pag_fec1: pag_fec1, pag_fec2: pag_fec2
                    },
                    cache: false,
                    beforeSend: function () {
                        $('#message').html("");
                        $('#btn-filtro').html('<i class="fa fa-search"></i> Filtrando...');
                    },
                    success: function (data) {
                        $('#btn-filtro').html('<i class="fa fa-search"></i> Filtrar Fechas');
                        var pagos = JSON.parse(data);

                        $('#contadorPago1').text(pagos.pag.numPag);
                        $('#contadorPago2').text(pagos.pag.valorPag);
                        $('#contadorPago3').text(pagos.pagopro.numPagPro);
                        $('#contadorPago4').text(pagos.pagopro.valorPagPro);
                        $('#contadorPago5').text(pagos.pagodes.numPagDes);
                        $('#contadorPago6').text(pagos.pagodes.valorPagDes);
                    }

                });
                $("body").css({
                    'cursor': 'Default'
                })

                return false;
            }
        </script>
