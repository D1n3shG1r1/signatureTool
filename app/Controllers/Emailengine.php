<?php

namespace App\Controllers;
use App\Models\Document_Model; //load model
use App\Models\Email_Model; //load model
use App\Controllers\BaseController;
//use App\Libraries\GeneratePdf; //import library
//use Config\Encryption;
//use Config\Services;

class Emailengine extends BaseController
{

    public $document_model = null;
    public $email_model = null;
    public $request = null;
	public $session = null;
	function __construct(){
		
		$this->session = \Config\Services::session();
		$this->session->start();
		
		//Request Object
		$this->request = \Config\Services::request();
		
		//Model Object
		$this->document_model = new Document_Model();
        $this->email_model = new Email_Model();
		
		helper("kitchen");

		helper("EncryDcry");

	}

    function sendDocuSingColl($maindocid) { 
        //emailengine/sendDocuSingColl
    
        /*
        $fpath = FCPATH."/test.txt";
        $fp = fopen($fpath, "a+");
        fwrite($fp, "\n\n\n maindocid:".$maindocid);
        fclose($fp);
        */

        $docDetails = $this->email_model->getSignerDocumentDetails($maindocid);
        //echo "<pre>"; print_r($docDetails); die;
        
        /*
        $fp = fopen($fpath, "a+");
        fwrite($fp, "\n\n\n docDetails:".json_encode($docDetails));
        fclose($fp);
        */        


        $ownerResult = $docDetails["ownerResult"];
        $docResult = $docDetails["docResult"];
        $signerResult = $docDetails["signerResult"];
        $signersResult = $docDetails["signersResult"];
        
        $ownerFirstName = $ownerResult["first_name"];
        $ownerLastName = $ownerResult["last_name"];
        $ownerName = $ownerFirstName." ".$ownerLastName;
        $ownerEmail = $ownerResult["email"];

        //$docResult["id"];
        $documentTitle = $docResult["documentTitle"];
        $customDocumentTitle = $docResult["customDocumentTitle"];
        $recipientMessage = $docResult["recipientMessage"];
        $expiresInDays = $docResult["expiresInDays"];
        $expiryDate = $docResult["expiryDate"];
        //$docResult["senderId"];
        
        $authType = 0;
        $otp = "";
        $accessCode = "";

        $signerWiseArr = array();
        $signers = array();
        foreach($signersResult as $k => $signersRw){
            $signers[$k]["name"] = $signersRw["signerName"];
            $signers[$k]["email"] = $signersRw["signerEmail"];    

            if($signerResult["signerEmail"] == $signersRw["signerEmail"]){
                $authType = $signersRw["authType"];
                $otp = $signersRw["otp"];
                $accessCode = $signersRw["accessCode"];
            }
        }
        
        $tmpSignerDocId = $signerResult["documentId"];
        $signerName = $signerResult["signerName"];
        $signerEmail = $signerResult["signerEmail"];
        $ownerName = $ownerName;
        $ownerEmail = $ownerEmail;
        $docTitle = $documentTitle;
        $docLink = site_url("sign/?documentId=$tmpSignerDocId");
        $expireyDate = $signerResult["documentExpiry"];

        //get doc auth
        //$signerEmail
        
        $data = array();
		$data["signerName"]         = $signerName;
		$data["ownerName"]          = $ownerName;
		$data["ownerEmail"]         = $ownerEmail;
        $data["docTitle"]           = $customDocumentTitle; //$docTitle;
        $data["additionalMessage"]  = $recipientMessage;
        $data["docLink"]            = $docLink;
        $data["expireyDate"]        = $expiryDate; //$expireyDate;
        $data["signers"]            = $signers;
        /*
        $data["authType"]           = $authType;
        $data["accessCode"]         = $accessCode;
        */

        /*$fp = fopen($fpath, "a+");
        fwrite($fp, "\n\n\n tmpData:".json_encode($data));
        fclose($fp);*/

        $template = view('emailtemplates/DocuSingColl_Template', $data);

        $to = $signerEmail;
        $subject = "Signature Request: $ownerName has requested you to sign $docTitle";
        $message = $template;
        
        
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setFrom('johndoe@gmail.com', 'Confirm Registration');
        
        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) 
		{
            //echo 'Email successfully sent';
            /*
            $fp = fopen($fpath, "a+");
            fwrite($fp, "\n\n\n email sent");
            fclose($fp);
            */            
            //update date time in db when email is sent
            $param = array();
            $param["document_status"] = "sent";
            $param["documentSentDate"] = date("Y-m-d H:i:s");

            $this->email_model->updateDocStatusEmailSentDateTime($maindocid, $param);
            
            if($authType == 1){
                 
                $emailData = array();
                $emailData["signerName"] = $signerName;
                $emailData["signerEmail"] = $signerEmail;
                $emailData["otp"] = $otp;
                $emailData["docTitle"] = $docTitle;
                $otpSent = $this->sendOtpEmail($emailData);
                if($otpSent > 0){
                    
                    //update otp sent date time
                    
                    $param = array();
                    $param["date"] = date("Y-m-d H:i:s");
                    $this->email_model->updateOtpDateTime($maindocid, $param);
                }
            }

        } 
		else 
		{
			//write log if email failed
            
			$data = $email->printDebugger(['headers']);
            
            //print_r($data);
			/*
            $fp = fopen($fpath, "a+");
            fwrite($fp, "\n\n\n email error:".json_encode($data));
            fclose($fp);
            */
            //echo "exe:".json_encode($data);
        }
        
    }

    function sendOtpEmail($emailData){
        
        $signerName = $emailData["signerName"];
        $signerEmail = $emailData["signerEmail"];
        $otp = $emailData["otp"];
        $docTitle = $emailData["docTitle"];

        
        $data = array();
		$data["signerName"] = $signerName;
        $data["otp"] = $otp;
        $data["docTitle"] = $docTitle;
		
        $template = view('emailtemplates/DocuSingOTP_Template', $data);
        $message = $template;

        $to = $signerEmail;
        $subject = "Your verification code for signing $docTitle";
        
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setFrom('johndoe@gmail.com', 'Confirm Registration');
        
        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) 
		{
            //echo 'Email successfully sent';
            return 1;
        } 
		else 
		{
			//write log if email failed
            //$data = $email->printDebugger(['headers']);
            return 0;
            
        }
    }


}
?>