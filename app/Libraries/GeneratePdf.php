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
    
    function preparePdf($rootFolder, $srcFilePath, $data, $hashCode, $signerDocumentId, $secretFolder){
        
        //$userId = "1673874254153097";
        
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
                            
                            $this->pdf->Image( $rootFolder."$secretFolder/$signerDocumentId/sign.png", $x, $y, $w, $h);
                            
                        }else{
                            
                            $this->pdf->SetFont("courier", "", $newFontSize);
                            $this->pdf->SetTextColor(0, 0, 0);
                            $this->pdf->Text($newLeft, $newTop, $default_value);
                        }
                    }

            }
    

            
            /*add hash key*/
            /*$hashCode, $signerDocumentId*/
            $this->pdf->SetFont("courier", "", 10);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Text(3, 5, "Document ID:".$signerDocumentId);
       }
        
       
		//show the PDF in page
		//$this->pdf->Output();
        $this->pdf->Output($rootFolder."$secretFolder/$signerDocumentId/$signerDocumentId.pdf", "F");
        
    }
    

    function prepareCompletionCertificate($rootFolder, $srcFilePath, $data, $hashCode, $signerDocumentId, $secretFolder){
        //echo $srcFilePath;
         
        //echo "this->pdf:<pre>"; print_r($this->pdf);
        
        $numPages = $this->pdf->setSourceFile($srcFilePath);
        //echo 'numPages:'.$numPages;
        
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

            $this->pdf->SetFont("helvetica", "", 11);
            $this->pdf->SetTextColor(0, 0, 0);

            //Summary
            //Document Id
            $this->pdf->Text(12.5, 75, "37a3235a19a98fd3e4bf7b0bcbe74669");   

            //Document Name
            $this->pdf->Text(12.5, 93, "Certificate of completion");

            //Page Count
            $this->pdf->Text(12.5, 113, $numPages);

            //Sent At
            $this->pdf->Text(12.5, 132, "Apr 03, 2023 09:26:12 UTC");

            //Title
            $this->pdf->Text(12.5, 151, "Sample Aggrement");


            
            //Type
            $this->pdf->Text(120, 75, "37a3235a19a98fd3e4bf7b0bcbe74669");   

            //Status
            $this->pdf->Text(120, 93, "Certificate of completion");

            //Signer/Reviwer Count
            $this->pdf->Text(120, 113, $numPages);

            //Completed At
            $this->pdf->Text(120, 132, "Apr 03, 2023 09:26:12 UTC");

            //Hash
            $this->pdf->SetFont("helvetica", "", 8);
            $this->pdf->SetXY(119,147);
            $this->pdf->MultiCell(107, 3,"e0a306a4ee830b4e4b579c9530f50a045bf129f9a6223526803b489e4a77ed2bd5675c03b624530aecad4fcf696d5dac19d666e0437bd4d0e596b4c5d34ab328bkc2BcW9iJquBUm08X1mppdLG14uVQcAQyfI/m6uztK1I+APrCmbGL/tzAIQZaJWRDVJJZ4becQKV3ta0YkCfzF7DLjaS1vTcemjAQSZPpqRIanh9K4MGTdJBy10JJ0AG+yOTjjux7Kl0IEZy7SPmXa0Xn7O2L6hJorWdRFJZHwzUC6VS3xJn7dL7bQMYQf/qOpKoLoeforRuKtjKEeZbRmki3PbfWe/IB/Axu3wC9H3AQApb7f0UouJwhhsimYBvAni/fHX1fuNarJ0");
            


            //Recipients
            $this->pdf->SetFont("helvetica", "", 12);
            //Name
            $this->pdf->Text(12.5, 198, "Rashika Sapru");   

            //signature type
            $this->pdf->Text(12.5, 218, "Type");

            //Timestamp
            $this->pdf->Text(12.5, 237, "Apr 03, 2023 09:26:12 UTC");


            //Email ID
            $this->pdf->Text(120, 198, "upkit.rashikasapru@gmail.com");   

            //Signature Auth
            $this->pdf->Text(120, 218, "-");

            //Signature
            $x = 114;
            $y = 230;
            $w = 45;
            $h = 18;
            
            $this->pdf->Image( $rootFolder."systemtemplates/sign.png", $x, $y, $w, $h);


            //Audit Trial
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->SetTextColor(0, 0, 0);
            //Signed
            $this->pdf->Text(12.5, 284, "Apr 03, 2023 09:26:12 UTC");

            //Signer Email
            $this->pdf->SetFont("helvetica", "", 12);
            $this->pdf->Text(120, 277, "Rashika Sapru signed the document.");
            
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Text(120, 284, "upkit.rashikasapru@gmail.com");

            //Completed
            $this->pdf->Text(12.5, 303, "Apr 03, 2023 09:26:12 UTC");
            $this->pdf->SetFont("helvetica", "", 12);
            $this->pdf->Text(120, 300, "Document has been completed.");
            

        }


        //show the PDF in page
		$this->pdf->Output();
    }

}

?>