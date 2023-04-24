<?php 
/* require_once('fpdf/fpdf.php');
require_once('fpdi/src/fpdi.php'); */
require("vendor/autoload.php");
// path of PDF file
//$fullPathToFile = "sample.pdf";

//use setasign\Fpdi\Fpdi;
use setasign\Fpdi;
// use setasign\Fpdf\Fpdf;
//$fullPathToFile = "C:/wamp64/www/digitalsignature/userassets/mydocuments/1673874254153097/0f07f84469b834c989fc085708e614fc.pdf";
$fullPathToFile = "C:/wamp64/www/digitalsignature/systemtemplates/CertificateOfCompletion.pdf";
$totalNumPages = 0;

$parser = 'fpdi-pdf-parser';

class PDF extends FPDI\FPDI {

    var $fileIndex;
    var $currentPage = 1;
    var $numPages;
    var $tplId;
    // public $totalNumPages;

    var $pageSize;


     /**
     * @var string
     */
    protected $pdfParserClass = null;

    /**
     * Set the pdf reader class.
     *
     * @param string $pdfParserClass
     */
    public function setPdfParserClass($pdfParserClass)
    {
        $this->pdfParserClass = $pdfParserClass;
    }

    /**
     * Get a new pdf parser instance.
     *
     * @param Fpdi\PdfParser\StreamReader $streamReader
     * @return Fpdi\PdfParser\PdfParser|setasign\FpdiPdfParser\PdfParser\PdfParser
     */
    protected function getPdfParserInstance(Fpdi\PdfParser\StreamReader $streamReader)
    {
        if ($this->pdfParserClass !== null) {
            return new $this->pdfParserClass($streamReader);
        }

        return parent::getPdfParserInstance($streamReader);
    }

    /**
     * Checks what kind of cross-reference parser is used.
     *
     * @return string
     */
    public function getXrefInfo()
    {
        foreach (array_keys($this->readers) as $readerId) {
            $crossReference = $this->getPdfReader($readerId)->getParser()->getCrossReference();
            $readers = $crossReference->getReaders();
            foreach ($readers as $reader) {
                if ($reader instanceof \setasign\FpdiPdfParser\PdfParser\CrossReference\CompressedReader) {
                    return 'compressed';
                }

                if ($reader instanceof \setasign\FpdiPdfParser\PdfParser\CrossReference\CorruptedReader) {
                    return 'corrupted';
                }
            }
        }

        return 'normal';
    }



    function Header() {
        global $fullPathToFile;
        //global $totalNumPages;

        if (is_null($this->fileIndex)) {

          $this->numPages = $this->setSourceFile($fullPathToFile);
          $this->fileIndex = $this->importPage(1);
            // $totalNumPages = $this->numPages;
            //echo "<pre>"; print_r($this) die;
           
        }
        $dimArr = $this->getTemplateSize($this->fileIndex);
        $this->pageSize = $dimArr;
       //$this->useTemplate($this->fileIndex, 0, 0,200);
       //$this->useTemplate($this->fileIndex, 0, 0,$dimArr["width"],$dimArr["height"], TRUE);
       $this->useTemplate($this->fileIndex,0,0,237, null,true);

    }

    function nextPage() {
       // $this->getTotalPages();
       if($this->currentPage != 1) {
            $this->fileIndex = $this->importPage($this->currentPage);
            
          // echo "if";
        }
       // echo $this->currentPage."<br>";
        $this->addPage();

        return ++$this->currentPage;
    }

    function getTotalPages(){
        global $fullPathToFile;
        $this->numPages = $this->setSourceFile($fullPathToFile);
        return  $this->numPages;
    }


    function getPageSize(){
        
        return  $this->pageSize;
    }

} 

        
    $data = Array
		(
        
            Array
                (
                    "elmType" =>"textbox",
                    "page" => 1,
                    "pageTop" => 117,
                    "style" => "z-index: 102; top: 117px; left: 57px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);",
                    "font_size" => "13px",
                    "font_family" => "CourierPrime-Regular",
                    "font_style" => "normal",
                    "font_weight" => "normal",
                    "text_decoration" => "none",
                    "default_value" => "Text",
                    "default_user" =>"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"
                ),

                Array
                (
                    "elmType" => "textbox",
                    "page" => 1,
                    "pageTop" => 330,
                    "style" => "z-index: 104; top: 330px; left: 786px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);",
                    "font_size" => "13px",
                    "font_family" => "CourierPrime-Regular",
                    "font_style" => "normal",
                    "font_weight" => "normal",
                    "text_decoration" => "none",
                    "default_value" => "Text 2",
                    "default_user" => "Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"
                ),

            Array
                (
                    "elmType" => "textbox",
                    "page" => 2,
                    "pageTop" => 2,
                    "style" => "z-index: 116; top: 1276px; left: 843px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);",
                    "font_size" => "13px",
                    "font_family" => "CourierPrime-Regular",
                    "font_style" => "normal",
                    "font_weight" => "normal",
                    "text_decoration" => "none",
                    "default_value" => "Text 4",
                    "default_user" => "Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"
                ),

            Array
                (
                    "elmType" => "textbox",
                    "page" => 2,
                    "pageTop" => 4,
                    "style" => "z-index: 114; top: 1278px; left: 3px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);",
                    "font_size" => "13px",
                    "font_family" => "CourierPrime-Regular",
                    "font_style" => "normal",
                    "font_weight" => "normal",
                    "text_decoration" => "none",
                    "default_value" => "Text 3",
                    "default_user" => "Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"
                ),

            Array
                (
                    "elmType" => "textbox",
                    "page" => 1,
                    "pageTop" => 666,
                    "style" => "z-index: 110; top: 666px; left: 540px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);",
                    "font_size" => "13px",
                    "font_family" => "CourierPrime-Regular",
                    "font_style" => "normal",
                    "font_weight" => "normal",
                    "text_decoration" => "none",
                    "default_value" => "Text",
                    "default_user" => "Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"
                )	
    
				
		
		);

        $pdf = new PDF();

        if ($parser === 'default') {
            $pdf->setPdfParserClass(Fpdi\PdfParser\PdfParser::class);
        }

        $totalNumPages = $pdf->getTotalPages();

       $pxInMM = 3.77; //1mm equals to 3.77px
       $mmInPx = 0.26; //1px equals to 0.26mm
        for($i = 1; $i <= $totalNumPages; $i++){
            $pdf->nextPage();
            $sizeArr = $pdf->getPageSize();
            foreach($data as $k => $vl){

                $elmType = $vl["elmType"];
                $page = $vl["page"];
                $pageTop = $vl["pageTop"];
                $style = $vl["style"];
                $font_size = $vl["font_size"];
                $font_family = $vl["font_family"];
                $font_style = $vl["font_style"];
                $font_weight = $vl["font_weight"];
                $text_decoration = $vl["text_decoration"];
                $default_value = $vl["default_value"];
                $default_user = $vl["default_user"];
            

                $styleArr = explode("; ", $style);
                $styleAttributes = array();
                foreach($styleArr as $tmpStyleRw){
                    $tmpStyleRwVal = explode(":", $tmpStyleRw);
                    $attrName = $tmpStyleRwVal[0];
                    $attrValue = $tmpStyleRwVal[1];

                    $styleAttributes[trim($attrName)] = trim($attrValue);
                }

                // echo "styleAttributes:<pre>"; print_r($styleArr); die;


                $left = $styleAttributes["left"];
                $top = $styleAttributes["top"];
                $left = str_replace("px", "", $left);
                $top = str_replace("px", "", $top);
                
                
                $top = $pageTop;


                $left = (int) $left;
                $top = (int) $top;
                
                $newLeft = $left * $mmInPx;
                $newLeft = $newLeft + 2;
                $newTop = $top * $mmInPx;
                $newTop = $newTop + 7;
                
                

                $newFontSize = str_replace("px", "", $font_size);    
                $newFontSize = (int) $newFontSize - 2.5;
                $newFontSize = 12;
                    
                // add content to current page
                if($i == $page){
                    /*if($elmType == "signature" || $elmType == "signaturein"){
                        //$this->pdf->Image( $rootFolder."userassets/mydocuments/1673874254153097/logo.png", 100, 60, 50, 50);
                    }else{*/
                        
                        $pdf->SetFont("courier", "", $newFontSize);
                        $pdf->SetTextColor(0, 0, 0);
                        //$pdf->SetXY($newLeft, $newTop);
                        //$pdf->Write(0, $default_value);
                        
                        $pdf->Text($newLeft, $newTop, $default_value);
                    /*}*/
                }

        }
       
       }



       $xrefInfo = $pdf->getXrefInfo();

        if ($xrefInfo === 'compressed') {
            $pdf->SetTextColor(72, 179, 84);
            $pdf->Write(5, 'This document uses new PDF compression technics introduced in PDF version 1.5 ;-)');
        } elseif ($xrefInfo === 'corrupted') {
            $pdf->SetTextColor(72, 179, 84);
            $pdf->Write(5, 'This document is corrupted but can be read and repaired with the FPDI PDF-Parser add-on.');
        } else {
            $pdf->SetTextColor(182);
            $pdf->Write(5, 'This document should also work with the free parser version ;-)');
        }


       //show the PDF in page
       $pdf->Output();



    /*
    // initiate PDF
    $pdf = new PDF();

   
    // go to first page
    $pdf->nextPage();

    // add content to current page
    $pdf->SetFont("helvetica", "", 13);
    $pdf->SetTextColor(220, 20, 60);
    $pdf->Text(0, 3.5, "head");
    $pdf->Text(13, 30, "I should not be here!");
    $pdf->Text(169.5, 30, "Text2");


    // move to next page and add content
    $pdf->nextPage();

    $pdf->SetFont("arial", "", 13);
    $pdf->SetTextColor(65, 105, 225);
    $pdf->Text(13, 30, "hello!");
    $pdf->Text(169.5, 30, "Text3");
    $pdf->Text(0, 3.5, "head");
    //show the PDF in page
    $pdf->Output();
    */

