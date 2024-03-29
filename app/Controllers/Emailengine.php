<?php

namespace App\Controllers;
use App\Models\Document_Model; //load model
use App\Models\Email_Model; //load model
use App\Models\Cron_Model; //load model

use App\Controllers\BaseController;
//use App\Libraries\GeneratePdf; //import library
//use Config\Encryption;
//use Config\Services;
use Config\Esign;

class Emailengine extends BaseController
{

    public $document_model = null;
    public $email_model = null;
    public $cron_model = null;
    public $request = null;
	public $session = null;
    public $esign_config = null;
    public $FROMEMAIL = null;
    public $FROMNAME = null;

	function __construct(){
		
		$this->session = \Config\Services::session();
		$this->session->start();
		
        //config object
		$this->esign_config = new \Config\Esign();
        $this->FROMEMAIL = $this->esign_config->FROMEMAIL;
        $this->FROMNAME = $this->esign_config->FROMNAME;

		//Request Object
		$this->request = \Config\Services::request();
		
		//Model Object
		$this->document_model = new Document_Model();
        $this->email_model = new Email_Model();
        $this->cron_model = new Cron_Model();
		
		helper("kitchen");
        helper("EncryDcry");

	}

    function sendDocuSingColl($maindocid) { 
        
        $docDetails = $this->email_model->getSignerDocumentDetails($maindocid);
        
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

    function sendCompletedDocumentToSigner($documentId){
        //emailengine/sendCompletedDocumentToSigner

        $secretFolder = $this->esign_config->SECRETFOLDER;
        //$documentId = "37a3235a19a98fd3e4bf7b0bcbe74669";
        $emailDocData = $this->email_model->getCompletedDocument($documentId);

        if(!empty($emailDocData)){

            $documentId = $emailDocData["documentId"];
            $signerName = $emailDocData["signerName"];
            $signerEmail = $emailDocData["signerEmail"];
            $documentTitle = $emailDocData["documentTitle"];
        
            $attachmentMime = "application/pdf";
            $folderPath = FCPATH . "$secretFolder\\" . $documentId; 
            $fileUrl_1 =  $folderPath."\\".$documentId.".pdf";
            $fileName_1 = $documentTitle.".pdf";

            $fileUrl_2 =  $folderPath."\\".$documentId."_auditlog.pdf";
            $fileName_2 = $documentTitle."_auditlog.pdf";

            $data = array();
            $data["signerName"] = $signerName;
            $data["docTitle"] = $documentTitle;
            
            $template = view('emailtemplates/DocuSingnedSigner_Template', $data);
            $message = $template;
            
            $to = $signerEmail;
            $subject = "You have successfully signed $documentTitle";
            
            $email = \Config\Services::email();
            $email->setTo($to);
            $email->setFrom($this->FROMEMAIL, $this->FROMNAME);
            
            $email->setSubject($subject);
            $email->setMessage($message);
            $email->attach(file_get_contents($fileUrl_1), 'attachment', $fileName_1, $attachmentMime);
            $email->attach(file_get_contents($fileUrl_2), 'attachment', $fileName_2, $attachmentMime);
            if ($email->send()) 
            {
                //echo 'Email successfully sent';
                return 1;
            } 
            else 
            {
                //write log if email failed
                //echo $data = $email->printDebugger(['headers']);
                return 0;
                
            }
        
        }
        
    }

    function sendDocuExpiredOwner($fileId){
        
        $homePath = FCPATH."index.php";
        $rootFolder = publicFolder();
        $CRONASSETSDIR = $this->esign_config->CRONASSETSDIR;
        
        $tmpDirPath = FCPATH.$CRONASSETSDIR."/$fileId/";
        $filePath = $tmpDirPath.$fileId.".txt";
        
        $fileContent = fileRead($filePath);
        if($fileContent != ""){
            
            $fileContentArr = json_decode($fileContent);
            
            $fileId = $fileContentArr->id;
            $fileName = $fileContentArr->file_name;
            $fileDocumentTitle = $fileContentArr->documentTitle;
            $fileUserId = $fileContentArr->user_id;
            $fileExpiry = $fileContentArr->expiryDate;
            $fileExpireStatus = $fileContentArr->expired;
           
            $owner = $fileContentArr->owner;
            $ownerId = $owner->id;
            $ownerFirstName = $owner->first_name;
            $ownerLastName = $owner->last_name;
            $ownerEmail = $owner->email;
            
            $parentDocument = $fileContentArr->parentDocument;
            $parentDocumentId = $parentDocument->id;
            $parentDocumentUploadId = $parentDocument->uploadId;
            $parentDocumentDocumentId = $parentDocument->documentId;
            $parentDocumentDocumentPath = $parentDocument->documentPath;
            $parentDocumentCreatedAt = $parentDocument->created_at;
            

            //Ok Report Template
            $data = array();
            $data["ownerName"] = ucwords($ownerFirstName." ".$ownerLastName);
            $data["documentTitle"] = $fileDocumentTitle;
            $data["sentDate"] = $parentDocumentCreatedAt;
            $data["expiryDate"] = $fileExpiry;
            $data["documentLink"] = site_url("signeddocument/$parentDocumentDocumentId");
            
            $template = view('emailtemplates/DocuExpiredOwner_Template', $data);
            $message = $template;
            
            $to = $ownerEmail;
            $subject = "Signature request for $fileDocumentTitle has expired";
            
            $email = \Config\Services::email();
            $email->setTo($to);
            $email->setFrom($this->FROMEMAIL, $this->FROMNAME);
            
            $email->setSubject($subject);
            $email->setMessage($message);
            
            if ($email->send()) 
            {
                //echo 'Email successfully sent';

                //update notify flag and notify date then remove the temp files
                $flag = 1;   
                $this->cron_model->updateOwnerExpiryNotify($fileId,$flag);
                
                //remove file
                fileRemove($filePath);
                
                //echo 'Email successfully sent';
                //return 1;
            } 
            else 
            {
                //write log if email failed
                //echo $data = $email->printDebugger(['headers']);
                //return 0;
                
            }


        }
    }

    function sendDocuExpiredReminder($fileId){
        $homePath = FCPATH."index.php";
        $rootFolder = publicFolder();
        $CRONASSETSDIR = $this->esign_config->CRONASSETSDIR;
        
        $tmpDirPath = FCPATH.$CRONASSETSDIR."/$fileId/";
        $filePath = $tmpDirPath.$fileId."_reminder.txt";
        
        $fileContent = fileRead($filePath);
        if($fileContent != ""){
            
            $fileContentArr = json_decode($fileContent, true);
            
            $signerId = $fileContentArr["id"];
            $parentDocument = $fileContentArr["parentDocument"];
            $documentId = $fileContentArr["documentId"];
            $signerName = $fileContentArr["signerName"];
            $signerEmail = $fileContentArr["signerEmail"];
            $document_status = $fileContentArr["document_status"];
            $documentExpiry = $fileContentArr["documentExpiry"];
           
            $documentDetails = $fileContentArr["documentDetails"];
            $documentDetails_id = $documentDetails["id"];
            $documentDetails_uploadId = $documentDetails["uploadId"];
            $documentDetails_senderId = $documentDetails["senderId"];
            $documentDetails_created_at = $documentDetails["created_at"];
                    
            $uploadFileDetails = $documentDetails["uploadFileDetails"];
            $uploadFileDetails_id = $uploadFileDetails["id"];
            $uploadFileDetails_documentTitle = $uploadFileDetails["documentTitle"];
            $uploadFileDetails_recipientMessage = $uploadFileDetails["recipientMessage"];
            
            $uploadFileDetails_expiryDate = $uploadFileDetails["expiryDate"];
            $uploadFileDetails_user_id =$uploadFileDetails["user_id"];
                        

            $ownerDetails = $documentDetails["ownerDetails"];
            $ownerDetails_id = $ownerDetails["id"];
            $ownerDetails_first_name = $ownerDetails["first_name"];
            $ownerDetails_last_name = $ownerDetails["last_name"];
            $ownerDetails_email = $ownerDetails["email"];
            
            
            $data = array();

            $data["expireyDate"] = $documentExpiry;
            $data["docTitle"] = $uploadFileDetails_documentTitle;
            $data["docLink"] = site_url("sign/?documentId=$documentId");
            $data["additionalMessage"] = $uploadFileDetails_recipientMessage;
            $data["ownerName"] = ucwords($ownerDetails_first_name." ".$ownerDetails_last_name);
            $data["ownerEmail"] = $ownerDetails_email;
            $data["signerName"] = ucwords($signerName);

            $subject = ucwords($ownerDetails_first_name." ".$ownerDetails_last_name)." has sent you a reminder to review and sign ".$uploadFileDetails_documentTitle;
            $template = view('emailtemplates/DocuReminderSigner_Template', $data);

            $message = $template;
            
            $to = $signerEmail;
            
            $email = \Config\Services::email();
            $email->setTo($to);
            $email->setFrom($this->FROMEMAIL, $this->FROMNAME);
            $email->setSubject($subject);
            $email->setMessage($message);
            
            if ($email->send()) 
            {
                //echo 'Email successfully sent';

                //update notify flag and notify date then remove the temp files
                $flag = 1;   
                $this->cron_model->updateReminderNotify($fileId,$flag);
                
                //remove file
                fileRemove($filePath);
                
                //echo 'Email successfully sent';
                //return 1;
            } 
            else 
            {
                //write log if email failed
                //echo $data = $email->printDebugger(['headers']);
                //return 0;
                
            }   
        }
    }
}
?>