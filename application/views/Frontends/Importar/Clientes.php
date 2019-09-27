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
            <a href="#page-stats1" class="panel-heading" data-toggle="collapse"><?= $subtitle1; ?></a>
            <div id="page-stats1" class="panel-collapse panel-body collapse in"> 
                <div class="row">
                    <div class="col-md-5">                                        
                        <form action="<?php echo site_url("Importar/ClientesUp"); ?>" method="post" enctype="multipart/form-data" id="importFrm" accept-charset="utf-8">
                            <div class="form-group">
                                <label>Pon aquí el excel con un los datos de los Clientes</label>                                
                                <br /><br />
                                <input type="file" name="fileUp" id="fileUp" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" /> 
                            </div>                            
                            <div class="form-group">
                                <label for="encabezado">El archivo tiene Encabezado</label>
                                <input id="encabezado" type="checkbox" name="encabezado" checked="true">
                            </div>
                            <div class="col-md-8 col-md-offset-2">
                                <button type="submit" id="btn-fileup" name="btn-fileup" class="btn btn-block btn-primary"><i class="fa fa-upload"></i> Subir</button>
                            </div>
                        </form>
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
            });
        </script>