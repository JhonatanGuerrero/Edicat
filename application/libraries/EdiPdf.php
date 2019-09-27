<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require 'tcpdf/tcpdf.php';

class EdiPdf extends TCPDF {

    function __construct() {
        parent::__construct();
        $CI = & get_instance();
    }

}

?>