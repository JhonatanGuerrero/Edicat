<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="content">
    <div class="header">        
        <?php //$this->load->view('Modules/notifications'); ?>
        <h1 class="page-title" style="font-size: 2em;"><?= $title; ?>  </h1>
    </div>            
    <div class="main-content">
        <div class="panel panel-default">
            <div id="page-stats" class="panel-collapse panel-body collapse in">
                <div class="row">
                    <div class="col-md-6">
                        <table id="<?= $Controller; ?>" class="table table-striped table-bordered" style="width:100%;">
                            <tr>
                                <td colspan="2"><h3 class="text-center">Datos de Contacto</h3></td>
                            </tr>
                            <tr>
                                <th>Desarrollado por:</th>
                                <td><?= $this->config->item('NAME_AUTHOR'); ?></td>
                            </tr>   
                            <tr>
                                <th>Correo Electrónico:</th>
                                <td><?= $this->config->item('PAGE_AUTHOR_MAIL'); ?></td>
                            </tr>
                            <tr>
                                <th>Teléfono de Contacto:</th>
                                <td><?= $this->config->item('PAGE_AUTHOR_CEL'); ?></td>
                            </tr>
                        </table>
                    </div>    

                    <div class="col-md-6">
                        <form>
                            <div class="col-md-12"> 
                                <div class="form-group">
                                    <label>Usuario</label>
                                    <input type="text" class="form-control" readonly value="<?= $this->session->userdata('Nombre'); ?>">                                    
                                </div>
                                <div class="form-group">
                                    <label>Notas/Observaciones</label>
                                    <textarea rows="6" class="form-control" name="Observaciones" id="Observaciones" style="resize: none;"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">                        
                                <div class="form-group pull-right">
                                    <button type="submit" id="btn-observaciones" name="btn-observaciones" class="btn btn-primary"><i class="fa fa-envelope"></i> Reportar Inconveniente</button>
                                </div>
                            </div>
                        </form>
                    </div>  

                </div>       
            </div>                            
        </div>                   
    </div>