<?php
namespace App\Libraries;
use setasign\Fpdi\Fpdi;

/*
use setasign\Fpdf\Fpdf;
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
    

    function prepareCompletionCertificate($rootFolder, $srcFilePath, $data, $signerDocumentId, $secretFolder){
        
        $documentId = $data["documentId"];
        $documentName = $data["documentName"];
        $sentAt = $data["sentAt"];
        $title = $data["title"];
        $signType = $data["signType"];
        $status = $data["status"];
        $signerCount = $data["signerCount"];
        $hash = $data["hash"];
        $recipientName = $data["recipientName"];
        $signatureType = $data["signatureType"];
        $timeStamp = $data["timeStamp"];
        $recipientEmail = $data["recipientEmail"];
        $signatureAuth = $data["signatureAuth"];
        $signaturePng = $data["signaturePng"];
        $signedAt = $data["signedAt"];
        $signedByName = $data["signedByName"];
        $signedByEmail = $data["signedByEmail"];
        $completedAt = $data["completedAt"];
       
        $numPages = $this->pdf->setSourceFile($srcFilePath);
        
        for($i = 1; $i <= $numPages; $i++){
            
            $fileIndex = $this->pdf->importPage($i);
            
            $this->pdf->AddPage();
            
            $adjustpagesize = true;
            
            $this->pdf->useTemplate($fileIndex,0,0,237, null,true);
            
            $this->pdf->SetFont("helvetica", "", 11);
            $this->pdf->SetTextColor(0, 0, 0);

            //Summary
            //Document Id
            $this->pdf->Text(12.5, 75, $documentId);   

            //Document Name
            $this->pdf->Text(12.5, 93, $documentName);

            //Page Count
            $this->pdf->Text(12.5, 113, $numPages);

            //Sent At
            $this->pdf->Text(12.5, 132, $sentAt);

            //Title
            $this->pdf->Text(12.5, 151, $title);

            //Type
            $this->pdf->Text(120, 75, $signType);   

            //Status
            $this->pdf->Text(120, 93, $status);

            //Signer/Reviwer Count
            $this->pdf->Text(120, 113, $signerCount);

            //Completed At
            $this->pdf->Text(120, 132, $completedAt);

            //Hash
            $this->pdf->SetFont("helvetica", "", 8);
            $this->pdf->SetXY(119,147);
            $this->pdf->MultiCell(107, 3, $hash);
            
            //Recipients
            $this->pdf->SetFont("helvetica", "", 11);
            //Name
            $this->pdf->Text(12.5, 198, $recipientName);   

            //signature type
            $this->pdf->Text(12.5, 218, $signatureType);

            //Timestamp
            $this->pdf->Text(12.5, 237, $timeStamp);
            
            //Email ID
            $this->pdf->Text(120, 198, $recipientEmail);   

            //Signature Auth
            $this->pdf->Text(120, 218, $signatureAuth);

            //Signature
            $x = 110;
            $y = 232;
            $w = 50; //50;
            $h = 18; //18;
            
            $this->pdf->Image($signaturePng , $x, $y, $w, $h);

            //Audit Trial
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->SetTextColor(0, 0, 0);
            
            //Signed
            $this->pdf->Text(12.5, 284, $signedAt);

            //Signer Email
            $this->pdf->SetFont("helvetica", "", 11);
            $this->pdf->Text(120, 277, $signedByName);
            
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Text(120, 284, $signedByEmail);

            //Completed
            $this->pdf->Text(12.5, 303, $completedAt);
            $this->pdf->SetFont("helvetica", "", 11);
            $this->pdf->Text(120, 300, "Document has been completed.");
            

       }
        
        //show the PDF in page
		//$this->pdf->Output();
        $this->pdf->Output($rootFolder."$secretFolder/$signerDocumentId/$signerDocumentId"."_CertificateOfCompletion.pdf", "F");
    }

}

?>