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
        
        //echo "<pre>"; print_r($filesResult); die;

        if(!empty($filesResult)){
            $tmpUserIdsArr = array();
            $tmpFileIdsArr = array();
            foreach($filesResult as $filesRw){
                $tmpUserId = $filesRw["user_id"];
                $tmpFileId = $filesRw["user_id"];
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

                echo "<pre>"; print_r($filesResult); die;
            }

            if(!empty($tmpFileIdsArr)){
                //get file document info
             }
            

        }
        

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
    
}