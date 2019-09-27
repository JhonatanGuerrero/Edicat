<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('Modules/graficas'); ?>
<div class="content">
    <div class="header">        
        <?php //$this->load->view('Modules/notifications'); ?>
        <h1 class="page-title" style="font-size: 2em;"><?= $title; ?> </h1>
    </div>            
    <div class="main-content">
        <div class="panel panel-default hidden">
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
                                <label>Buscar</label>
                                <div class="btn-toolbar list-toolbar">
                                    <button id="btn-filtro" name="btn-filtro" class="btn btn-primary"><i class="fa fa-search"></i> Buscar Clientes</button>
                                </div>
                            </div>
                        </div> 
                    </form>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <a href="#page-res" class="panel-heading" data-toggle="collapse">Clientes General   </a>
            <div id="page-res" class="panel-collapse panel-body collapse in">
                <div class="row">
                    <div class="col-md-12">
                        <h2 id="h2totalCliente" style="color: #637392;" class="hidden">Total Clientes: <label id="totalCliente"></label></h2>
                    </div>
                    <div class="col-md-12">
                        <div id="chartdiv" style="width: 100%; height: 400px;"></div>
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
                $('#FechaIni').val("01/06/2018");

                $('#btn-filtro').click(function (e) {
                    e.preventDefault();
                    var pag_fec1 = $('#FechaIni').val();
                    var pag_fec2 = $('#FechaFin').val();
                    buscar(pag_fec1, pag_fec2);
                });

                var pag_fec1 = $('#FechaIni').val();
                var pag_fec2 = $('#FechaFin').val();
                buscar(pag_fec1, pag_fec2);
            });

            function buscar(pag_fec1, pag_fec2) {
                $('#message').html("");
                var method = "<?= base_url(); ?>Reportes/ConteoClientesPost/";
                $("body").css({
                    'cursor': 'wait'
                })

                $.ajax({
                    type: 'post',
                    url: method,
                    data: {
                        pag_fec1: pag_fec1, pag_fec2: pag_fec2
                    },
                    dataType: 'json',
                    context: document.body,
                    global: false,
                    async: true,
                    beforeSend: function () {
                        $('#message').html("");
                        $('#btn-filtro').html('<i class="fa fa-search"></i> Filtrando...');
                    },
                    success: function (data) {
                        $('#btn-filtro').html('<i class="fa fa-search"></i> Filtrar Fechas');
                        var total = data[0];
                        var dia = data[1];
                        var deben = data[2];
                        var mora = data[3];
                        var datacredito = data[4];
                        var devoluciones = data[5];
                        var pazysalvo = data[6];
                        var reportados = data[7];
                        var eliminados = data[8];
                        graficarClientes(dia, deben, mora, datacredito, devoluciones, pazysalvo, reportados, eliminados);
                        $('#h2totalCliente').removeClass('hidden');
                        $('#totalCliente').html(total);
                    }

                });
                $("body").css({
                    'cursor': 'Default'
                })

                return false;
            }

            function graficarClientes(dia, deben, mora, datacredito, devoluciones, pazysalvo, reportados, eliminados) {
                // Themes begin
                am4core.useTheme(am4themes_material);
                am4core.useTheme(am4themes_animated);
                // Themes end

                // Create chart instance
                var chart = am4core.create("chartdiv", am4charts.XYChart);
                chart.scrollbarX = new am4core.Scrollbar();

                // Add data
                chart.data = [{
                        "tipo": "Al día\n" + dia + " Clientes",
                        "clientes": dia
                    }, {
                        "tipo": "Deben\n" + deben + " Clientes",
                        "clientes": deben
                    }, {
                        "tipo": "En Mora\n" + mora + " Clientes",
                        "clientes": mora
                    }, {
                        "tipo": "Datacrédito\n" + datacredito + " Clientes",
                        "clientes": datacredito
                    }, {
                        "tipo": "Devoluciones\n" + devoluciones + " Clientes",
                        "clientes": devoluciones
                    }, {
                        "tipo": "Paz y Salvo\n" + pazysalvo + " Clientes",
                        "clientes": pazysalvo
                    }, {
                        "tipo": "Reportados\n" + reportados + " Clientes",
                        "clientes": reportados
                    }, {
                        "tipo": "Eliminados\n" + eliminados + " Clientes",
                        "clientes": eliminados
                    }];

                // Create axes
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "tipo";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.minGridDistance = 20;
                categoryAxis.renderer.labels.template.verticalCenter = "middle";
                categoryAxis.renderer.labels.template.rotation = 0;
                categoryAxis.tooltip.disabled = true;
                categoryAxis.renderer.minHeight = 110;

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.minWidth = 50;

                // Modify chart's colors
                chart.colors.list = [
                    am4core.color("#8FFEC2"),
                    am4core.color("#D65DB1"),
                    am4core.color("#FF6F91"),
                    am4core.color("#FF9671"),
                    am4core.color("#FFC75F"),
                    am4core.color("#F9F871")
                ];
                // Create series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.sequencedInterpolation = true;
                series.dataFields.valueY = "clientes";
                series.dataFields.categoryX = "tipo";
                series.columns.template.strokeWidth = 0;
                series.columns.template.tooltipText = "{tipo}";
                series.tooltip.pointerOrientation = "vertical";

                series.columns.template.column.cornerRadiusTopLeft = 10;
                series.columns.template.column.cornerRadiusTopRight = 10;
                series.columns.template.column.fillOpacity = 0.8;

                var bullet = series.columns.template.createChild(am4core.Circle);
                bullet.locationY = 0.5;
                bullet.align = "center";
                bullet.valign = "middle";
                bullet.fill = am4core.color("#fff");
                bullet.fillOpacity = 0.7;
                bullet.radius = 15;

                // Add a bullet
                var label = series.columns.template.createChild(am4core.Label);
                label.text = "{valueY}";
                label.align = "center";
                label.valign = "middle";
                label.fill = am4core.color("#5f6f8d");
                label.zIndex = 2;
                label.strokeWidth = 0;

                series.columns.template.adapter.add("fill", (fill, target) => {
                    return chart.colors.getIndex(target.dataItem.index);
                })

                chart.responsive.enabled = true;

                chart.responsive.rules.push({
                    relevant: function (target) {
                        return false;
                    },
                    state: function (target, stateId) {
                        return;
                    }
                });

            }
        </script>
