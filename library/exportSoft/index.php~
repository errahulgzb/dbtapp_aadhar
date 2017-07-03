<?php

require_once __DIR__."/vendor/autoload.php";

use Dompdf\Dompdf

;

$pdf=new Dompdf;
$pdf->loadHtml("<h1>Hello World !!!</h1>");

$pdf->render();
$pdf->stream();
