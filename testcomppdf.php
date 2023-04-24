<?php 
// path to compressed PDF file
$input_file = 'compressed.pdf';
$input_file = "C:/wamp64/www/digitalsignature/systemtemplates/CertificateOfCompletion.pdf";
// path to uncompressed output file
//$output_file = 'uncompressed.pdf';
$output_file = "C:/wamp64/www/digitalsignature/systemtemplates/CertificateOfCompletiondddd.pdf";


// use FPDI to decompress the PDF file
require("vendor/autoload.php");
use setasign\Fpdi\Fpdi;
$pdf = new Fpdi();
$pdf->setSourceFile($input_file);
$page_count = $pdf->getTemplatePageCount();
for ($i = 1; $i <= $page_count; $i++) {
    $tplidx = $pdf->importPage($i);
    $pdf->AddPage();
    $pdf->useTemplate($tplidx);
}
$pdf->Output($output_file, "F");

// check if the output file exists
if (file_exists($output_file)) {
   // success
} else {
   // failed to create output file
}

?>