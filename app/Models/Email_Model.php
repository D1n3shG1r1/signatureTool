<?php

namespace App\Models;

use CodeIgniter\Model;

class Email_Model extends Model
{
    public $session = null;
	protected $db;
	
	
	function __construct(){
		parent::__construct();
		$this->session = \Config\Services::session();
		$this->session->start();
		$this->db = \Config\Database::connect();
	}

    function getSignerDocumentDetails($docId){
        
        $finalArr = array();

        //signer data
        $cmd = "SELECT `parentDocument`, `documentId`, `signerEmail`, `signerName`, `documentExpiry` FROM `e_sign_document_signers` WHERE `documentId` = '$docId'";
        $query = $this->db->query($cmd);
	    $signerResult = $query->getRowArray();

        if(!empty($signerResult)){
            
            $parentDocument = $signerResult["parentDocument"];
            
            //other signers
            $cmd = "SELECT `signerEmail`, `signerName`, `authType`, `otp`, `accessCode` FROM `e_sign_document_signers` WHERE `parentDocument` = $parentDocument";
            $query = $this->db->query($cmd);
            $signersResult = $query->getResultArray();
            
            //parent doc
            $cmd = "SELECT `documentTitle`, `senderId`, `uploadId` FROM `e_sign_documents` WHERE `id` = $parentDocument";
            $query = $this->db->query($cmd);
            $parentDocResult = $query->getRowArray();
            $senderId = $parentDocResult["senderId"];
            

            //get doc upload info
            $uploadId = $parentDocResult["uploadId"];
            $cmd = "SELECT `documentTitle` AS `customDocumentTitle`, `recipientMessage`, `expiresInDays`, `expiryDate` FROM `e_sign_uploaded_files` WHERE `id` = $uploadId AND `user_id` = $senderId";
            $query = $this->db->query($cmd);
            $docUpladResult = $query->getRowArray();


            //owner
            $cmd = "SELECT `first_name`, `last_name`, `email` FROM `users` WHERE `id` = $senderId";
            $query = $this->db->query($cmd);
		    $userResult = $query->getRowArray();
       
            unset($signerResult["parentDocument"]);
            unset($parentDocResult["senderId"]);
            unset($parentDocResult["uploadId"]);
            
            $parentDocResult  = array_merge($parentDocResult, $docUpladResult);

            $finalArr["signerResult"] = $signerResult;
            $finalArr["signersResult"] = $signersResult;
            $finalArr["docResult"] = $parentDocResult;
            $finalArr["ownerResult"] = $userResult;
        }

        return $finalArr;
        
        /*
        $finalArr = array();
        $cmd = "SELECT `id`, `documentTitle`, `senderId` FROM `e_sign_documents` WHERE `documentId` = '$docId'";
		
		$query = $this->db->query($cmd);
		$signDocResult = $query->getRowArray();
		
        if(!empty($signDocResult)){
            
            $id = $signDocResult["id"];
            $documentTitle = $signDocResult["documentTitle"];
            $ownerId = $signDocResult["senderId"];
            
            $cmd = "SELECT `first_name`, `last_name`, `email` FROM `users` WHERE `id` = $ownerId";
            $query = $this->db->query($cmd);
		    $userResult = $query->getRowArray();

            $cmd = "SELECT `documentId`, `signerEmail`, `signerName`, `documentExpiry` FROM `e_sign_document_signers` WHERE `parentDocument` = $id";
            $query = $this->db->query($cmd);
		    $signersResult = $query->getResultArray();
            
            $finalArr["ownerResult"] = $userResult;
            $finalArr["docResult"] = $signDocResult;
            $finalArr["signersResult"] = $signersResult;
            
        }
        return $finalArr;
        */
        
    }
    
    function updateOtpDateTime($docid, $param){
        
        $dt = $param["date"];
        
		$cmd = "UPDATE `e_sign_document_signers` SET `otpDateTime` = '$dt' WHERE `documentId` = '$docid'";
				
        $query = $this->db->query($cmd);
				
        if($this->db->affectedRows() > 0){
            return 1;
        }

    }
    
    function updateDocStatusEmailSentDateTime($docid, $param){
        
        $status = $param["document_status"];
        $dt = $param["documentSentDate"];
        
		$cmd = "UPDATE `e_sign_document_signers` SET `documentSentDate` = '$dt', `document_status` = '$status' WHERE `documentId` = '$docid'";
				
        $query = $this->db->query($cmd);
				
        if($this->db->affectedRows() > 0){
            return 1;
        }
    }

    function getCompletedDocument($documentId){
        
        $result = array();

        $cmd = "SELECT `parentDocument`, `documentId`, `signerEmail`, `signerName`, `documentExpiry` FROM `e_sign_document_signers` WHERE `documentId` = '$documentId'";
        $query = $this->db->query($cmd);
        $signerRow = $query->getRowArray();

        if(!empty($signerRow)){
            $tmpParentDoc = $signerRow["parentDocument"];
        
            $cmd = "SELECT `uploadId` FROM `e_sign_documents` WHERE `id` = $tmpParentDoc";
            $query = $this->db->query($cmd);
            $parentDocumentRow = $query->getRowArray();
            
            if(!empty($parentDocumentRow)){
                $uploadId = $parentDocumentRow["uploadId"];
                $cmd = "SELECT `documentTitle` FROM `e_sign_uploaded_files` WHERE `id` = $uploadId";
                $query = $this->db->query($cmd);
                $uploadedDocumentRow = $query->getRowArray();    

                $result["documentId"] = $signerRow["documentId"];
                $result["signerName"] = $signerRow["signerName"];
                $result["signerEmail"] = $signerRow["signerEmail"];
                $result["documentTitle"] = $uploadedDocumentRow["documentTitle"];
            
            }
        
        }

        return $result;
    }

}
?>
