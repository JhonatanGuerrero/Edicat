<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('Modules/head');
$this->load->view('Modules/navbar-simple');
$this->load->view('Frontends/' . $contenido);
$this->load->view('Modules/footer-simple');
?>