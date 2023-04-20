<?php
$htmlStr = file_get_contents('certificateofcompleteion.html');
/*
require __DIR__.'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML($htmlStr);
$html2pdf->output();
*/

/*
require __DIR__.'/vendor/autoload.php';
//require "pdfcrowd.php";

try
{
    // create the API client instance
    //$client = new \Pdfcrowd\HtmlToPdfClient("dineshgiri", "b5b485fc13af8cf903ab72e3794b325f");
    $client = new \Pdfcrowd\HtmlToPdfClient("dineshgiri", "b5b485fc13af8cf903ab72e3794b325f");

    // run the conversion and write the result to a file
    //$client->convertFileToFile("certificateofcompleteion.html", "resultdd.pdf");
    $client->convertStringToFile($htmlStr, "result.pdf");
}
catch(\Pdfcrowd\Error $why)
{
    // report the error
    error_log("Pdfcrowd Error: {$why}\n");

    // rethrow or handle the exception
    throw $why;
}
*/


// (A) LOAD MPDF
require "vendor/autoload.php";
$mpdf = new \Mpdf\Mpdf();
// PORTRAIT BY DEFAULT, WE CAN ALSO SET LANDSCAPE
// $mpdf = new \Mpdf\Mpdf(["orientation" => "L"]);
 
// (B) OPTIONAL META DATA + PASSWORD PROTECTION
//$mpdf->SetTitle("Document Title");
//$mpdf->SetAuthor("Jon Doe");
//$mpdf->SetCreator("Code Boxx");
//$mpdf->SetSubject("Demo");
//$mpdf->SetKeywords("Demo", "Testing");
// $mpdf->SetProtection([], "user", "password");
 
// (C) THE HTML
// OR WE CAN JUST READ FROM A FILE
// $html = file_get_contents("PAGE.HTML");
 
// (D) WRITE HTML TO PDF
$mpdf->WriteHTML($htmlStr);
 
// (E) OUTPUT
// (E1) DIRECTLY SHOW IN BROWSER
$mpdf->Output();


?>