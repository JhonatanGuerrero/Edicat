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
                    <?php if ($this->session->flashdata("msg")): ?>
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissable fade in">                       
                                <?= $this->session->flashdata("msg"); ?>
                            </div>
                        </div>
                    <?php endif; ?>
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
                <?php
//                foreach ($ListaProductos as $item) {
//                    var_dump($item);
//                    echo "<br><br>";
//                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Nombre Producto</th>
                                    <th>Valor Producto</th>
                                    <th>Cantidad</th>
                                    <th>Valor</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($ListaProductos) > 0 && $ListaProductos != false) {
                                    foreach ($ListaProductos as $item) {
//                                        var_dump($item);
//                                        echo "<br><br>";
                                        ?> 
                                        <tr>
                                            <td><?= $item["NomPro"]; ?></td>
                                            <td><?= money_format("%.0n", $item["ValTarifa"]); ?></td>
                                            <td><?= $item["Cantidad"]; ?></td>
                                            <td><?= money_format("%.0n", $item["ValPP"]); ?></td>                                            
                                            <td>Sin Opciones Habilitadas</td>
                                        </tr>          
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>  
            </div>
        </div>  
        <div class="panel panel-default">
            <a href="#page-stats2" class="panel-heading" data-toggle="collapse">Agregar Producto</a>
            <div id="page-stats2" class="panel-collapse panel-body collapse in">
                <div class="row">                    
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Pedido</label>
                                <input type="text" value="<?= $pedido; ?>" class="form-control" readonly id="pedido" name="pedido">
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre Cliente</label>
                                <input type="text" value="<?= $NombreCliente; ?>" class="form-control" readonly id="nombre" name="nombre">
                            </div>
                        </div>                         
                    </div>

                    <div class="col-md-12">
                        <form id="frm-AddProducto" name="frm-AddProducto">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Producto</label>
                                    <select name="ProductoAddP" id="ProductoAddP" class="form-control required">
                                        <option value=""></option>
                                        <?php
                                        foreach ($LProducto as $item):
                                            echo '<option value="' . $item['Codigo'] . '">' . $item['Nombre'] . '</option>';
                                        endforeach;
                                        ?>                                    
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" value="" class="form-control required" max="10" min="1" id="CantidadAddP" name="CantidadAddP">
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tarifa</label>
                                    <select name="TarifaAddP" id="TarifaAddP" class="form-control required">                                 
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Valor Total</label>
                                    <input type="text" value="" class="form-control required"  readonly id="ValorAddP" name="ValorAddP">
                                </div>
                            </div>

                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="btn-AddProducto" id="btn-AddProducto"><i class="fa fa-save"></i> Agregar Producto</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="message"></div>
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

                $('#ProductoAddP').change(function () {
                    var producto = $('#ProductoAddP').val();
                    tarifaProducto(producto);
                });

                $('#TarifaAddP').change(function () {
                    var tarifa = $('#TarifaAddP').val();
                    var cantidad = $('#CantidadAddP').val();
                    if (tarifa.toString().length > 0 & cantidad.toString().length > 0) {
                        totalProducto(tarifa, cantidad);
                    } else {
                        $('#ValorAddP').val("");
                    }
                });

                $('#CantidadAddP').change(function () {
                    var tarifa = $('#TarifaAddP').val();
                    var cantidad = $('#CantidadAddP').val();
                    if (tarifa.toString().length > 0 & cantidad.toString().length > 0) {
                        totalProducto(tarifa, cantidad);
                    } else {
                        $('#ValorAddP').val("");
                    }
                });

                $('btn-AddProducto').click(function (e) {
                    e.preventDefault();
                    agregarProducto();
                });

                $('#frm-AddProducto').submit(function (e) {
                    e.preventDefault();
                    agregarProducto();
                });

            });

            function tarifaProducto(productID) {
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
                                $('#TarifaAddP').html('<option value=""></option>');
                            } else {
                                $('#TarifaAddP').html("");
                                $('#TarifaAddP').html('<option value=""></option>');
                                $.each(html, function (key, value) {
                                    $('#TarifaAddP').append("<option value='" + value.Codigo + "'>" + value.Nombre + "</option>");
                                });
                            }
                        }
                    });
                } else {
                    $('#TarifaAddP').html('<option value=""></option>');
                }
            }

            function totalProducto(tarifa, cantidad) {
                var method = "<?= base_url() . 'Tarifas/obtenerTarifaCod/'; ?>";
                $("body").css({
                    'cursor': 'wait'
                })
                $.ajax({
                    type: 'POST',
                    url: method,
                    data: {codigo: tarifa},
                    dataType: 'JSON',
                    cache: false,
                    success: function (data) {
                        if (typeof data[0].Codigo === 'undefined') {
                            alert(data);
                        } else {
                            if (data[0].Cuotas == 0) {
                                $('#ValorAddP').val("");
                            } else {
                                var valor = parseInt(data[0].Valor);
                                var valorTotal = new Intl.NumberFormat("es-CO").format(valor * cantidad);
                                $('#ValorAddP').val("$ " + valorTotal);
                            }
                        }
                    }
                });
                $("body").css({
                    'cursor': 'default'
                })

            }

            function agregarProducto() {
                var pedido = $('#pedido').val();
                var nombre = $('#nombre').val();
                var nombrePro = $('#ProductoAddP option:selected').html();
                var producto = $('#ProductoAddP').val();
                var tarifa = $('#TarifaAddP').val();
                var cantidad = $('#CantidadAddP').val();
                var valor = $('#ValorAddP').val();

                if (pedido.toString().length <= 0) {
                    $('#message').html(
                            '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                No se puede continuar sin Pedido...\n\
                            </div>');
                } else {
                    if (producto.toString().length <= 0) {
                        $('#message').html(
                                '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                    Debe elegir un producto para continuar...\n\
                                </div>');
                    } else {
                        if (tarifa.toString().length <= 0) {
                            $('#message').html(
                                    '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                        Debe elegir un tarifa para continuar...\n\
                                    </div>');
                        } else {
                            if (cantidad.toString().length <= 0) {
                                $('#message').html(
                                        '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            Debe indicar la cantidad para continuar...\n\
                                        </div>');
                            } else {
                                if (valor.toString().length <= 0 || valor == 0) {
                                    $('#message').html(
                                            '<div class="alert alert-danger alert-dismissable fade in" >\n\
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                No se ha calculado el valor, no se puede continuar...\n\
                                            </div>');
                                } else {
                                    $('#message').html("");
                                    var method = "<?= base_url(); ?>Clientes/AddProducto/";
                                    $("body").css({
                                        'cursor': 'wait'
                                    })
                                    $.ajax({
                                        type: 'post',
                                        url: method,
                                        data: {
                                            pedido: pedido, producto: producto, tarifa: tarifa, cantidad: cantidad, valor: valor, nombre: nombrePro
                                        },
                                        cache: false,
                                        beforeSend: function () {
                                            $('#message').html("");
                                            $('#btn-AddProducto').html('<i class="fa fa-save"></i> Agregando Producto...');
                                            $('#btn-AddProducto').prop('disabled', true);
                                        },
                                        success: function (data) {
                                            $('#btn-AddProducto').html('<i class="fa fa-save"></i> Agregar Producto');
                                            if (data == 1) {
                                                $('#message').html(
                                                        '<div class="alert alert-success alert-dismissable fade in">\n\
                                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                            Se agregó el Producto <strong>' + $('#ProductoAddP option:selected').html() + '</strong> al Cliente <strong>' + nombre + '</strong>\n\
                                                        </div>');
                                                setTimeout('document.location.reload()', 1500);
                                                //location.href = "<?= base_url("Pagos/Cliente/"); ?>" + pag_cli + "/";
                                            } else {
                                                $('#message').html(
                                                        '<div class="alert alert-danger alert-dismissable fade in">\n\
                                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                            <strong>Error</strong><br />' + data + '\n\
                                                        </div>');
                                            }
                                        }

                                    });
                                    return false;
                                }
                            }
                        }
                    }
                }



            }

        </script>

