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
		
		$cmd = "SELECT `email` FROM `users` WHERE `id` = $id";
		
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

}
