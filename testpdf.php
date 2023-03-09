<?php 
/* require_once('fpdf/fpdf.php');
require_once('fpdi/src/fpdi.php'); */
require("vendor/autoload.php");
// path of PDF file
//$fullPathToFile = "sample.pdf";

use setasign\Fpdi\Fpdi;
// use setasign\Fpdf\Fpdf;
//$fullPathToFile = "C:/wamp64/www/digitalsignature/userassets/mydocuments/1673874254153097/5183472c3a4573cd1d85b040bf3edcf0.pdf";
$totalNumPages = 0;
class PDF extends FPDI {

    var $fileIndex;
    var $currentPage = 1;
    var $numPages;
    var $tplId;
    // public $totalNumPages;

    var $pageSize;


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

/*if($_GET["i"]){*/
        
    $datadd = Array
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

        //$pdf->nextPage();
        
        //$pdf->Output();
        //die;
        $totalNumPages = $pdf->getTotalPages();


        /*        
        page:1, top:117, left:57,default_value:Text
        page:1, top:330, left:786,default_value:Text 2
        page:2, top:2, left:843,default_value:Text 4
        page:2, top:4, left:3,default_value:Text 3
        page:1, top:666, left:540,default_value:Text
        page:1, top:117, left:57,default_value:Text
        page:1, top:330, left:786,default_value:Text 2
        page:2, top:2, left:843,default_value:Text 4
        page:2, top:4, left:3,default_value:Text 3
        page:1, top:666, left:540,default_value:Text

        Array
        (
            [width] => 209.88865255556 //800
            [height] => 297.01065961111 //1200
            [0] => 209.88865255556
            [1] => 297.01065961111
            [orientation] => P
        )

        */
       /*
        $pdf->nextPage();
        $dimarr = $pdf->getPageSize();
      //  echo "dimarr:<pre>"; print_r($dimarr);
        $el = 786;
        $et = 330;
        


        $pdf->SetFont("courier", "", 13);
        $pdf->SetTextColor(220, 20, 60);
        //$pdf->Text(13, 30, "Text"); //  page:1, top:117, left:57,default_value:Text

        $nwl = ($dimarr["width"]-57)/10;
        $nwt = ($dimarr["height"]-117)/6; 

        $pdf->Text(0, 10, "T");
        $pdf->Text(207, 10, "T");
        $pdf->Text($dimarr["width"]/2, $dimarr["height"]/2, "T");*/
/*
        $pdf->SetFont("courier", "", 13);
        $pdf->SetTextColor(220, 20, 60);
        //$pdf->Text(786/4.2, 330, "Text 2");
        //$pdf->Text(170, 78, "Text 2");

        $nwl = ($dimarr["width"]-786)/10;
        $nwt = ($dimarr["height"]-330)/6; 

        $pdf->Text(180, 82, "Text 2");

        $pdf->SetFont("courier", "", 13);
        $pdf->SetTextColor(220, 20, 60);
        $pdf->Text(540/4.2, 666, "Text");

     


        $pdf->nextPage();
        $dimarr = $pdf->getPageSize();
        echo "dimarr2:<pre>"; print_r($dimarr);

        $pdf->SetFont("courier", "", 13);
        $pdf->SetTextColor(220, 20, 60);
        $pdf->Text(843/4.2, 2, "Text");
        $pdf->SetFont("courier", "", 13);
        $pdf->SetTextColor(220, 20, 60);
        $pdf->Text(3/4.2, 4, "Text");*/
       //$pdf->Output();
        //die;
        
        /*
        for($i = 1; $i <= $totalNumPages; $i++){
            //$i = 2;
            //$fileIndex = $pdf->importPage($i);
            $pdf->nextPage();

            $dd = array("vvv", "dfgdg", "dfgdf");
            foreach($dd as $ddd){
                $pdf->SetFont("helvetica", "", 13);
                $pdf->SetTextColor(220, 20, 60);
                $pdf->Text(0, 3.5, "head");

                $pdf->SetFont("helvetica", "", 13);
                $pdf->SetTextColor(220, 20, 60);
                $pdf->Text(0, 5, "head");

                $pdf->SetFont("helvetica", "", 13);
                $pdf->SetTextColor(220, 20, 60);
                $pdf->Text(0, 8, "head");    
            }
       }    
            $pdf->Output();
    die;*/
       

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
                
                /*
                //echo "<pre>"; print_r($sizeArr); die;
                $pw = (int) $sizeArr["width"];
                $ph = (int) $sizeArr["height"];
                $cw = (int) $pw/2;
                $ch = (int) $ph/2;

                //echo  $pw.",".$ph.",".$cw.",".$ch; die;
                
                if($left >= $pw){
                    //left out
                    $newLeft = $pw - 10;
                }else{
                    if($left >= $cw && $left <= $pw ){
                        //greater than equals to left center
                        $newLeftDiv = $left/4;
                        if($newLeftDiv > $cw){
                            $newLftMinus = $newLeftDiv - $cw;
                            $newLeft = $pw - $newLftMinus;
                        }else{
                            $newLeft = $cw + $newLeftDiv;
                        }
                        //$newLeft = $cw + $newLeft;
                        //$newLeft = $pw - $newLeft;
                    }else{
                        //less than to left center
                        $newLeft = $left/4;
                        //$newLeft = $cw + $newLeft;
                    }
                }
                
                
                
                if($top >= $ph){
                    //top out
                    $newTop = $ph - 10;
                }else{
                    
                    if($top >= $ch && $top <= $ph){
                        //greater than equals to left center
                        $newTopDiv = $top/4;
                        if($newTopDiv > $ch){
                            $newTopMinus = $newTopDiv - $ch;
                            //$newTop = $ph - $newTopMinus;
                            $newTop = $ch - $newTopMinus;
                        }else{
                            $newTop = $ch + $newTopMinus;
                        }
                       // $newTop = $ch + $newLeft;
                        //$newTop = $ph - $newTop;
                    }else{
                        //less than to left center
                        $newTop = $top/4;
                        //$newLeft = $cw + $newLeft;
                    }
                }

                echo  "page:".$i.",pw:".$pw.",ph:".$ph.",cw:".$cw.",ch:".$ch.",left:".$left.",left/4:".($left/4).",newLeft:".$newLeft.",top:".$top.",top/4:".($top/4).",newTop:".$newTop.",default_value:".$default_value."<br>";
                */
                //$newLeft = (int) $left - 140;
                //$newTop = (int) $top - 58;

                //$newLeft = $left / 4;
                //$newTop = $top / 4.1;
                //$newLeft = $left / 4.4;
                //$newTop = $top / 4.14;
                

                //$newLeft = 25;
                //$newLeft = 0; //initial left
                //$newTop = 2.7; //initial top

                //echo "page:".$page.", top:".$top.", left:".$left.",default_value:".$default_value."<br>";


                //$newTop = $top; //element page top

                $newFontSize = str_replace("px", "", $font_size);    
                $newFontSize = (int) $newFontSize - 2.5;
                $newFontSize = 12;
                //$newFontSize = 8.50;
                //echo $newFontSize; die;
                //echo $newLeft."--".$newTop; die;

                
                    
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
/*}else{
    echo "else";
}*/
