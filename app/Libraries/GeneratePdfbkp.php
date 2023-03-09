<?php
namespace App\Libraries;
use setasign\Fpdi\Fpdi;

/*use setasign\Fpdf\Fpdf;
use setasign\Fpdi\FpdfTpl;
*/

class GeneratePdf{

    var $pdf;
    

    //public $fileIndex;
    //public $currentPage = 1;
    //public $tplId;
    //public $fullPathToFile;
    //public $numPages;

    function __construct(){
        //Library Object
        $this->pdf = new Fpdi();
    
        
    }
    
    function preparePdf($rootFolder, $srcFilePath, $data){
        
        $numPages = $this->pdf->setSourceFile($srcFilePath);
        
       for($i = 1; $i <= $numPages; $i++){
            //$i = 2;
            $fileIndex = $this->pdf->importPage($i);
            
            //$this->pdf->SetTopMargin(2000);
            //$this->pdf->AddPage();
            $orientation = '';
            $size = '';
            $rotation = 0;
            $this->pdf->AddPage($orientation, $size, $rotation);
            
            $x = 0;
            $y = 0;
            $w = null;
            $h = null;
            $adjustpagesize = true;
            //$this->pdf->useTemplate($fileIndex, 0, 0,200);
            //$this->pdf->useTemplate($fileIndex, $x, $y, $w, $h, $adjustpagesize);
            $this->pdf->useImportedPage($fileIndex);
            
            // $this->pdf->useTemplate($fileIndex);

            //echo "<pre>"; print_r($data); die;

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
                

                    $styleArr = explode(";", $style);
                    $styleAttributes = array();
                    foreach($styleArr as $tmpStyleRw){
                        $tmpStyleRwVal = explode(":", $tmpStyleRw);
                        $attrName = $tmpStyleRwVal[0];
                        $attrValue = $tmpStyleRwVal[1];

                        $styleAttributes[trim($attrName)] = trim($attrValue);
                    }

                    //echo "styleAttributes:<pre>"; print_r($styleAttributes); die;


                    $left = $styleAttributes["left"];
                    $top = $styleAttributes["top"];
                    $left = str_replace("px", "", $left);
                    $top = str_replace("px", "", $top);
                    
                    
                    $top = $pageTop;


                    $left = (int) $left;
                    $top = (int) $top;
                    //$newLeft = (int) $left - 140;
                    //$newTop = (int) $top - 58;

                    $newLeft = $left / 4.2;
                    $newTop = $top; // / 1;
                    //$newLeft = $left / 4.4;
                    //$newTop = $top / 4.14;
                    

                    //$newLeft = 25;
                    //$newLeft = 0; //initial left
                    //$newTop = 2.7; //initial top

                    //echo "top:".$top.", left:".$left; die;


                    //$newTop = $top; //element page top

                    $newFontSize = str_replace("px", "", $font_size);    
                    $newFontSize = (int) $newFontSize - 2.5;
                    //$newFontSize = 8.50;
                    //echo $newFontSize; die;
                    //echo $newLeft."--".$newTop; die;

                    
                        
                    // add content to current page
                    if($i == $page){
                        /*if($elmType == "signature" || $elmType == "signaturein"){
                            //$this->pdf->Image( $rootFolder."userassets/mydocuments/1673874254153097/logo.png", 100, 60, 50, 50);
                        }else{*/
                            
                            $this->pdf->SetFont("courier", "", $newFontSize);
                            $this->pdf->SetTextColor(0, 0, 0);
                            $this->pdf->Text($newLeft, $newTop, $default_value);
                        /*}*/
                    }

            }
    
       }
        
        /*
        $fileIndex = $this->pdf->importPage(1);
		$this->pdf->AddPage();
		$this->pdf->useTemplate($fileIndex, 0, 0,200);
		// add content to current page
		$this->pdf->SetFont("helvetica", "", 20);
		$this->pdf->SetTextColor(220, 20, 60);
		$this->pdf->Text(50, 20, "I should not be here!");
		//$this->pdf->Image( $rootFolder."userassets/mydocuments/1673874254153097/logo.png", 100, 60, 50, 50);

        
		// move to next page and add content
        $fileIndex = $this->pdf->importPage(2);
        $this->pdf->AddPage();
		$this->pdf->useTemplate($fileIndex, 0, 0,200);

		$this->pdf->SetFont("arial", "", 15);
		$this->pdf->SetTextColor(65, 105, 225);
		$this->pdf->Text(50, 20, "Me neither!!!");
        */
		//show the PDF in page
		$this->pdf->Output();
        //$this->pdf->Output($rootFolder."userassets/mydocuments/1673874254153097/generated.pdf", "F");

        

    }
    

}




/*
require("vendor/autoload.php");

use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;
*/
/*
use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;
use setasign\Fpdi\FpdfTpl;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class GeneratePdf  extends FPDI{
   
    public $fileIndex;
    public $currentPage = 1;
    public $tplId;
    public $fullPathToFile;
    public $numPages;
    function __construct($p){
        //$this->fullPathToFile = "http://localhost/digitalsignature/$p";
        $this->fullPathToFile = $p;
        //$this->Header();
        // echo "<pre>";
        // print_r($this->fullPathToFile);
        // die;
    }

    function createPdf(){
        //$fullPathToFile = "sample.pdf";
    }

    function Header() {

        // global $fullPathToFile;

        if (is_null($this->fileIndex)) {

            $this->numPages = $this->setSourceFile($this->fullPathToFile);
            $this->fileIndex = $this->importPage(1);

        } 
        
        $this->useTemplate($this->fileIndex, 0, 0,200);

        
        echo "<pre>";
        print_r($this->numPages);
        die;
        
    }

    function nextPage() {
        
        if($this->currentPage != 1) {
            $this->fileIndex = $this->importPage($this->currentPage);
        }
        
        echo "<pre>";
        print_r($this->importPage(2));
        die;
        
        $this->addPage();

        return ++$this->currentPage;
    }

}
*/

?>