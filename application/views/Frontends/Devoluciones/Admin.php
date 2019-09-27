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
                        <form id="form-Filtrar" name="form-Filtrar" method="POST" >                            
                            <div class="col-md-3 col-md-offset-2">
                                <div class="form-group">
                                    <label>Fecha Inicial</label>
                                    <input type="text" id="FechaIni" name="FechaIni" class="form-control datepicker1" required style="background-color: #ffffff;">
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha Final</label>
                                    <input type="text" id="FechaFin" name="FechaFin" class="form-control datepicker1" required style="background-color: #ffffff;">
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Filtrar</label>
                                    <div class="btn-toolbar list-toolbar">
                                        <button id="btn-Filtrar" name="btn-Filtrar" class="btn btn-primary"><i class="fa fa-search"></i> Filtrar Fechas</button>                                            
                                    </div>                                    
                                </div>
                            </div>                            
                        </form>
                    </div>
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th style="text-align:center;margin:0 auto;">Pedido</th>
                                    <th style="text-align:center;margin:0 auto;">Cliente</th>
                                    <th style="text-align:center;margin:0 auto;">Saldo</th>
                                    <th style="text-align:center;margin:0 auto;width:50px;">Fecha Devolución</th>
                                    <th style="text-align:center;margin:0 auto;width:200px;">Nota</th>
                                    <th style="text-align:center;margin:0 auto;">Opciones</th>
                                </tr>
                            </thead>
                        </table>
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

                $('#form-Filtrar').submit(function (e) {
                    e.preventDefault();
                    filtrar();
                });

                $('#btn-Filtrar').click(function (e) {
                    e.preventDefault();
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
                        {data: "Saldo"},
                        {data: "fecha"},
                        {data: "observacion"},
                        {data: "btn"}
                    ],
                    ajax: {
                        method: 'post',
                        url: "<?= base_url(); ?>Devoluciones/listadoDevoluviones/"
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
                        {data: "Saldo"},
                        {data: "fecha"},
                        {data: "observacion"},
                        {data: "btn"}
                    ],
                    ajax: {
                        method: 'post',
                        url: "<?= base_url(); ?>Devoluciones/FiltroDevol/",
                        data: {
                            pag_usu: pag_usu, pag_fec1: pag_fec1, pag_fec2: pag_fec2
                        }
                    },
                    language: {
                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                    }
                });
            }
        </script>
