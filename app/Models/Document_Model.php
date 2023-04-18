<?php

namespace App\Models;

use CodeIgniter\Model;

class Document_Model extends Model
{
    public $session = null;
	protected $db;
	
	
	function __construct(){
		parent::__construct();
		$this->session = \Config\Services::session();
		$this->session->start();
		$this->db = \Config\Database::connect();
	}

	function InsertToDB($insertData){
		//echo "insertData:<pre>"; print_r($insertData); die;
		$table = $this->db->table('e_sign_uploaded_files');
		$table->insert($insertData);
		//echo $query = $this->db->getLastQuery();
		
		if($this->db->affectedRows() > 0){
			return $insertData["id"];
		}else{
			return 0;
		}
	}

	function getUploadedFileData($id, $userId){
		
		$cmd = "SELECT `file_name`,`recipients`,`documentTitle` FROM `e_sign_uploaded_files` WHERE `id` = $id AND `user_id` = $userId";
		
		$query = $this->db->query($cmd);
		$result = $query->getRowArray();
		
		return $result;
		
	}

	function getUserById($id){
		
		$cmd = "SELECT `first_name`, `last_name`, `email` FROM `users` WHERE `id` = $id";
		
		$query = $this->db->query($cmd);
		$result = $query->getRowArray();
		
		return $result;

	}

	function getUsersByEmail($tmpUserEmails){
		
		$emailsStr = implode("','", $tmpUserEmails);

		$cmd = "SELECT `id`, `email` FROM `users` WHERE `email` IN('$emailsStr')";
		
		$query = $this->db->query($cmd);
		$result = $query->getResultArray();
		
		return $result;

	}

	function saveDocumentData($insertData){
		
		$table = $this->db->table('e_sign_documents');
		$table->insert($insertData);
	
		if($this->db->affectedRows() > 0){
			return $insertData["id"];
		}else{
			return 0;
		}
	}

	function saveSignersDocumentData($insertData){
		
		$table = $this->db->table('e_sign_document_signers');
		$query = $table->insertBatch($insertData);
		if($query == true){
			return 1;
		}
		else{
			return 0;
		}

	}

	function getSignerDocumentRawData($docId){

		$result = array();	
		//$cmd = "SELECT * FROM `e_sign_document_signers` WHERE `documentId` = '$docId' AND `documentExpired` = 0 AND `document_status` != 'signed'";
		$cmd = "SELECT * FROM `e_sign_document_signers` WHERE `documentId` = '$docId' AND `documentExpired` = 0";
		$query = $this->db->query($cmd);
		$signerResult = $query->getRowArray();
		
		if(!empty($signerResult)){
			
			$parentDocument = $signerResult["parentDocument"];
		
			$cmd = "SELECT `documentId`, `documentPath` FROM `e_sign_documents` WHERE `id` = $parentDocument";
		
			$query = $this->db->query($cmd);
			$parentDocResult = $query->getRowArray();
		
			$result["parentDoc"] = $parentDocResult;
			$result["signerData"] = $signerResult;

		}

		return $result;
	}


	function updatePartyFilledData($documentId, $signerDocData){
		
		$table = $this->db->table('e_sign_document_signers');
		$table->set('userfilled_documentdata', $signerDocData);
		$table->where('documentId', $documentId);
		$table->update();

		if($this->db->affectedRows() > 0){
			return 1;
		}else{
			return 0;
		}
	}

	function updatePartySignStatus($documentId, $email, $status){
		$isComplete = 1;
		$cmd = "SELECT `id`, `document_status` FROM `e_sign_documents` WHERE `documentId` = '$documentId'";
		
		$query = $this->db->query($cmd);
		$parentDocResult = $query->getRowArray();
		$newUpdateArr = array();
		

		if(!empty($parentDocResult)){
			$id = $parentDocResult["id"];
			$document_status = $parentDocResult["document_status"];
			$newUpdateArr = json_decode($document_status, true);
			
			foreach($newUpdateArr as &$newUpdateRw){
				if($newUpdateRw["email"] == $email){
					$newUpdateRw["status"] = $status;
				}
			}
			

			foreach($newUpdateArr as $tmpNewUpdateRw){
				if(strtolower($tmpNewUpdateRw["status"]) != "signed"){
					$isComplete = 0;
				}
			}
		}

		if(!empty($newUpdateArr)){
			$newUpdateJson = json_encode($newUpdateArr);
			
			$table = $this->db->table('e_sign_documents');
			$table->set('document_status', $newUpdateJson);
			$table->set('isComplete', $isComplete);
			$table->where('documentId', $documentId);
			$table->update();
			
			if($this->db->affectedRows() > 0){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}

	
	

	function updateSignerDocStatus($signerDocumentId, $status){
		
		$table = $this->db->table('e_sign_document_signers');
		$table->set('document_status', $status);
		$table->where('documentId', $signerDocumentId);
		$table->update();

		if($this->db->affectedRows() > 0){
			return 1;
		}else{
			return 0;
		}
	}

	function saveDocumentESignHash($insertData){
		//e_sign_electronic_signatures
		//save electronic signatures of document
		$table = $this->db->table('e_sign_electronic_signatures');
		$table->insert($insertData);
	
		if($this->db->affectedRows() > 0){
			return $insertData["id"];
		}else{
			return 0;
		}
	}

	function getDocumentByUser($signerEmail, $signerDocumentId){
		$result = array();	
		$cmd = "SELECT `id`,`parentDocument`,`signerId`, `signerEmail` FROM `e_sign_document_signers` WHERE `signerEmail` = '$signerEmail' AND `documentId` ='$signerDocumentId'";
		
		$query = $this->db->query($cmd);
		$result = $query->getRowArray();
		
		if(!empty($result)){
			$parentDocumentId = $result["parentDocument"];
			//get document owner
			$result2 = array();	
			$cmd = "SELECT `senderId` FROM `e_sign_documents` WHERE `id` = '$parentDocumentId'";
			$query = $this->db->query($cmd);
			$result2 = $query->getRowArray();

			if(!empty($result2)){
				$senderId = $result2["senderId"];
			}else{
				$senderId = 0; 
			}
			
			$result["senderId"] = $senderId;
		}
		
		return $result;	
	
	}

	function getDocumentTitle($fileId){
		$cmd = "SELECT `file_name` FROM `e_sign_uploaded_files` WHERE `id` = '$fileId'";
		$query = $this->db->query($cmd);
		$result = $query->getRowArray();
		return $result;
	}

	function getDocExpiry($docId){
		$cmd = "SELECT `expiresInDays`, `expiryDate`, `alertOneDyBfrExp` FROM `e_sign_uploaded_files` WHERE `id` = '$docId'";
		$query = $this->db->query($cmd);
		$result = $query->getRowArray();

		return $result;
	}
	
	function getDocRecipients($docId){
		$cmd = "SELECT `recipients` FROM `e_sign_uploaded_files` WHERE `id` = '$docId'";
		$query = $this->db->query($cmd);
		$result = $query->getRowArray();

		$recipients = array();

		if(!empty($result) && $result["recipients"] != ""){
			$recipients = json_decode($result["recipients"], true);
		}

		return $recipients;
	}

	function getDocOwnerEmail($docId){
		
		$result = array();
		
		$cmd = "SELECT `senderId` FROM `e_sign_documents` WHERE `id` = '$docId'";
		$query = $this->db->query($cmd);
		$documentResult = $query->getRowArray();

		if(!empty($documentResult)){
			$userID = $documentResult['senderId'];
			$cmd = "SELECT `email` FROM `users` WHERE `id` = '$userID'";
			$query = $this->db->query($cmd);
			$userResult = $query->getRowArray();

			if(!empty($userResult)){
				$email = $userResult['email'];
				$result["email"] = $email;
			}

		}

		return $result;
	}


	function getSenderIdByDoc($docId){
		
		$cmd = "SELECT `senderId` FROM `e_sign_documents` WHERE `documentId` = '$docId'";
		$query = $this->db->query($cmd);
		$documentResult = $query->getRowArray();
		return $documentResult;
	}

	function updateDocAccessOTP($docId, $otp){
		
		$result = array();	
		$cmd = "SELECT `parentDocument`, `signerEmail`, `signerName` FROM `e_sign_document_signers` WHERE `documentId` = '$docId' AND `documentExpired` = 0";
		$query = $this->db->query($cmd);
		$signerResult = $query->getRowArray();
		
		if(!empty($signerResult)){
			
			$parentDocument = $signerResult['parentDocument'];
			$cmd = "SELECT `uploadId`, `documentTitle` FROM `e_sign_documents` WHERE `id` = $parentDocument";
			$query = $this->db->query($cmd);
			$documentResult = $query->getRowArray();

			$uploadId = $documentResult["uploadId"];
			$cmd = "SELECT `documentTitle` FROM `e_sign_uploaded_files` WHERE `id` = $uploadId";
			$query = $this->db->query($cmd);
			$uploadResult = $query->getRowArray();
			$customDocTitle = $uploadResult["documentTitle"];

			$table = $this->db->table('e_sign_document_signers');
			$table->set("otp", $otp);
			$table->set("otpDateTime", date("Y-m-d H:i:s"));
			$table->where('documentId', $docId);
			$table->update();

			if($this->db->affectedRows() > 0){
				$result = $signerResult;
				$result["customDocTitle"] = $customDocTitle;
			}
			
		}

		return $result;
	}

	function getMyDocuments($userId,$offset=0){
	
		$cmd = "SELECT `uploadId`, `documentId`, `no_of_parties`, `documentPath`, `isComplete`, `created_at` FROM `e_sign_documents` WHERE `senderId` = $userId LIMIT 10 OFFSET $offset";
		$query = $this->db->query($cmd);
		$documentRows = $query->getResultArray();
		
		if(!empty($documentRows)){
			$docDetailsArr = array();
			$uploadIdsArr = array();

			foreach($documentRows as $docRw){
				$uploadIdsArr[] = $docRw['uploadId'];
			}
			
			$uploadIdsStr = implode(",", $uploadIdsArr);
			//get document custom-title
			$cmd = "SELECT `id`,`file_name`, `system_file_name`, `documentTitle` FROM `e_sign_uploaded_files` WHERE `id` IN($uploadIdsStr) AND `user_id` = $userId";
			$query = $this->db->query($cmd);
			$uploadRows = $query->getResultArray();

			if(!empty($uploadRows)){
				foreach($uploadRows as $upldRw){
					$tmpId = $upldRw["id"];
					$docDetailsArr[$tmpId] = $upldRw;
				}
			}

			foreach($documentRows as &$docRw){
				$tmpId = $docRw['uploadId'];
				
				$docRw['docDetails'] = $docDetailsArr[$tmpId];
			}

		}

		return $documentRows;

	}


	function getSignedDocument($documentId, $userId){

		$cmd = "SELECT `id`, `uploadId`, `documentId`, `no_of_parties`, `documentPath`, `isComplete`, `created_at` FROM `e_sign_documents` WHERE `senderId` = $userId AND `documentId` = '$documentId'";
		$query = $this->db->query($cmd);
		$documentRow = $query->getRowArray();
		
		//document details
		//echo "<pre>";
		//print_r($documentRow);
		$finalResult = array();
		if(!empty($documentRow)){
			
			$finalResult["masterDocument"] = $documentRow;

			$parentDocumentId = $documentRow["id"];
			$uploadId = $documentRow["uploadId"];

			$cmd = "SELECT `first_name`, `last_name`, `email` FROM `users` WHERE `id` = $userId";
			$query = $this->db->query($cmd);
			$userRow = $query->getRowArray();	
			
			//sender details	
			//print_r($userRow);
			$finalResult["sender"] = $userRow;

			//get uploaded file
			$cmd = "SELECT `file_name`, `recipients` FROM `e_sign_uploaded_files` WHERE `id` = $uploadId";
			$query = $this->db->query($cmd);
			$recipentsRow = $query->getRowArray();	
			$recipients = json_decode($recipentsRow['recipients'], true);
			//print_r($recipentsRow);


			//get signers document
			$cmd = "SELECT `id`, `parentDocument`, `documentId`, `signerEmail`, `document_status` FROM `e_sign_document_signers` WHERE `parentDocument` = $parentDocumentId";
			$query = $this->db->query($cmd);
			$recipentsDocs = $query->getResultArray();	

			$recipientWiseData = array();
			//print_r($recipentsDocs);
			foreach($recipentsDocs as $tmpSignerDoc){
				$recipientWiseData[$tmpSignerDoc["signerEmail"]] = $tmpSignerDoc;
			}

			foreach($recipients as $k => &$tmpSignerDocc){
				$tmpData = $recipientWiseData[$k];
				$tmpSignerDocc["document"] = $tmpData;
			}

			$recipentsRow['recipients'] = json_encode($recipients);
			$finalResult["recipents"] = $recipentsRow;
			//print_r($recipentsRow);
			//print_r($finalResult);

		}

		return $finalResult;

	}


}
?>