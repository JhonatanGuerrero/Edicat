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
            <div id="page-stats" class="panel-collapse panel-body collapse in">
                <div class="row">
                    <div class="col-md-12" id="message">
                    </div>
                </div>
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
                        <form id="form-FiltrarPagos" name="form-FiltrarPagos" method="POST" >
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
                                        <button id="btn-FiltrarPagos" name="btn-FiltrarPagos" class="btn btn-primary"><i class="fa fa-search"></i> Filtrar Fechas</button>                                            
                                    </div>                                    
                                </div>
                            </div>                            
                        </form>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Recibo de Pago</label>
                                <div class="btn-toolbar list-toolbar">
<!--                                    <button id="btn-imprimirPagos" name="btn-imprimirPagos" class="btn btn-info"><i class="fa fa-print"></i> Imprimir Recibos</button>-->
                                    <a href="#ModalPrint" data-toggle="modal" title="Imprimir Recibos" class="btn btn-info"><i class="fa fa-print"></i> Imprimir Recibos</a>
                                </div>                                    
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th style="width:300px;">Dirección</th>
                                    <th>Cuota</th>
                                    <th style="width:70px;">Saldo</th>
                                    <th style="width:70px;">Valor</th>
                                    <th style="width:70px;">Fecha Programada</th>
                                    <th style="width:70px;">Estado</th>
                                    <th style="width:150px;">Opciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>  

        <div class="modal fade " id="ModalConfirmarPago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" name="form-modal" id="form-modal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Confirmar el Pago</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row hidden">                                
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label>Codigo</label>
                                        <input type="text" id="modal-codigo-confirmar" name="modal-codigo-confirmar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-md-6 hidden">
                                    <div class="form-group">
                                        <label>Pedido</label>
                                        <input type="text" id="modal-pedido-confirmar" name="modal-pedido-confirmar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6 hidden">
                                    <div class="form-group">
                                        <label>Cliente</label>
                                        <input type="text" id="modal-cliente-confirmar" name="modal-cliente-confirmar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" id="modal-nombre-confirmar" name="modal-nombre-confirmar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Recibo de Pago</label>
                                        <input type="text" id="modal-Pago-confirmar1" name="modal-Pago-confirmar1" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>                 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Saldo Programado</label>
                                        <input type="text" id="modal-Saldo-confirmar1" name="modal-Saldo-confirmar1" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>                                
                                <div class="col-md-12 hidden">
                                    <div class="form-group">
                                        <label>Valor</label>
                                        <input type="number" id="modal-Valor-confirmar" name="modal-Valor-confirmar" class="form-control" style="background-color:#ffffff;">
                                    </div>  
                                </div>                                                                  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pago Real</label>
                                        <input type="number" id="modal-Pago-confirmar" name="modal-Valor-confirmar" class="form-control" style="background-color:#ffffff;">
                                    </div>  
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Saldo Real</label>
                                        <input type="text" id="modal-Saldo-confirmar" name="modal-Saldo-confirmar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cobrador:</label>
                                        <select name="Cobrador-confirmar" id="Cobrador-confirmar" class="form-control required">
                                            <option value=""></option>
                                            <?php
                                            foreach ($Lista1 as $item):
                                                echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                            endforeach;
                                            ?>                                    
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha de Pago:</label>
                                        <input type="text" id="FechaPago-confirmar" name="FechaPago-confirmar" class="form-control datepicker8">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span class="label label-success ">Fecha Actual Pago: </span></label>
                                        <input type="text" id="FechaPago-actual" name="FechaPago-actual" class="form-control" readonly>                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span class="label label-danger">Fecha Próximo Pago: </span></label>
                                        <input type="text" id="FechaPago-proximo" name="FechaPago-proximo" class="form-control datepicker1" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-md-12 hidden">
                                    <div class="form-group">
                                        <label>Observaciones Anteriores</label>
                                        <textarea value="" rows="6" class="form-control" name="ObservacionesAnt-confirmar" id="ObservacionesAnt-confirmar" style="resize: none;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <textarea value="" rows="2" class="form-control" name="Observaciones-confirmar" id="Observaciones-confirmar" style="resize: none;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="row">                                
                                <div class="col-md-12">
                                    <p class="error-text" style="margin-top:5px;">
                                        <i class="fa fa-check modal-icon" style="margin-top:-10px;"></i>¿Desea Confirmar este Pago?
                                    </p>
                                </div>
                            </div>-->
                            <div class="row">
                                <div class="col-md-12" id="message-confirmar">
                                </div>
                            </div>                            
                        </div>
                        <div class="modal-footer" style="margin-top: -15px;">
                            <button class="btn btn-default" id="btn-modal-cerrar-confirmar" name="btn-modal-cerrar-confirmar" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                            <button id="btn-modal-confirmar" name="btn-modal-confirmar" class="btn btn-primary"><i class="fa fa-check"></i> Confirmar Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="ModalDescartarPago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" name="form-modal" id="form-modal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Descartar el Pago</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row ">                                
                                <div class="col-md-6 hidden">
                                    <div class="form-group">
                                        <label>Codigo</label>
                                        <input type="text" id="modal-codigo-descartar" name="modal-codigo-descartar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-md-6 hidden">
                                    <div class="form-group">
                                        <label>Pedido</label>
                                        <input type="text" id="modal-pedido-descartar" name="modal-pedido-descartar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6 hidden">
                                    <div class="form-group">
                                        <label>Cliente</label>
                                        <input type="text" id="modal-cliente-descartar" name="modal-cliente-descartar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" id="modal-nombre-descartar" name="modal-nombre-descartar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pago</label>
                                        <input type="text" id="modal-Pago-descartar" name="modal-Pago-descartar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Saldo:</label>
                                        <input type="text" id="modal-saldo-descartar" name="modal-saldo-descartar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-12 hidden">
                                    <div class="form-group">
                                        <label>Valor</label>
                                        <input type="text" id="modal-Valor-descartar" name="modal-Valor-descartar" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Volver a llamar:</label>
                                        <input type="text" id="Fecha-volverLlamar-descartar" name="Fecha-volverLlamar-descartar" class="form-control datepicker8">
                                    </div>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-md-12 hidden">
                                    <div class="form-group">
                                        <label>Observaciones Anteriores</label>
                                        <textarea value="" rows="6" class="form-control" name="ObservacionesAnt-descartar" id="ObservacionesAnt-descartar" style="resize: none;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <textarea value="" rows="6" class="form-control" name="Observaciones-descartar" id="Observaciones-descartar" style="resize: none;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-md-12">
                                    <p class="error-text" style="margin-top:5px;">
                                        <i class="fa fa-check modal-icon" style="margin-top:-10px;"></i>¿Desea Descartar este Pago?
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="message-descartar">
                                </div>
                            </div>                            
                        </div>
                        <div class="modal-footer" style="margin-top: -15px;">
                            <button class="btn btn-default" id="btn-modal-cerrar-descartar" name="btn-modal-cerrar-descartar" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                            <button id="btn-modal-descartar" name="btn-modal-descartar" class="btn btn-danger"><i class="fa fa-close"></i> Descartar Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal small fade" id="ModalPrint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" name="form-modal" id="form-modal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Imprimir Recibos de Pago - Masivo</h3>
                        </div>
                        <div class="modal-body">  
                            <div class="row">                                
                                <div class="col-md-12">
                                    <p class="error-text"><i class="fa fa-warning modal-icon"></i>¿Desea imprimir estos recibos de pago en este momento?</p>
                                </div>
                            </div>
                            <br>
                            <div class="row">                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Número de Recibos</label>
                                        <input type="text" id="modal-num" name="modal-num" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total de Pagos</label>
                                        <input type="text" id="modal-pag" name="modal-pag" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="modal-footer" style="margin-top: -15px;">
                            <button class="btn btn-default" id="btn-modal-cerrar" name="btn-modal-cerrar" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                            <button id="btn-modal" name="btn-modal" class="btn btn-info"><i class="fa fa-print"></i> Imprimir Recibos</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal small fade" id="ModalPrintSolo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" name="form-modal" id="form-modal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Imprimir Recibo de Pago - Único</h3>
                        </div>
                        <div class="modal-body">  
                            <div class="row hidden">                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pedido</label>
                                        <input type="text" id="modal-PedidoSolo" name="modal-PedidoSolo" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cliente</label>
                                        <input type="text" id="modal-ClienteSolo" name="modal-ClienteSolo" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                            </div>     
                            <div class="row">                                
                                <div class="col-md-12">
                                    <p class="error-text"><i class="fa fa-warning modal-icon"></i>¿Desea imprimir estos recibos de pago en este momento?</p>
                                </div>
                            </div>
                            <br>
                            <div class="row">                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Número de Recibos</label>
                                        <input type="text" id="modal-numSolo" name="modal-numSolo" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total de Pagos</label>
                                        <input type="text" id="modal-pagSolo" name="modal-pagSolo" class="form-control" readonly style="background-color:#ffffff;">
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="modal-footer" style="margin-top: -15px;">
                            <button class="btn btn-default" id="btn-modal-cerrar" name="btn-modal-cerrar" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                            <button id="btn-modalSolo" name="btn-modalSolo" class="btn btn-info"><i class="fa fa-print"></i> Imprimir Recibo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="<?= base_url(); ?>Public/assets/lib/Datetables.js/sum().js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $('#message').html("");
                $('#message-confirmar').html("");
                var todayDate = new Date();
                var HomeDate = new Date(2018, 6 - 1, 1, 0, 0, 0);
                var MaxDate = new Date();
                MaxDate.setDate(MaxDate.getDate() + 45);
                var MinDate = new Date();
                MinDate.setDate(MinDate.getDate() - 45);

                $('.datepicker1').datetimepicker({
                    format: 'DD/MM/YYYY',
                    locale: 'es',
                    defaultDate: todayDate,
                    minDate: HomeDate,
                    maxDate: MaxDate
                });
                $('.datepicker8').datetimepicker({
                    format: 'DD/MM/YYYY',
                    locale: 'es',
                    minDate: MinDate,
                    maxDate: MaxDate
                });

                $('#form-FiltrarPagos').submit(function (e) {
                    e.preventDefault();
                    filtrar();
                });

                $('#btn-FiltrarPagos').click(function (e) {
                    e.preventDefault();
                    filtrar();
                });

                listar();

                $('#btn-imprimirPagos').click(function (e) {
                    e.preventDefault();
                    $('#ModalPrint').modal('toggle');
                    imprimirRecibos();
                });

                $('#btn-modal').click(function (e) {
                    e.preventDefault();
                    $('#ModalPrint').modal('toggle');
                    imprimirRecibos();
                });

                $('#btn-modalSolo').click(function (e) {
                    e.preventDefault();
                    $('#ModalPrintSolo').modal('toggle');
                    imprimirReciboSolo();
                });

                $('#btn-modal-confirmar').click(function (e) {
                    e.preventDefault();
                    confirmarPago();
                });

                $('#btn-modal-descartar').click(function (e) {
                    e.preventDefault();
                    descartarPago();
                });

                $('#modal-Pago-confirmar').focusout(function () {
                    var pagoActual = $('#modal-Pago-confirmar1').val();
                    var saldoActual = $('#modal-Saldo-confirmar1').val();
                    pagoActual = pagoActual.replace("$", '').replace("", '').replace(".", '').replace(",", '').trim();
                    saldoActual = saldoActual.replace("$", '').replace("", '').replace(".", '').replace(",", '').trim();
                    saldoActual = parseInt(saldoActual) + parseInt(pagoActual);

                    var pago = $('#modal-Pago-confirmar').val();
                    if (pago == "") {
                        $('#modal-Pago-confirmar').val(0);
                        pago = 0;
                    }
                    var saldo = parseInt(saldoActual) - parseInt(pago)
                    saldo = saldo.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    $('#modal-Saldo-confirmar').val('$ ' + saldo);
                });

                $('#Observaciones-confirmar').focus(function () {
                    var abono = $('#modal-Pago-confirmar').val();
                    var fechaPago = $('#FechaPago-confirmar').val();
                    if (abono.toString().length > 0 && fechaPago.toString().length > 0) {
                        var mon = new Intl.NumberFormat().format(abono);
                        var ob = "Se confirma que el cliente pagó $ " + mon + " el día " + fechaPago + ". ";
                        $('#Observaciones-confirmar').text(ob);
                    } else {
                        $('#Observaciones-confirmar').val("");
                    }
                });
            });

           function listar() {
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
                        {data: "direccion"},
                        {data: "numCuota"},
                        {data: "saldo"},
                        {data: "cuota"},
                        {data: "fecha"},
                        {data: "estado"},
                        {data: "btn"}
                    ],
                    ajax: {
                        method: 'post',
                        url: "<?= base_url(); ?>Pagos/FiltroProg/",
                        data: {
                            pag_usu: pag_usu, pag_fec1: pag_fec1, pag_fec2: pag_fec2
                        }
                    },
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    },
                    initComplete: function (row, data, start, end, display) {
                        var table = $('#Pagos').DataTable();
                        var num = table.rows().count();
                        $('#modal-num').val(num);

                        var api = this.api(), data;
                        // Remove the formatting to get integer data for summation
                        var intVal = function (i) {
                            return typeof i === 'string' ?
                                    i.replace(/[\$.]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                        };
                        // Total over all pages
                        var total = api
                                .column(1)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                        // Total over this page
                        var pageTotal = api
                                .column(1, {page: 'current'})
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                        pageTotal = pageTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        $('#modal-pag').val('$ ' + pageTotal);
                    }
                });
            }

            function filtrar() {
                var pag_usu = $('#dropUsuario').val();
                var pag_fec1 = date("Y-m-d");
                var pag_fec2 = date("Y-m-d");
                // var pag_fec1 = $('#FechaIni').val();
                // var pag_fec2 = $('#FechaFin').val();

                $('#<?= $Controller; ?>').DataTable({
                    bDestroy: true,
                    responsive: true,
                    scrollX: true,
                    bSort: false,
                    columns: [
                        {data: "pedido"},
                        {data: "direccion"},
                        {data: "numCuota"},
                        {data: "saldo"},
                        {data: "cuota"},
                        {data: "fecha"},
                        {data: "estado"},
                        {data: "btn"}
                    ],
                    ajax: {
                        method: 'post',
                        url: "<?= base_url(); ?>Pagos/FiltroProg/",
                        data: {
                            pag_usu: pag_usu, pag_fec1: pag_fec1, pag_fec2: pag_fec2
                        }
                    },
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    },
                    initComplete: function (row, data, start, end, display) {
                        var table = $('#Pagos').DataTable();
                        var num = table.rows().count();
                        $('#modal-num').val(num);

                        var api = this.api(), data;
                        // Remove the formatting to get integer data for summation
                        var intVal = function (i) {
                            return typeof i === 'string' ?
                                    i.replace(/[\$.]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                        };
                        // Total over all pages
                        var total = api
                                .column(1)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                        // Total over this page
                        var pageTotal = api
                                .column(1, {page: 'current'})
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                        pageTotal = pageTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        $('#modal-pag').val('$ ' + pageTotal);
                    }
                });
            }

            function dataModalSolo(num, pageTotal, cliente, pedido) {
                $('#modal-numSolo').val(num);
                $('#modal-pagSolo').val(pageTotal);
                $('#modal-ClienteSolo').val(cliente);
                $('#modal-PedidoSolo').val(pedido);
            }

            function dataModalConfirmar(codigo, pago, saldo, valor, cliente, pedido, nombre, observaciones, diacobro, nuevoDiaCobro) {
                $('#modal-codigo-confirmar').val(codigo);
                $('#modal-Pago-confirmar1').val(pago);
                var numPago = pago.replace("$", '').replace("", '').replace(".", '').replace(",", '').trim();
                $('#modal-Pago-confirmar').val(numPago);
                $('#modal-Valor-confirmar').val(valor);
                $('#modal-Saldo-confirmar1').val(saldo);
                $('#modal-Saldo-confirmar').val(saldo);
                $('#modal-cliente-confirmar').val(cliente);
                $('#modal-pedido-confirmar').val(pedido);
                $('#modal-nombre-confirmar').val(nombre);
                $('#FechaPago-actual').val("Aún no funciona");
                $('#FechaPago-proximo').val("Aún no funciona");
                // $('#FechaPago-actual').val(diacobro);
                // $('#FechaPago-proximo').val(nuevoDiaCobro);
                $('#Cobrador-confirmar').val("");
                $('#FechaPago-confirmar').val("");
                $('#ObservacionesAnt-confirmar').html(observaciones);
                var ob = "Se confirma que el cliente pagó " + pago + " el día de hoy. ";
                $('#Observaciones-confirmar').text(ob);
                $('#message-confirmar').text("");
            }

            function confirmarPago() {
                var codigo = $('#modal-codigo-confirmar').val();
                var pedido = $('#modal-pedido-confirmar').val();
                var cliente = $('#modal-cliente-confirmar').val();
//                var nombre = $('#modal-nombre-confirmar').val();
                var pago = $('#modal-Pago-confirmar').val();
//                var saldo = $('#modal-Saldo-confirmar').val();
                var valor = $('#modal-Valor-confirmar').val();
                var cobrador = $('#Cobrador-confirmar').val();
                var FechaPago = $('#FechaPago-confirmar').val();
                var ObservacionesAnt = $('#ObservacionesAnt-confirmar').html();
                var observaciones = $('#Observaciones-confirmar').val();

                if (observaciones.toString().length <= 0) {
                    $('#message-confirmar').html(
                            '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />Debe Indicar alguna indicación\n\
                                </div>');
                } else {
                    if (cobrador.toString().length <= 0) {
                        $('#message-confirmar').html(
                                '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    <strong>Error</strong><br />Debe Indicar que Cobrador recogió el pago\n\
                                </div>');
                    } else {
                        if (FechaPago.toString().length <= 0) {
                            $('#message-confirmar').html(
                                    '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        <strong>Error</strong><br />Debe Indicar cuando se recogió el pago\n\
                                    </div>');
                        } else {
                            if (cobrador.toString().length <= 0) {
                                $('#message-confirmar').html(
                                        '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />Debe Indicar que Cobrador recogió el pago\n\
                                        </div>');
                            } else {
                                $('#message-confirmar').html("");
                                var method = "<?= base_url(); ?>Pagos/ConfirmarDia/";
                                $("body").css({
                                    'cursor': 'wait'
                                })
                                $.ajax({
                                    type: 'post',
                                    url: method,
                                    data: {
                                        codigo: codigo, pedido: pedido, cliente: cliente, pago: pago, FechaPago: FechaPago, valor: valor, cobrador: cobrador, ObservacionesAnt: ObservacionesAnt, observaciones: observaciones
                                    },
                                    cache: false,
                                    beforeSend: function () {
                                        $('#message-confirmar').html("");
                                        $('#btn-modal-confirmar').html('<i class="fa fa-check"></i> Confirmando...');
                                    },
                                    success: function (data) {
                                        $('#btn-modal-confirmar').html('<i class="fa fa-check"></i> Confirmar Pago');
                                        if (data == 1 || data == 123) {
                                            $('#message-confirmar').html(
                                                    '<div class="alert alert-success alert-dismissable fade in">\n\
                                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                        <strong>Se confirmó el pago de <b>' + pago + '</b></strong>\n\
                                                    </div>');

                                            $('#btn-modal-cerrar-confirmar').click();
                                            $('#btn-FiltrarPagos').click();
                                        } else {
                                            $('#message-confirmar').html(
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

            function dataModalDescartar(codigo, pedido, cliente, nombre, pago, saldo, valor, observaciones) {
                $('#modal-codigo-descartar').val(codigo);
                $('#modal-pedido-descartar').val(pedido);
                $('#modal-cliente-descartar').val(cliente);
                $('#modal-nombre-descartar').val(nombre);
                $('#modal-Pago-descartar').val(pago);
                $('#modal-saldo-descartar').val(saldo);
                $('#modal-Valor-descartar').val(valor);
                $('#Fecha-volverLlamar-descartar').val("");
                $('#ObservacionesAnt-descartar').html(observaciones);
                $('#Observaciones-descartar').html("Cliente no pagó como se acordó.");
                $('#message-descartar').text("");
            }

            function descartarPago() {
                var codigo = $('#modal-codigo-descartar').val();
                var pedido = $('#modal-pedido-descartar').val();
                var cliente = $('#modal-cliente-descartar').val();
                var nombre = $('#modal-nombre-descartar').val();
                var pago = $('#modal-Pago-descartar').val();
                var saldo = $('#modal-saldo-descartar').val();
                var valor = $('#modal-Valor-descartar').val();
                var volverLlamar = $('#Fecha-volverLlamar-descartar').val();
                var observacionesAnt = $('#ObservacionesAnt-descartar').val();
                var observaciones = $('#Observaciones-descartar').val();

                if (observaciones.toString().length <= 0) {
                    $('#message-confirmar').html(
                            '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />Debe Indicar alguna indicación\n\
                            </div>');
                } else {
                    $('#message-descartar').html("");
                    var method = "<?= base_url(); ?>Pagos/DescartarDia/";
                    $("body").css({
                        'cursor': 'wait'
                    })
                    $.ajax({
                        type: 'post',
                        url: method,
                        data: {
                            codigo: codigo, pedido: pedido, cliente: cliente, pago: pago, saldo: saldo, volverLlamar: volverLlamar, ObservacionesAnt: observacionesAnt, observaciones: observaciones
                        },
                        cache: false,
                        beforeSend: function () {
                            $('#message-descartar').html("");
                            $('#btn-modal-descartar').html('<i class="fa fa-close"></i> Descartar...');
                        },
                        success: function (data) {
                            $('#btn-modal-descartar').html('<i class="fa fa-close"></i> Descartar Pago');
                            if (data == 1) {
                                $('#message-descartar').html(
                                        '<div class="alert alert-success alert-dismissable fade in">\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Se Descartó el pago de <b>' + pago + '</b></strong>\n\
                                        </div>');

                                $('#btn-modal-cerrar-descartar').click();
                                $('#btn-FiltrarPagos').click();
                            } else {
                                $('#message-descartar').html(
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

            function imprimirRecibos() {
                var margen = prompt("¿Quiere subir o bajar la impresión?\n0: Normal\n1: Arriba 1 punto\n2: Arriba 2 puntos\n3: Abajo 1 punto\n4: Abajo 2 puntos");
                var pag_usu = $('#dropUsuario').val();
                var pag_fec1 = $('#FechaIni').val();
                var pag_fec2 = $('#FechaFin').val();

                if (pag_usu == "") {
                    pag_usu = "*";
                }
                if (margen.toString().length <= 0) {
                    margen = 0;
                }

                $('#message').html("");
                var method = "<?= base_url(); ?>Pagos/ImprimirRecibosPP/";
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
                        $('#btn-imprimirPagos').html('<i class="fa fa-print"></i> Imprimiendo...');
                    },
                    success: function (data) {
                        $('#btn-imprimirPagos').html('<i class="fa fa-print"></i> Imprimir Recibos');
                        if (data == 1) {
                            window.open('<?= base_url(); ?>Pagos/ImprimirRecibos/' + margen, '_blank');
                        }
                    }

                });
                $("body").css({
                    'cursor': 'Default'
                })

                return false;
            }

            function imprimirReciboSolo() {
                var cliente = $('#modal-ClienteSolo').val();
                var pedido = $('#modal-PedidoSolo').val();
                var margen = prompt("¿Quiere subir o bajar la impresión?\n0: Normal\n1: Arriba 1 punto\n2: Arriba 2 puntos\n3: Abajo 1 punto\n4: Abajo 2 puntos");

                $('#message').html("");
                $("body").css({
                    'cursor': 'wait'
                })
                window.open('<?= base_url(); ?>Pagos/ImprimirReciboSolo/' + pedido + '/' + cliente + '/' + margen, '_blank');

                $("body").css({
                    'cursor': 'Default'
                })

                return false;
            }


        </script>
