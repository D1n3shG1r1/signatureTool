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
        
        $pxInMM = 3.77; //1mm equals to 3.77px
        $mmInPx = 0.26; //1px equals to 0.26mm
       for($i = 1; $i <= $numPages; $i++){
            //$i = 2;
            $fileIndex = $this->pdf->importPage($i);
            
            $orientation = '';
            $size = '';
            $rotation = 0;
            $this->pdf->AddPage($orientation, $size, $rotation);
            
            $adjustpagesize = true;
            
            $this->pdf->useTemplate($fileIndex,0,0,237, null,true);
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
                    
                    $newLeft = $left * $mmInPx;
                    $newLeft = $newLeft + 3;
                    $newTop = $top * $mmInPx;
                    $newTop = $newTop + 7;

                    $newFontSize = str_replace("px", "", $font_size);    
                    $newFontSize = (int) $newFontSize - 2.5;
                    $newFontSize = 12;
    
                    // add content to current page
                    if($i == $page){
                        if($elmType == "signature" || $elmType == "signaturein"){
                            $x = $newLeft;
                            $y = $newTop-6;
                            $w = 45;
                            $h = 18;
                            
                            //$this->pdf->Image( $rootFolder."userassets/mydocuments/1673874254153097/logo.png", 100, 60, 50, 50);
                            $this->pdf->Image( $rootFolder."650d5885e051cbf1361781a0366dab198ce52007/ccca43b66c9b41b249c46d2ba96612a4/sign.png", $x, $y, $w, $h);
                        }else{
                            
                            $this->pdf->SetFont("courier", "", $newFontSize);
                            $this->pdf->SetTextColor(0, 0, 0);
                            $this->pdf->Text($newLeft, $newTop, $default_value);
                        }
                    }

            }
    

            
            /*add hash key*/
            $this->pdf->SetFont("courier", "", 10);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Text(3, 5, "");
       }
        
       
		//show the PDF in page
		//$this->pdf->Output();
        $this->pdf->Output($rootFolder."userassets/mydocuments/1673874254153097/generated.pdf", "F");

        
    }
    

}

?>