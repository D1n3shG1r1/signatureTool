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
            $cmd = "SELECT `signerEmail`, `signerName` FROM `e_sign_document_signers` WHERE `parentDocument` = $parentDocument";
            $query = $this->db->query($cmd);
            $signersResult = $query->getResultArray();
            
            //parent doc
            $cmd = "SELECT `documentTitle`, `senderId` FROM `e_sign_documents` WHERE `id` = $parentDocument";
            $query = $this->db->query($cmd);
            $parentDocResult = $query->getRowArray();
            $senderId = $parentDocResult["senderId"];
            
            //owner
            $cmd = "SELECT `first_name`, `last_name`, `email` FROM `users` WHERE `id` = $senderId";
            $query = $this->db->query($cmd);
		    $userResult = $query->getRowArray();
       
            unset($signerResult["parentDocument"]);
            unset($parentDocResult["senderId"]);

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
}
?>