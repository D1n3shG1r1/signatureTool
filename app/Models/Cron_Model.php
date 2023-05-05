<?php

namespace App\Models;

use CodeIgniter\Model;

class Cron_Model extends Model
{
    public $session = null;
	protected $db;
	
	function __construct(){
		parent::__construct();
		$this->session = \Config\Services::session();
		$this->session->start();
		$this->db = \Config\Database::connect();
	}

    function getExpiredDocuments(){
        $todayMin = date("Y-m-d 00:00:00");
        $todayMax = date("Y-m-d 23:59:59");
        $result = array();

        //get uploaded expired files
        $cmd = "SELECT `id`, `file_name`, `documentTitle`, `user_id`, `expiryDate`, `expired` FROM `e_sign_uploaded_files` WHERE `expired` = 1";
		$query = $this->db->query($cmd);
		$filesResult = $query->getResultArray();   
        
        if(!empty($filesResult)){
            $tmpUserIdsArr = array();
            $tmpFileIdsArr = array();
            foreach($filesResult as $filesRw){
                $tmpUserId = $filesRw["user_id"];
                $tmpFileId = $filesRw["id"];
                if(in_array($tmpUserId, $tmpUserIdsArr) == false){
                    $tmpUserIdsArr[] = $tmpUserId;
                }

                if(in_array($tmpFileId, $tmpFileIdsArr) == false){
                    $tmpFileIdsArr[] = $tmpFileId;
                }
                
                
            }

            if(!empty($tmpUserIdsArr)){
                //get files owner
                $tmpUserIdsStr = implode(",", $tmpUserIdsArr);
                $cmd = "SELECT `id`, `first_name`, `last_name`, `email` FROM `users` WHERE `id` IN($tmpUserIdsStr)";
                $query = $this->db->query($cmd);
                $usersResult = $query->getResultArray();   

                if(!empty($usersResult)){
                    $usersWiseResultArr = array();
                    foreach($usersResult as $usersRw){
                        $usersWiseResultArr[$usersRw["id"]] = $usersRw;
                    }

                    foreach($filesResult as &$filesRw){
                        $tmpUserId = $filesRw["user_id"];
                        $filesRw["owner"] = $usersWiseResultArr[$tmpUserId];
                        
                    }

                }

            }

            if(!empty($tmpFileIdsArr)){
                //get file document info
                $tmpFileIdsStr = implode(",", $tmpFileIdsArr);
                $cmd = "SELECT `id`, `uploadId`, `documentId`, `documentPath`, `created_at` FROM `e_sign_documents` WHERE `uploadId` IN($tmpFileIdsStr)";
                $query = $this->db->query($cmd);
                $documentsResult = $query->getResultArray();        
                
                if(!empty($documentsResult)){
                    $fileWiseDocumentsArr = array();
                    foreach($documentsResult as $documentsRw){
                        $tmpUpldId = $documentsRw["uploadId"];
                        $fileWiseDocumentsArr[$tmpUpldId] = $documentsRw;
                    }

                    foreach($filesResult as &$filesRw){
                        $tmpFileId = $filesRw["id"];
                        $filesRw["parentDocument"] = $fileWiseDocumentsArr[$tmpFileId];
                        
                    }
                
                    $result = $filesResult;
                }


            }
            
        }
        
        //echo "result:<pre>"; print_r($$result);
        return $result;     
    }

    function updateOwnerExpiryNotify($uploadId,$flag){
        
		$table = $this->db->table('e_sign_uploaded_files');
		$table->set('expiryNotify', $flag);
        $table->set('expiryNotifyDate', date("Y-m-d H:i:s"));
		$table->where('id', $uploadId);
		$table->update();

		if($this->db->affectedRows() > 0){
			return 1;
		}else{
			return 0;
		}
    }
    
    function getDocumentsToBeExpire(){
        $result = array();
        $upcomingExDate = date("Y-m-d 00:00:00", strtotime("+1 day"));
        $upcomingExDateMax = date("Y-m-d 23:59:59", strtotime($upcomingExDate));
        $upcomingExDateMin = $upcomingExDate;
        
        $cmd = "SELECT `parentDocument`, `documentId`, `signerName`, `signerEmail`, `document_status`, `documentExpiry` FROM `e_sign_document_signers` WHERE `documentExpiry` >= '$upcomingExDateMin' AND `documentExpiry` <= '$upcomingExDateMax' AND `document_status` != 'signed' AND `reminderSent` = 0";
        //$cmd = "SELECT `id`, `parentDocument`, `documentId`, `signerName`, `signerEmail`, `document_status`, `documentExpiry` FROM `e_sign_document_signers` WHERE `documentExpiry` >= '$upcomingExDateMin' AND `documentExpiry` <= '$upcomingExDateMax'";
        $query = $this->db->query($cmd);
        $signersResult = $query->getResultArray();

        if(!empty($signersResult)){
            //get the document title and owner
            $tmpParentDocumentIdsArr = array();
            foreach($signersResult as $signersRw){
                $tmpParentDocument = $signersRw["parentDocument"];
                if(in_array($tmpParentDocument, $tmpParentDocumentIdsArr) == false){
                    $tmpParentDocumentIdsArr[] = $tmpParentDocument;
                }
            }

            $tmpParentDocumentIdsStr = implode(",", $tmpParentDocumentIdsArr);
            $cmd = "SELECT `id`, `uploadId`, `senderId`, `created_at` FROM `e_sign_documents` WHERE `id` IN($tmpParentDocumentIdsStr)";
            $query = $this->db->query($cmd);
            $documentsResult = $query->getResultArray();        
            
            $tmpDocumentsWiseArr = array();
            if(!empty($documentsResult)){
                $uploadIdsArr = array();
                $ownerIdsArr = array(); 
            
                foreach($documentsResult as $documentRw){
                    
                    $tmpUploadId = $documentRw["uploadId"];
                    $tmpSenderId = $documentRw["senderId"];

                    if(in_array($tmpUploadId, $uploadIdsArr) == false){
                        $uploadIdsArr[] = $tmpUploadId;
                    }

                    if(in_array($tmpSenderId, $ownerIdsArr) == false){
                        $ownerIdsArr[] = $tmpSenderId;
                    }
                    
                }

                $uploadIdsStr = implode(",", $uploadIdsArr);
                $ownerIdsStr = implode(",", $ownerIdsArr);
                
                //get file info and owner info
                //file info
                $cmd = "SELECT `id`, `documentTitle`, `expiryDate`, `user_id`, `recipientMessage` FROM `e_sign_uploaded_files` WHERE `id` IN($uploadIdsStr)";
                $query = $this->db->query($cmd);
                $uploadFilesResult = $query->getResultArray();        

                //owner info
                $cmd = "SELECT `id`, `first_name`, `last_name`, `email` FROM `users` WHERE `id` IN($ownerIdsStr)";
                $query = $this->db->query($cmd);
                $ownersResult = $query->getResultArray();

                $uploadIdWiseFilesResultArr = array();
                $ownerIdWiseOwnersResultArr = array();
                
                foreach($uploadFilesResult as $uploadFilesRw){
                    $uploadIdWiseFilesResultArr[$uploadFilesRw["id"]] = $uploadFilesRw;
                }
                
                foreach($ownersResult as $ownersRw){    
                    $ownerIdWiseOwnersResultArr[$ownersRw["id"]] = $ownersRw;
                }
                

                foreach($documentsResult as &$documentRww){

                    $tmpUploadId = $documentRww["uploadId"];
                    $tmpSenderId = $documentRww["senderId"];

                    $documentRww["uploadFileDetails"] = $uploadIdWiseFilesResultArr[$tmpUploadId];
                    $documentRww["ownerDetails"] = $ownerIdWiseOwnersResultArr[$tmpSenderId];
                }

                foreach($documentsResult as $documentRwww){
                    $tmpDocumentsWiseArr[$documentRwww["id"]] = $documentRwww;
                }

                foreach($signersResult as &$signersRww){
                    $tmpParentDocument = $signersRww["parentDocument"];
                    $signersRww["documentDetails"] = $tmpDocumentsWiseArr[$tmpParentDocument];
                }    

                $result = $signersResult;
            }

        }

        //echo "<pre>"; print_r($result);    
        return $result;

    }

    
    function updateReminderNotify($Id,$flag){
        
		$table = $this->db->table('e_sign_document_signers');
		$table->set('reminderSent', $flag);
        $table->set('lastReminder', date("Y-m-d H:i:s"));
		$table->where('id', $Id);
		$table->update();

		if($this->db->affectedRows() > 0){
			return 1;
		}else{
			return 0;
		}
    }

    function setDocumentToBeExpired(){
        $today = date("Y-m-d H:i:s");
        $cmd = "SELECT `id` FROM `e_sign_uploaded_files` WHERE `expiryDate` < '$today' AND `expired` = 0 LIMIT 100";
        $query = $this->db->query($cmd);
        $uploadFilesResult = $query->getResultArray();   

        if(!empty($uploadFilesResult)){
            $fileIdsArr = array();
            
            foreach($uploadFilesResult as $uploadFilesRw){
                $fileIdsArr[] = $uploadFilesRw["id"];
            }

            $fileIdsStr = implode(",", $fileIdsArr);

            $cmd = "SELECT `id` FROM `e_sign_documents` WHERE `uploadId` IN($fileIdsStr)";
            $query = $this->db->query($cmd);
            $documentResult = $query->getResultArray();   
            $documentIdArr = array();
            foreach($documentResult as $documentRw){
                $documentIdArr[] = $documentResult["id"];
            }

            $documentIdStr = implode(",", $documentIdArr);
            $cmd = "UPDATE `e_sign_document_signers` SET `documentExpired` = 1 WHERE `parentDocument` IN($documentIdStr)";
            $query = $this->db->query($cmd);

            
            $cmd = "UPDATE `e_sign_uploaded_files` SET `expired` = 1 WHERE `id` IN($fileIdsStr)";
            $query = $this->db->query($cmd);
            
        }

    }

}