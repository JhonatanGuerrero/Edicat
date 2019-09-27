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
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dato</label>
                                    <input type="text" id="" name="" value="<?= "Plantilla"; ?>" class="form-control" readonly style="background-color: #fff;">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12" id="message">
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>  
        <script>
            $(document).ready(function () {
//                $('#<?= $Controller; ?>').DataTable({
//                    responsive: true,
//                    scrollX: true,
//                    language: {
//                        url: "<?= base_url('Public/assets/'); ?>/lib/Datetables.js/Spanish.json"
//                    }
//                });
            });
        </script>

