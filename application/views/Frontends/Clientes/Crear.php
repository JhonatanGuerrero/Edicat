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
                    <div class="col-md-12" id="message">
                        <?php
                        if ($this->session->flashdata("error")) {
                            echo '<div class="alert alert-danger" role="alert">' . $this->session->flashdata("error") . '</div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-toolbar list-toolbar">
                            <a href="<?= base_url() . "Clientes/Admin/" ?>" class="btn btn-danger"><i class="fa fa-undo"></i> Volver a Clientes</a>
                        </div>
                    </div>
                </div>
                <div class="row hidden">
                    <div class="col-md-12">
                        <div class="btn-toolbar list-toolbar">
                            <input type="text" id="txtGuardando" name="txtGuardando" readonly="yes">
                        </div>
                    </div>
                </div>   
                <div class="btn-toolbar list-toolbar">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <form id="FrmNuevoCliente" name="FrmNuevoCliente" method="POST" role="form">
                                <div>                                
                                    <h3>Datos Personales</h3>
                                    <section style="margin-top:-15px;" >
                                        <div class="form-group hidden">
                                            <label>Orden de Pedido</label>
                                            <input type="text" readonly placeholder="Orden de Pedido" value="" class="form-control" id="Orden" name="Orden">
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre *</label>
                                            <input type="text" minlength="5" maxlength="50" placeholder="Nombre" value="" class="form-control required" id="Nombre" name="Nombre">
                                        </div>
                                        <div class="form-group">
                                            <label>Tipo de Documento *</label>
                                            <select name="TipoDocumento" id="TipoDocumento" class="form-control required">
                                                <option value=""></option>
                                                <?php
                                                foreach ($Lista1 as $item):
                                                    echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                                endforeach;
                                                ?>                                    
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Documento *</label>
                                            <input type="number" min="0" placeholder="Documento" minlength="8" maxlength="15"  value="" class="form-control required number" id="Documento" name="Documento">
                                        </div>                                    
                                    </section>

                                    <h3>Ubicación</h3>
                                    <section style="margin-top:-15px;" >
                                        <div class="form-group">
                                            <label>Dirección *</label>
                                            <input type="text" placeholder="Direccion" value=""  minlength="8" maxlength="30" class="form-control required" id="Direccion" name="Direccion">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Etapa</label>
                                                    <input type="text" placeholder="Etapa" value="" class="form-control" maxlength="10" id="Etapa" name="Etapa">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Torre</label>
                                                    <input type="text" placeholder="Torre" value="" class="form-control" maxlength="10" id="Torre" name="Torre">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Apartamento</label>
                                                    <input type="text" placeholder="Apartamento" value="" class="form-control" maxlength="10" id="Apartamento" name="Apartamento">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Manzana</label>
                                                    <input type="text" placeholder="Manzana" value="" class="form-control" id="Manzana" maxlength="10" name="Manzana">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Interior</label>
                                                    <input type="text" placeholder="Interior" value="" class="form-control" maxlength="10" id="Interior" name="Interior">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Casa</label>
                                                    <input type="text" placeholder="Casa" value="" class="form-control" maxlength="10" id="Casa" name="Casa">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Barrio *</label>
                                                    <input type="text" placeholder="Barrio" value="" class="form-control required" maxlength="30" id="Barrio" name="Barrio">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Tipo de Vivienda *</label>
                                                    <select name="TipoVivienda" id="TipoVivienda" class="form-control required">
                                                        <option value=""></option>
                                                        <?php
                                                        foreach ($Lista2 as $item):
                                                            echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>                                    
                                            </div>
                                        </div>
                                    </section>

                                    <h3>Teléfonos</h3>
                                    <section style="margin-top:-15px;" >
                                        <div class="form-group">
                                            <label>Teléfono 1 *</label>
                                            <input type="text" placeholder="Teléfono 1" value="" class="form-control required number" minlength="7" maxlength="10" id="Telefono1" name="Telefono1">
                                        </div>
                                        <div class="form-group">
                                            <label>Teléfono 2</label>
                                            <input type="text" placeholder="Teléfono 2" value="" class="form-control" minlength="7" maxlength="10" id="Telefono2" name="Telefono2">
                                        </div>                                   
                                        <div class="form-group">
                                            <label>Teléfono 3</label>
                                            <input type="text" placeholder="Teléfono 3" value="" class="form-control" minlength="7" maxlength="10" id="Telefono3" name="Telefono3">
                                        </div>
                                    </section>

                                    <h3>Lista de Referencias</h3>
                                    <section style="margin-top:-15px;" >
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Nombre Referencia *</label>
                                                    <input type="text" placeholder="Nombre" value="" class="form-control required" minlength="5" maxlength="50" id="NombreRef1" name="NombreRef1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Teléfono Referencia *</label>
                                                    <input type="text" placeholder="Teléfono" value="" class="form-control required" minlength="7" maxlength="30" id="TelefonoRef1" name="TelefonoRef1">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Parentesco Referencia *</label>
                                                    <input type="text" placeholder="Parentesco" value="" class="form-control required" minlength="3" maxlength="20" id="ParentescoRef1" name="ParentescoRef1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Nombre Referencia *</label>
                                                    <input type="text" placeholder="Nombre" value="" class="form-control required" minlength="5" maxlength="50" id="NombreRef2" name="NombreRef2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Teléfono Referencia *</label>
                                                    <input type="text" placeholder="Teléfono" value="" class="form-control required" minlength="7" maxlength="30" id="TelefonoRef2" name="TelefonoRef2">
                                                </div> 
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Parentesco Referencia *</label>
                                                    <input type="text" placeholder="Parentesco" value="" class="form-control required" minlength="3" maxlength="20" id="ParentescoRef2" name="ParentescoRef2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Nombre Referencia</label>
                                                    <input type="text" placeholder="Nombre" value="" class="form-control" minlength="5" maxlength="50" id="NombreRef3" name="NombreRef3">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Teléfono Referencia</label>
                                                    <input type="text" placeholder="Teléfono" value="" class="form-control " minlength="7" maxlength="30" id="TelefonoRef3" name="TelefonoRef3">
                                                </div> 
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Parentesco Referencia</label>
                                                    <input type="text" placeholder="Parentesco" value="" class="form-control" minlength="3" maxlength="20" id="ParentescoRef3" name="ParentescoRef3">
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <h3>Productos </h3>
                                    <section style="margin-top:-15px;" >
                                        <table class="table table-striped table-bordered" id="ProductosAdquiridos" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Cantidad</th>
                                                    <th>Producto</th>
                                                    <th>Valor</th>                                                  
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="number" min="0" placeholder="#" onchange="valorProducto(1)" value="" class="form-control required number" maxlength="3" id="Cantidad1" name="Cantidad1">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <select name="Producto1" id="Producto1" onchange="valorProducto(1)" class="form-control required">
                                                                <option value=""></option>
                                                                <?php
                                                                foreach ($Lista4 as $item):
                                                                    echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                                                endforeach;
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="number" min="0" placeholder="Valor" style="background-color: #fff;" readonly value="0" class="form-control required number" id="Valor1" name="Valor1">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </section>

                                    <h3>Pagos</h3>
                                    <section style="margin-top:-15px;" >
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Valor Total *</label>
                                                    <input type="number" min="0" placeholder="Valor Total" value="" readonly style="background-color: #fff;" class="form-control required" id="valorTotal1" name="valorTotal1">
                                                </div>
                                            </div>                                        
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Primer Cobro *</label>
                                                    <input type="text" placeholder="Primer Cobro" value="" class="form-control required datepicker" id="primerCobro" name="primerCobro">
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label>Tarifa *</label>
                                                    <select name="Tarifa1" id="Tarifa1" class="form-control required" onchange="datosTarifa(this.value)">
                                                        <option value=""></option>
                                                    </select>
                                                </div>    
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label>Descuento</label>
                                                    <input type="number" min="0" placeholder="Valor del Descuento" value="0" readonly style="background-color: #fff;" class="form-control required" id="valorDescuento" name="valorDescuento">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Número de Cuotas *</label>
                                                    <input type="number" placeholder="Cuotas" value="" readonly style="background-color: #fff;" class="form-control required" id="numCuotas" name="numCuotas"  onchange="tarifaManual(1)">
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label>Valor de la Cuota *</label>
                                                    <input type="number" min="0" placeholder="Valor de la Cuota" value="" readonly style="background-color: #fff;" class="form-control required" id="valorCuota" name="valorCuota" onchange="tarifaManual(2)">
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label>Total a Pagar *</label>
                                                    <input type="number" placeholder="Total a Pagar" value="" style="background-color: #fff;" class="form-control required number" readonly id="TotalPagar" name="TotalPagar">
                                                    <label name="lblErrorTotal" id="lblErrorTotal" class="" style="color: #8a1f11;"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label>Abono</label>
                                                    <input type="number" placeholder="Abono" value="0" min="0" class="form-control number" id="Abono" name="Abono">
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <h3>Observaciones</h3>
                                    <section style="margin-top:-15px;" >
                                        <div class="row">
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label>Vendedor *</label>
                                                    <select name="Vendedor1" id="Vendedor1" class="form-control required">
                                                        <option value=""></option>
                                                        <?php
                                                        foreach ($Lista6 as $item):
                                                            echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>    
                                            </div>                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3"> 
                                                <div class="form-group ui-widget">
                                                    <label>Iglesia *</label>
                                                    <input type="text" id="IglesiaEvento" name="IglesiaEvento" class="form-control required">
                                                </div>    
                                            </div>
                                            <div class="col-md-3"> 
                                                <div class="form-group">
                                                    <label>Barrio *</label>
                                                    <input type="text" id="BarrioEvento" name="BarrioEvento" class="form-control required">
                                                </div>    
                                            </div>
                                            <div class="col-md-3"> 
                                                <div class="form-group">
                                                    <label>Fecha Evento *</label>
                                                    <input type="text" id="FechaEvento" name="FechaEvento" class="form-control datepickerAll required">
                                                </div>    
                                            </div>
                                            <div class="col-md-3"> 
                                                <div class="form-group">
                                                    <label>Ubicación Física *</label>
                                                    <input type="text" id="PaginaEvento" name="PaginaEvento" maxlength="20" class="form-control required">
                                                </div>    
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12"> 
                                                <div class="form-group">
                                                    <label>Observaciones</label>
                                                    <textarea value="" rows="6" class="form-control" name="Observaciones" id="Observaciones" style="resize: none;"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </form>  

                            <div class="row">
                                <div class="col-md-12" id="msgErrors">
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
            $('#<?= $Controller; ?>').DataTable({
                responsive: true,
                scrollX: true,
                language: {
                    url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
                }
            });

            function crearcliente() {
                var txtGuardando = $('#txtGuardando').val();
                if (txtGuardando != '1') {
                    $('#message').html("");
                    var cli_nom = $('form[name=FrmNuevoCliente] input[name=Nombre]')[0].value.trim();
                    var e_tipdoc = document.getElementById("TipoDocumento");
                    var cli_tipdoc = e_tipdoc.options[e_tipdoc.selectedIndex].value;
                    var cli_doc = $('form[name=FrmNuevoCliente] input[name=Documento]')[0].value.trim();
                    var cli_dir = $('form[name=FrmNuevoCliente] input[name=Direccion]')[0].value.trim();
                    var cli_eta = $('form[name=FrmNuevoCliente] input[name=Etapa]')[0].value.trim();
                    var cli_tor = $('form[name=FrmNuevoCliente] input[name=Torre]')[0].value.trim();
                    var cli_apto = $('form[name=FrmNuevoCliente] input[name=Apartamento]')[0].value.trim();
                    var cli_manz = $('form[name=FrmNuevoCliente] input[name=Manzana]')[0].value.trim();
                    var cli_int = $('form[name=FrmNuevoCliente] input[name=Interior]')[0].value.trim();
                    var cli_casa = $('form[name=FrmNuevoCliente] input[name=Casa]')[0].value.trim();
                    var cli_bar = $('form[name=FrmNuevoCliente] input[name=Barrio]')[0].value.trim();
                    var e_tipviv = document.getElementById("TipoVivienda");
                    var cli_tipviv = e_tipviv.options[e_tipviv.selectedIndex].value;
                    var cli_tel1 = $('form[name=FrmNuevoCliente] input[name=Telefono1]')[0].value.trim();
                    var cli_tel2 = $('form[name=FrmNuevoCliente] input[name=Telefono2]')[0].value.trim();
                    var cli_tel3 = $('form[name=FrmNuevoCliente] input[name=Telefono3]')[0].value.trim();
                    var cli_nomrf1 = $('form[name=FrmNuevoCliente] input[name=NombreRef1]')[0].value.trim();
                    var cli_telrf1 = $('form[name=FrmNuevoCliente] input[name=TelefonoRef1]')[0].value.trim();
                    var cli_paren1 = $('form[name=FrmNuevoCliente] input[name=ParentescoRef1]')[0].value.trim();
                    var cli_nomrf2 = $('form[name=FrmNuevoCliente] input[name=NombreRef2]')[0].value.trim();
                    var cli_telrf2 = $('form[name=FrmNuevoCliente] input[name=TelefonoRef2]')[0].value.trim();
                    var cli_paren2 = $('form[name=FrmNuevoCliente] input[name=ParentescoRef2]')[0].value.trim();
                    var cli_nomrf3 = $('form[name=FrmNuevoCliente] input[name=NombreRef3]')[0].value.trim();
                    var cli_telrf3 = $('form[name=FrmNuevoCliente] input[name=TelefonoRef3]')[0].value.trim();
                    var cli_paren3 = $('form[name=FrmNuevoCliente] input[name=ParentescoRef3]')[0].value.trim();
                    var cli_cant1 = $('form[name=FrmNuevoCliente] input[name=Cantidad1]')[0].value.trim();
                    var e_prod1 = document.getElementById("Producto1");
                    var cli_prod1 = e_prod1.options[e_prod1.selectedIndex].value;
                    var cli_nomprod1 = $("#Producto1 option:selected").text();
                    var cli_val1 = $('form[name=FrmNuevoCliente] input[name=Valor1]')[0].value.trim();
                    var cli_valtotal = $('form[name=FrmNuevoCliente] input[name=valorTotal1]')[0].value.trim();
                    var cli_abono = $('form[name=FrmNuevoCliente] input[name=Abono]')[0].value.trim();
                    var cli_priCobro = $('form[name=FrmNuevoCliente] input[name=primerCobro]')[0].value.trim();
                    var e_tar1 = document.getElementById("Tarifa1");
                    var cli_tar1 = e_tar1.options[e_tar1.selectedIndex].value;
                    var cli_nomTar = $("#Tarifa1 option:selected").text();
                    var cli_numCuo = $('form[name=FrmNuevoCliente] input[name=numCuotas]')[0].value.trim();
                    var cli_valCuo = $('form[name=FrmNuevoCliente] input[name=valorCuota]')[0].value.trim();
                    var cli_totalPag = $('form[name=FrmNuevoCliente] input[name=TotalPagar]')[0].value.trim();
                    var e_Ven = document.getElementById("Vendedor1");
                    var cli_Ven = e_Ven.options[e_Ven.selectedIndex].value;
                    var cli_IglEve = $('form[name=FrmNuevoCliente] input[name=IglesiaEvento]')[0].value.trim();
                    var cli_BarEve = $('form[name=FrmNuevoCliente] input[name=BarrioEvento]')[0].value.trim();
                    var cli_FecEve = $('form[name=FrmNuevoCliente] input[name=FechaEvento]')[0].value.trim();
                    var cli_PagEve = $('form[name=FrmNuevoCliente] input[name=PaginaEvento]')[0].value.trim();
                    var cli_Obs = $('form[name=FrmNuevoCliente] textarea[name=Observaciones]')[0].value.trim();


                    var numErrores = 0;
                    /*Validaciones*/
                    //Referencias
                    var ref = 0;
                    if (cli_nomrf3 != "")
                        ref++;
                    if (cli_telrf3 != "")
                        ref++;
                    if (cli_paren3 != "")
                        ref++;
                    if (ref != 0 && ref != 3)
                    {
                        $('#msgErrors').html(
                                '<div class="alert alert-danger alert-dismissable fade in">\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                <strong>Error</strong><br />Si desea agregar una referencia, por favor indique los 3 datos: Nombre, Teléfono y Parentesco\n\
                            </div>');
                        numErrores++;
                    }

                    if (numErrores == 0) {
                        var method = "<?= base_url() . 'Clientes/NewClient/'; ?>";
                        $("body").css({
                            'cursor': 'wait'
                        })
                        $('#txtGuardando').val("1");
                        $.ajax({
                            type: 'POST',
                            url: method,
                            data: {
                                cli_nom: cli_nom, cli_tipdoc: cli_tipdoc, cli_doc: cli_doc,
                                cli_dir: cli_dir, cli_eta: cli_eta, cli_tor: cli_tor, cli_apto: cli_apto, cli_manz: cli_manz, cli_int: cli_int, cli_casa: cli_casa, cli_bar: cli_bar, cli_tipviv: cli_tipviv,
                                cli_tel1: cli_tel1, cli_tel2: cli_tel2, cli_tel3: cli_tel3,
                                cli_numRef: ref,
                                cli_nomrf1: cli_nomrf1, cli_telrf1: cli_telrf1, cli_paren1: cli_paren1,
                                cli_nomrf2: cli_nomrf2, cli_telrf2: cli_telrf2, cli_paren2: cli_paren2,
                                cli_nomrf3: cli_nomrf3, cli_telrf3: cli_telrf3, cli_paren3: cli_paren3,
                                cli_cant1: cli_cant1, cli_prod1: cli_prod1, cli_val1: cli_val1, cli_nomprod1: cli_nomprod1,
                                cli_valtotal: cli_valtotal, cli_priCobro: cli_priCobro, cli_tar1: cli_tar1, cli_nomTar: cli_nomTar, cli_numCuo: cli_numCuo, cli_valCuo: cli_valCuo, cli_totalPag: cli_totalPag, cli_abono: cli_abono,
                                cli_Ven: cli_Ven, cli_IglEve: cli_IglEve, cli_BarEve: cli_BarEve, cli_FecEve: cli_FecEve, cli_PagEve: cli_PagEve, cli_Obs: cli_Obs
                            },
                            cache: false,
                            beforeSend: function () {
                                $("#msgErrors").html(
                                        '<div class="alert alert-success alert-dismissable fade in">\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        Guardando...\n\
                                     </div>');

                            },
                            success: function (data) {
                                $('#txtGuardando').val("1");
                                if (data == 1) {
                                    $('#msgErrors').html(
                                            '<div class="alert alert-success alert-dismissable fade in">\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>El Cliente/Pedido \"<b>' + cli_nom + '</b>\" fue creado exitosamente</strong>\n\
                                         </div>');
                                    location.href = "<?= base_url(); ?>Clientes/Admin";
                                } else {
                                    $('#msgErrors').html(
                                            '<div class="alert alert-danger alert-dismissable fade in">\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            <strong>Error</strong><br />' + data + '\n\
                                        </div>');
                                    $('#txtGuardando').val("0");
                                    var finishButton = $('#FrmNuevoCliente').find('a[href="#finish"]');
                                    finishButton.removeClass("hidden");
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

            var form = $("#FrmNuevoCliente");
            form.validate({
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                },
                rules: {
                    Valor1: {
                        required: false,
                        min: 1
                    },
                    numCuotas: {
                        required: false,
                        min: 1
                    },
                    valorCuota: {
                        required: false,
                        min: 1
                    },
                    TotalPagar: {
                        required: false,
                        min: 1
                    }
                },
                messages: {
                    Valor1: {
                        min: "No se ha calculado el precio del producto"
                    },
                    numCuotas: {
                        min: "La cuota debe ser al menos 1"
                    },
                    valorCuota: {
                        min: "El valor de la Cuota no puede ser 0"
                    },
                    TotalPagar: {
                        min: "No se ha calculado el precio a pagar"
                    }
                }

            });

            form.children("div").steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                },
                onFinishing: function (event, currentIndex)
                {
                    form.validate().settings.ignore = ":disabled";
                    return form.valid();
                },
                onFinished: function (event, currentIndex)
                {
                    //alert("Submitted!");
                    var finishButton = $('#FrmNuevoCliente').find('a[href="#finish"]');
                    finishButton.addClass("hidden");
                    crearcliente(); 
                }
            });
            var todayDate = new Date().getDate();
            $('.datepicker').datetimepicker({
                format: 'DD/MM/YYYY',
                locale: 'es',
                minDate: new Date(),
                maxDate: new Date(new Date().setDate(todayDate + 60))
            });
            $('.datepickerAll').datetimepicker({
                format: 'DD/MM/YYYY',
                locale: 'es'
            });
        });

        function cambioProducto() {
            var productID = $('#Producto1').val();
            if (productID) {
                var method = "<?= base_url() . 'Tarifas/obtenerTarifaProductoJson/'; ?>";

                $.ajax({
                    type: 'POST',
                    url: method,
                    data: {codigo: productID},
                    dataType: 'json',
                    success: function (html) {
                        if (html == 0) {
                            alert("Error: El Producto Seleccionado no tiene Tarifas Vigentes.");
                            $('#Tarifa1').html('<option value=""></option>');
                        } else {
                            $('#Tarifa1').html("");
                            $('#Tarifa1').html('<option value=""></option>');
                            $.each(html, function (key, value) {
                                $('#Tarifa1').append("<option value='" + value.Codigo + "'>" + value.Nombre + "</option>");
                            });
                        }
                    }
                });
            } else {
                $('#Tarifa1').html('<option value=""></option>');
            }

        }


        function valorProducto(num) {
            var Codproducto = "Producto" + num;
            Codproducto = $("#" + Codproducto).val();
            var method = "<?= base_url() . 'Productos/obtenerProductoCod/'; ?>";
            $("body").css({
                'cursor': 'wait'
            })
            var Cantproducto = "Cantidad" + num;
            Cantproducto = $("#" + Cantproducto).val();
            var Valorproducto = "Valor" + num
            if (Cantproducto <= 0 || Cantproducto == null || Cantproducto == "") {
                $("#" + Cantproducto).val("0");
                $("#" + Valorproducto).val("0");
            } else {
                if (Codproducto <= 0 || Codproducto == null || Codproducto == "") {
                    $("#" + Valorproducto).val("0");
                } else {
                    $.ajax({
                        type: 'POST',
                        url: method,
                        data: {codigo: Codproducto},
                        dataType: 'JSON',
                        cache: false,
                        success: function (data) {
                            if (typeof data[0].Codigo === 'undefined') {
                                alert(data);
                            } else {
                                var valorPro = data[0].Valor;
                                var totalPro = valorPro * Cantproducto;
                                $("#" + Valorproducto).val(totalPro);
                                var v = parseInt("0");
                                for (var i = 1; i <= 1; i++) {
                                    var t = parseInt($("#Valor" + i).val());
                                    v = v + t;
                                }
                                $("#valorTotal1").val(v);
                                cambioProducto();
                            }
                        }
                    });
                }
            }

            $("body").css({
                'cursor': 'default'
            })
        }

        function datosTarifa(event) {
            $('#lblErrorTotal').html("");
            var codigoTarifa = event;
            if (codigoTarifa == null || codigoTarifa == "" || codigoTarifa == 0) {
                $('#numCuotas').val("0");
                $('#valorCuota').val("0");
                $('#TotalPagar').val("0");
            } else {
                var method = "<?= base_url() . 'Tarifas/obtenerTarifaCod/'; ?>";
                $("body").css({
                    'cursor': 'wait'
                })
                $.ajax({
                    type: 'POST',
                    url: method,
                    data: {codigo: codigoTarifa},
                    dataType: 'JSON',
                    cache: false,
                    success: function (data) {
                        if (typeof data[0].Codigo === 'undefined') {
                            alert(data);
                        } else {
                            if (data[0].Cuotas == 0) {
                                $('#lblErrorTotal').html("");
                                $('#numCuotas').val("0");
                                $('#valorCuota').val("0");
                                $('#TotalPagar').val("0");
                                $('#valorDescuento').val("0");
                                $('#numCuotas').attr("readonly", false);
                                $('#valorCuota').attr("readonly", false);
                                $('#valorDescuento').attr("readonly", false);
                                $('#numCuotas').focus();
                            } else {
                                $('#lblErrorTotal').html("");
                                $('#numCuotas').attr("readonly", true);
                                $('#valorCuota').attr("readonly", true);
                                var cantidad = parseInt($('#Cantidad1').val());
                                var total1 = parseInt($('#valorTotal1').val());

                                var numCuotas = data[0].Cuotas;
                                $('#numCuotas').val(numCuotas);
                                var descuento = parseInt(data[0].Descuento);
                                var totaldescuento = descuento * cantidad;
                                $('#valorDescuento').val(totaldescuento);
                                var valorCuota = parseInt(data[0].ValorCuota) * cantidad;
                                $('#valorCuota').val(valorCuota);
                                var valor = parseInt(data[0].Valor);
                                var totalpagar = (valor * cantidad);
                                $('#TotalPagar').val(totalpagar);

                                if ((totalpagar + totaldescuento) != total1) {
                                    $('#lblErrorTotal').html("La tarifa no coincide: Por favor pruebe otra, de lo contrario solicite creación de Tarifa");
                                }
                            }
                        }
                    }
                });
                $("body").css({
                    'cursor': 'default'
                })
            }
        }

        $(function () {
            var IglesiasTags = <?= $Lista7; ?>;
            $("#IglesiaEvento").autocomplete({
                source: IglesiasTags
            });
            var EventosTags = <?= $Lista8; ?>;
            $("#BarrioEvento").autocomplete({
                source: EventosTags
            });
        });
    </script>