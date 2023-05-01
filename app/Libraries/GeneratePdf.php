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
                            
                            $this->pdf->SetFont("helvetica", "", $newFontSize);

                            $this->pdf->SetTextColor(0, 0, 0);
                            $this->pdf->Text($newLeft, $newTop, $default_value);
                        }
                    }

            }
    

            
            /*add hash key*/
            /*$hashCode, $signerDocumentId*/
            $this->pdf->SetFont("helvetica", "", 10);
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
        $this->pdf->Output($rootFolder."$secretFolder/$signerDocumentId/$signerDocumentId"."_auditlog.pdf", "F");
    }

    function prepareConsolidatePdf($rootFolder, $srcFilePath, $data, $secretFolder, $userId, $parentDocument){
        
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
                    $signerDocumentId = $vl["documentId"];
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
                            
                            $this->pdf->SetFont("helvetica", "", $newFontSize);
                            $this->pdf->SetTextColor(0, 0, 0);
                            $this->pdf->Text($newLeft, $newTop, $default_value);
                        }
                    }

            }
    
            /*add hash key*/
            /*$hashCode, $signerDocumentId*/
           /*
            $this->pdf->SetFont("courier", "", 10);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Text(3, 5, "Document ID:".$signerDocumentId);
            */
       }
    
		//show the PDF in page
		//$this->pdf->Output();
        $this->pdf->Output($rootFolder."userassets/mydocuments/$userId/$parentDocument"."_auditlog.pdf", "F");
        
    }

    function prepareConsolidateCompletionCertificate($rootFolder, $srcFilePath, $bgImg, $data, $signerDocumentId, $secretFolder){
        
        $documentId = "1a171bde8a66e9793d8a2489a6619e3d";
        $documentName = "gorise paypal last transaction1.pdf";
        $numPages = "1";
        $sentAt = "2023-04-26 12:50:36";
        $title = "Test Sign Document";
        $signType = "Signature Collection";
        $status = "Completed";
        $signerCount = "1";
        $completedAt = "2023-04-27 12:21:05";
        $hash = "f8ff2f6e3d499076c41db32925bcc91358c31c38aaa62d48baf25ad5bfa25ff61952adbd8f3a72e63fbf66af39ea40f6debbc6c2bffa01524e4df32bccf4f2e2YqQhXQWixl/ZBTOsfuSnpueujehMdhn7ykgjzN4eK4JoFlkwZqevZFycAXih5aiCaQebfGRJ/gBpU22FO3vFijBoBUwHZG+xwnzX33m/HdrklWH5KDz5aSEnr76Pn3SMrG0/HEm1WRDkIcd0K7cyDLo35kbQr9qDlUbZ3AqtTFJ28+8WhMFwjHKs1yceAFsF+DV3+RzBFz7LNA75Tm1IVtarpWPtv2slGTufBZ0lvFZQvydicWuZu4nTFsEUWlr+24rGywXAFmZ+kQ==";

        $numPages = $this->pdf->setSourceFile($srcFilePath);
        $i = 1;
        
        $fileIndex = $this->pdf->importPage($i);
        

        $orientation = '';
        $size = '';
        $rotation = 0;
        $this->pdf->AddPage($orientation, $size, $rotation);

        //$this->pdf->AddPage();
        $this->pdf->useTemplate($fileIndex,0,0,237, null,true);

        $pHeight = $this->pdf->GetPageHeight();
        $pWidth = $this->pdf->GetPageHeight();
        
        $this->pdf->SetFont("helvetica", "", 11);
        //$this->pdf->SetTextColor(0, 0, 0);
        //$this->pdf->SetTextColor(60,61,61);
        $this->pdf->SetTextColor(27,27,27);
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
        $this->pdf->SetTextColor(60,61,61);
        $this->pdf->SetXY(119,147);
        $this->pdf->MultiCell(107, 3, $hash);
        

        //Add Recipients
        $this->pdf->SetFont("helvetica", "B", 15);
        $this->pdf->Text(12, 180, "Recipients");
        $x1 = 49; 
        $y1 = 180;
        $x2 = 225;
        $y2 = 180;

        $this->pdf->Line($x1, $y1, $x2, $y2);

        $recipientsBatchArr = array(
            array(    
                array(),
                array(),
                array(
                    "Name" => "Kishan1",
                    "Email ID" => "kishan@example.com",
                    "Signature Type" => "Type",
                    "Security Authentication" => "Access Code",
                    "Timestamps" => "2023-04-27 12:21:05",
                    "Signature" => "Kishan Rathore"
                ),
                array(
                    "Name" => "Kishan1",
                    "Email ID" => "kishan@example.com",
                    "Signature Type" => "Type",
                    "Security Authentication" => "Access Code",
                    "Timestamps" => "2023-04-27 12:21:05",
                    "Signature" => "Kishan Rathore"
                )
            ),
            array(    
                array(
                    "Name" => "Dinesh2",
                    "Email ID" => "dinesh@example.com",
                    "Signature Type" => "Type",
                    "Security Authentication" => "OTP",
                    "Timestamps" => "2023-04-27 12:21:05",
                    "Signature" => "Dinesh Kumar Giri"
                ),
                array(
                    "Name" => "Kishan2",
                    "Email ID" => "kishan@example.com",
                    "Signature Type" => "Type",
                    "Security Authentication" => "Access Code",
                    "Timestamps" => "2023-04-27 12:21:05",
                    "Signature" => "Kishan Rathore"
                ),
                array(
                    "Name" => "Kishan2",
                    "Email ID" => "kishan@example.com",
                    "Signature Type" => "Type",
                    "Security Authentication" => "Access Code",
                    "Timestamps" => "2023-04-27 12:21:05",
                    "Signature" => "Kishan Rathore"
                ),
                array(
                    "Name" => "Kishan2",
                    "Email ID" => "kishan@example.com",
                    "Signature Type" => "Type",
                    "Security Authentication" => "Access Code",
                    "Timestamps" => "2023-04-27 12:21:05",
                    "Signature" => "Kishan Rathore"
                )
                ),
                array(    
                    array(
                        "Name" => "Dinesh3",
                        "Email ID" => "dinesh@example.com",
                        "Signature Type" => "Type",
                        "Security Authentication" => "OTP",
                        "Timestamps" => "2023-04-27 12:21:05",
                        "Signature" => "Dinesh Kumar Giri"
                    ),
                    array(
                        "Name" => "Kishan3",
                        "Email ID" => "kishan@example.com",
                        "Signature Type" => "Type",
                        "Security Authentication" => "Access Code",
                        "Timestamps" => "2023-04-27 12:21:05",
                        "Signature" => "Kishan Rathore"
                    ),
                    array(
                        "Name" => "Kishan3",
                        "Email ID" => "kishan@example.com",
                        "Signature Type" => "Type",
                        "Security Authentication" => "Access Code",
                        "Timestamps" => "2023-04-27 12:21:05",
                        "Signature" => "Kishan Rathore"
                    ),
                    array(
                        "Name" => "Kishan3",
                        "Email ID" => "kishan@example.com",
                        "Signature Type" => "Type",
                        "Security Authentication" => "Access Code",
                        "Timestamps" => "2023-04-27 12:21:05",
                        "Signature" => "Kishan Rathore"
                    )
                )
    );

    //echo "<pre>"; print_r($recipientsArr); die;

        //Recipients Loop
        $prevLhsX = 0;
        $prevLhsY = 178;

        $prevRhsX = 0;
        $prevRhsY = 178;

        foreach($recipientsBatchArr as $k => $recipientBatchRw){
            
            if($k > 0){
                $prevLhsX = 0;
                $prevLhsY = 5;
        
                $prevRhsX = 0;
                $prevRhsY = 5;
                
                $fileIndex = $this->pdf->importPage($i);

                $orientation = '';
                $size = '';
                $rotation = 0;
                $this->pdf->AddPage($orientation, $size, $rotation);
                
               $this->pdf->useTemplate($fileIndex,0,0,237, null,true);
                $x = 0;
                $y = 0;
                $w = $pWidth;
                $h = $pHeight;
                
                $this->pdf->Image($bgImg , $x, $y, $w, $h);
               
            }
            
            foreach($recipientBatchRw as $recipientRw){
                if(!empty($recipientRw)){
                $newLhsX = 12.5;
                $newLhsY = $prevLhsY + 20;

                $newRhsX = 120;
                $newRhsY = $prevRhsY + 20;
                
                $rcpntNm = $recipientRw["Name"];
                $rcpntEmlId = $recipientRw["Email ID"];
                $rcpntSignatureType = $recipientRw["Signature Type"];

                $rcpntSecurityAuthentication = $recipientRw["Security Authentication"];
                $rcpntTimestamps = $recipientRw["Timestamps"];
                $rcpntSignature = $recipientRw["Signature"];
                

                //Recipients
                

                //Name
                $this->pdf->SetFont("helvetica", "B", 13);
                $this->pdf->SetTextColor(0,0,0);
                $this->pdf->Text($newLhsX, $newLhsY, "Name");   //label
                
                $newLhsY = $newLhsY + 10;  
                $this->pdf->SetFont("helvetica", "", 11);
                $this->pdf->SetTextColor(27,27,27);
                $this->pdf->Text($newLhsX, $newLhsY-5, $rcpntNm);   //value

                //Email ID
                $this->pdf->SetFont("helvetica", "B", 13);
                $this->pdf->SetTextColor(0,0,0);
                $this->pdf->Text($newRhsX, $newRhsY, "Email ID");    //label
                $newRhsY = $newRhsY + 10; 
                $this->pdf->SetFont("helvetica", "", 11);
                $this->pdf->SetTextColor(27,27,27);
                $this->pdf->Text($newRhsX, $newRhsY-5, $rcpntEmlId);   //value
                
                //signature type
                $newLhsY = $newLhsY + 10;  
                $this->pdf->SetFont("helvetica", "B", 13);
                $this->pdf->SetTextColor(0,0,0);
                $this->pdf->Text($newLhsX, $newLhsY, "Signature Type");   //label
                $newLhsY = $newLhsY + 10;  
                $this->pdf->SetFont("helvetica", "", 11);
                $this->pdf->SetTextColor(27,27,27);
                $this->pdf->Text($newLhsX, $newLhsY-5, $rcpntSignatureType);   //value
                
                //Signature Auth
                $newRhsY = $newRhsY + 10; 
                $this->pdf->SetFont("helvetica", "B", 13);
                $this->pdf->SetTextColor(0,0,0);
                $this->pdf->Text($newRhsX, $newRhsY, "Security Authentication");   //label
                $newRhsY = $newRhsY + 10; 
                $this->pdf->SetFont("helvetica", "", 11);
                $this->pdf->SetTextColor(27,27,27);
                $this->pdf->Text($newRhsX, $newRhsY-5, $rcpntSecurityAuthentication);   //value
                
                //Timestamp
                $newLhsY = $newLhsY + 10;  
                $this->pdf->SetFont("helvetica", "B", 13);
                $this->pdf->SetTextColor(0,0,0);
                $this->pdf->Text($newLhsX, $newLhsY, "Timestamps");   //label
                $newLhsY = $newLhsY + 10;  
                $this->pdf->SetFont("helvetica", "", 11);
                $this->pdf->SetTextColor(27,27,27);
                $this->pdf->Text($newLhsX, $newLhsY-5, $rcpntTimestamps);   //value
                
                //Signature
                $x = 110;
                $y = 232;
                $w = 50; //50;
                $h = 18; //18;
                
                //$this->pdf->Image($rcpntSignature , $x, $y, $w, $h);
                $newRhsY = $newRhsY + 10; 
                $this->pdf->SetFont("helvetica", "B", 13);
                $this->pdf->Text($newRhsX, $newRhsY, "Signature");   //label
                $newRhsY = $newRhsY + 10; 
                $this->pdf->SetFont("helvetica", "", 11);
                $this->pdf->Text($newRhsX, $newRhsY-5, $rcpntSignature);   //value
                
                //seprator
                $x1 = 12; 
                $y1 = $newLhsY+10;
                $x2 = 225;
                $y2 = $newRhsY+10;
        
                $this->pdf->Line($x1, $y1, $x2, $y2);
        
                
                $prevLhsX = $newLhsX;
                $prevLhsY = $newLhsY;

                $prevRhsX = $newRhsX;
                $prevRhsY = $newRhsY;
            }
                //echo "newLhsX: $newLhsX,newLhsY: $newLhsY, newRhsX: $newRhsX, newRhsY: $newRhsY <br>";
            }
            
        }

        
        //Add Audit Trial
        //Import and Add New Page
        $fileIndex = $this->pdf->importPage($i);

        $orientation = '';
        $size = '';
        $rotation = 0;
        $this->pdf->AddPage($orientation, $size, $rotation);
        
       $this->pdf->useTemplate($fileIndex,0,0,237, null,true);
        $x = 0;
        $y = 0;
        $w = $pWidth;
        $h = $pHeight;
        
        $this->pdf->Image($bgImg , $x, $y, $w, $h);


        //Page Heading    
        $this->pdf->SetFont("helvetica", "B", 15);
        $this->pdf->Text(12, 26, "Audit Trial");
        $x1 = 49; 
        $y1 = 26;
        $x2 = 225;
        $y2 = 26;

        $this->pdf->Line($x1, $y1, $x2, $y2);
        
        //Audit Trial Loop
        $auditTrialBatchArr = array(
            array(    
                /*array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),*/
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                )
            ),
            array(    
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                )
            ),
            array(    
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Signed",
                    "Name" => "Kishan",
                    "Email ID" => "kishan@example.com",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => "122.161.198.145"
                ),
                array(
                    "Section" => "Completed",
                    "Name" => "",
                    "Email ID" => "",
                    "DateTime" => date("Y-m-d H:i:s"),
                    "IP" => ""
                )
            )
        );


       
        foreach($auditTrialBatchArr as $k => $auditTrialBatchRw){
            $prevLhsX = 12;
            $prevLhsY = 40;
            $prevRhsX = 60;
            $prevRhsY = 40;
            $newLhsX = $prevLhsX;
            $newLhsY = $prevLhsY;
            $newRhsX = $prevRhsX;
            $newRhsY = $prevRhsY;

            if($k > 0){
                $newLhsX = $prevLhsX;
                $newLhsY = $prevLhsY-23;
                $newRhsX = $prevRhsX;
                $newRhsY = $prevRhsY-23;
            }

           foreach($auditTrialBatchRw as $sk=> $auditTrialRw){
                   
                    $adtSec = $auditTrialRw["Section"];
                    $adtNm = $auditTrialRw["Name"];
                    $adtEml = $auditTrialRw["Email ID"];
                    $adtDtTm = $auditTrialRw["DateTime"];
                    $adtIP = $auditTrialRw["IP"];
                
                    
                    $this->pdf->SetFont("helvetica", "B", 11);
                    $this->pdf->SetTextColor(0,0,0);
                    $this->pdf->Text($newLhsX+5, $newLhsY, "$adtSec:"); // Label
                    
                    $this->pdf->SetTextColor(27,27,27);
                    if(strtolower($adtSec) == "signed"){
                        $this->pdf->SetFont("helvetica", "", 11);
                        $this->pdf->Text($newRhsX+5, $newRhsY, $adtNm." signed the document."); // Name
                        
                        $this->pdf->SetFont("helvetica", "", 9);
                        $this->pdf->Text($newLhsX+5, $newLhsY+7, $adtDtTm); // Date Time
                        
                        $this->pdf->SetFont("helvetica", "", 9);
                        $this->pdf->Text($newRhsX+5, $newRhsY+7, $adtEml); // Email 
                        
                        $this->pdf->SetFont("helvetica", "", 9);
                        $this->pdf->Text($newRhsX+120, $newRhsY+7, "IP: ".$adtIP); // IP    
                    
                        $rectX = $newLhsX;
                        $rectY = $newLhsY-5;
                        $rectW = 213;
                        $rectH = 15;
                        $this->pdf->Rect($rectX, $rectY, $rectW, $rectH, $style='');

                        
                        $x1 = $newLhsX + 48; 
                        $y1 = $newRhsY;
                        $x2 = $newLhsX + 48;
                        $y2 = $newRhsY;// + 38;
                        $this->pdf->Line($x1, $y1-5, $x2, $y2+10); //vertical line

                        
                        $x1 = $newLhsX + 165; 
                        $y1 = $newRhsY;// + 30.5;
                        $x2 = $newLhsX + 165;
                        $y2 = $newRhsY;// + 38;
                        $this->pdf->Line($x1, $y1+2.5, $x2, $y2+10); //vertical line


                        $x1 = $newLhsX+48;
                        $y1 = $newLhsY+2.5;
                        $x2 = $newLhsX+213;
                        $y2 = $newLhsY+2.5;
                        $this->pdf->Line($x1, $y1, $x2, $y2); //horizontal line
                        
                    }else{
                        
                        $this->pdf->SetFont("helvetica", "", 11);
                        $this->pdf->Text($newRhsX+5, $newRhsY, " Document has been completed."); // Document completed

                        $this->pdf->SetFont("helvetica", "", 9);
                        $this->pdf->Text($newLhsX+5, $newLhsY+7, $adtDtTm); // Date Time
                        
                        $rectX = $newLhsX;
                        $rectY = $newLhsY-5;
                        $rectW = 213;
                        $rectH = 15;
                        $this->pdf->Rect($rectX, $rectY, $rectW, $rectH, $style='');
                        
                    }
                    
                    $newLhsY = $newLhsY + 20;
                    $newRhsY = $newRhsY + 20;
                   
                  
                }
           
            

                if($k < count($auditTrialBatchArr)-1){
                    //Import and Add New Page
                    $fileIndex = $this->pdf->importPage($i);

                    $orientation = '';
                    $size = '';
                    $rotation = 0;
                    $this->pdf->AddPage($orientation, $size, $rotation);
                    
                    $this->pdf->useTemplate($fileIndex,0,0,237, null,true);
                    $x = 0;
                    $y = 0;
                    $w = $pWidth;
                    $h = $pHeight;
                    
                    $this->pdf->Image($bgImg , $x, $y, $w, $h);
                }

        }
        
        $this->pdf->Output();
        
        /*
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
        $this->pdf->Output($rootFolder."$secretFolder/$signerDocumentId/$signerDocumentId"."_auditlog.pdf", "F");
        */
    }
}

?>