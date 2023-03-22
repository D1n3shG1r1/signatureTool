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
	
		if($this->db->affectedRows() > 0){
			return $insertData["id"];
		}else{
			return 0;
		}
	}

	public function GetData($id)
	{
		$table = $this->db->table('e_sign_uploaded_files');
		$data = $table->where('id', $id)->get();

		
		return $data;
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
			
		}

		if(!empty($newUpdateArr)){
			$newUpdateJson = json_encode($newUpdateArr);
			
			$table = $this->db->table('e_sign_documents');
			$table->set('document_status', $newUpdateJson);
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

	function getDocumentByUser($signerId, $signerDocumentId){
		$result = array();	
		$cmd = "SELECT `id`,`parentDocument`,`signerId` FROM `e_sign_document_signers` WHERE `signerId` = '$signerId' AND `documentId` ='$signerDocumentId'";
		
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

}
?>