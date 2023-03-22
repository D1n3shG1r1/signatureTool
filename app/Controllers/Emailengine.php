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
        //$maindocid = "e1d46561da8c90973f0fd3c893d22fac";
        $maindocid = "fe563c70178fa7d9a76d2ffaee85d860";
        $docDetails = $this->email_model->getSignerDocumentDetails($maindocid);
        //echo "<pre>"; print_r($docDetails); die;
        
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
        //$docResult["senderId"];
        
        $signerWiseArr = array();
        $signers = array();
        foreach($signersResult as $k => $signersRw){
            $signers[$k]["name"] = $signersRw["signerName"];
            $signers[$k]["email"] = $signersRw["signerEmail"];    
        }
       
        $tmpSignerDocId = $signerResult["documentId"];
        $signerName = $signerResult["signerName"];
        $signerEmail = $signerResult["signerEmail"];
        $ownerName = $ownerName;
        $ownerEmail = $ownerEmail;
        $docTitle = $documentTitle;
        $docLink = site_url("sign/?documentId=$tmpSignerDocId");
        $expireyDate = $signerResult["documentExpiry"];
        
        $data = array();
		$data["signerName"]     = $signerName;
		$data["ownerName"]      = $ownerName;
		$data["ownerEmail"]     = $ownerEmail;
        $data["docTitle"]       = $docTitle;
        $data["docLink"]        = $docLink;
        $data["expireyDate"]    = $expireyDate;
        $data["signers"]        = $signers;

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
            echo 'Email successfully sent';
			//update date time in db when email is sent
        } 
		else 
		{
			//write log if email failed
            
			$data = $email->printDebugger(['headers']);
            print_r($data);
			
        }


        
    }


}
?>