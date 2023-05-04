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

		/*
        $cmd = "SELECT `id`, `parentDocument`, `documentId`, `documentExpiry` FROM `e_sign_document_signers` WHERE `documentExpiry` >= '$todayMin' AND `documentExpiry` <= '$todayMax'";
		$query = $this->db->query($cmd);
		$signerResult = $query->getResultArray();

        //echo "signerResult:<pre>"; print_r($signerResult);
        if(!empty($signerResult)){
            
            $parentDocIdsArr = array();
            
            foreach($signerResult as $signerRw){
                $tmpParentId = $signerRw["parentDocument"];
                
                if(in_array($tmpParentId, $parentDocIdsArr) == false){
                    $parentDocIdsArr[] = $tmpParentId;
                }
            }

            if(!empty($parentDocIdsArr)){
                $parentDocIdsStr = implode(",", $parentDocIdsArr);
                $cmd = "SELECT `id`, `uploadId`, `senderId` FROM `e_sign_documents` WHERE `id` IN($parentDocIdsStr)";
                $query = $this->db->query($cmd);
                $parentResult = $query->getResultArray();

                echo "<pre>"; print_r($parentResult);
            }
            
        }
        */
     
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
        $upcomingExDate = date("Y-m-d 00:00:00", strtotime("+1 day"));
        $upcomingExDateMax = date("Y-m-d 23:59:59", strtotime($upcomingExDate));
        $upcomingExDateMin = $upcomingExDate;
        
        //$cmd = "SELECT `parentDocument`, `documentId`, `signerName`, `signerEmail`, `document_status`, `documentExpiry` FROM `e_sign_document_signers` WHERE `documentExpiry` >= '$upcomingExDateMin' AND `documentExpiry` <= '$upcomingExDateMax' AND `document_status` != 'signed'";
        $cmd = "SELECT `parentDocument`, `documentId`, `signerName`, `signerEmail`, `document_status`, `documentExpiry` FROM `e_sign_document_signers` WHERE `documentExpiry` >= '$upcomingExDateMin' AND `documentExpiry` <= '$upcomingExDateMax'";
        $query = $this->db->query($cmd);
        $signersResult = $query->getResultArray();

        //echo "<pre>"; print_r($signersResult);

        if(!empty($signersResult)){
            //get the document title and owner
            $tmpParentDocumentIdsArr = array();
            foreach($signersResult as $signersRw){
                $tmpParentDocument = $signersRw["parentDocument"];
                if(in_array($tmpParentDocument, $tmpParentDocumentIdsArr) == false){
                    $tmpParentDocumentIdsArr[] = $tmpParentDocument;
                }
                
                $tmpParentDocumentIdsStr = implode(",", $tmpParentDocumentIdsArr);
                $cmd = "SELECT `id`, `uploadId`, `senderId`, `created_at` FROM `e_sign_documents` WHERE `id` IN($tmpParentDocumentIdsStr)";
                $query = $this->db->query($cmd);
                $documentsResult = $query->getResultArray();        
                
                echo "<pre>"; print_r($documentsResult);

            }
        }


    }

}