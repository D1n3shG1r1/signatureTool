<?php
namespace  App\Models;
use CodeIgniter\Model;
use Config\Database;


class Admin_Model extends Model{
	
	public $session = null;
	protected $db;
	
	
	function __construct(){
		parent::__construct();
		$this->session = \Config\Services::session();
		$this->session->start();
		$this->db = \Config\Database::connect();
	}

	function signup($insertData){
		
		$insertData["password"] = sha1($insertData["password"]);
		$insertData["updated_at"] = date("Y-m-d H:i:s");
		
		$table = $this->db->table('users');
		$table->insert($insertData);
	
		if($this->db->affectedRows() > 0){
			return $insertData["id"];
		}else{
			return 0;
		}
	}
	
	function signin($data){
		
		$email = $data["email"];
		$password = sha1($data["password"]);
		//$password = $data["password"];
		
		$cmd = "SELECT `id`, `first_name`, 	`last_name`, `email`, `email_verified` FROM `users` WHERE `email` = '$email' AND `password` = '$password'";
		
		$query = $this->db->query($cmd);
		$result = $query->getRowArray();
		
		if(!empty($result)){
			if($result["email_verified"] > 0){
				$sessArr = array(
					"loginId" => $result["id"],
					"loginEmail" => $result["email"],
					"loginFName" => $result["first_name"],
					"loginLName" => $result["last_name"]
				);
				//$this->session->set("loginId", $result["id"]);		
				$this->session->set($sessArr);		
			}
			
			return $result;
		}else{
			return array();
		}
		
	}
	
	function accountverify($data){
		
		$email = $data["email"];
		$password = sha1($data["password"]);
		
		$cmd = "SELECT `id`, `first_name`, `last_name`, `email`, `email_verified` FROM `users` WHERE `email` = '$email' AND `password` = '$password'";
		
		$query = $this->db->query($cmd);
		$result = $query->getRowArray();
		
		if(!empty($result)){
			
			if($result["email_verified"] == 0){
						
				$id = $result["id"];
				$dt = date("Y-m-d H:i:s");
				$cmd = "UPDATE `users` SET `email_verified` = 1, `email_verified_at` = '$dt' WHERE `id` = $id AND `email` = '$email'";
				
				$query = $this->db->query($cmd);
				
				//$result = $query->getRowArray();
				if($this->db->affectedRows() > 0){
					
					$cmd = "SELECT `id`, `first_name`, 	`last_name`, `email`, `email_verified` FROM `users` WHERE `email` = '$email' AND `password` = '$password'";
			
					$query = $this->db->query($cmd);
					$result = $query->getRowArray();
					
					if(!empty($result)){
				
						if($result["email_verified"] == 1){
							$sessArr = array(
								"loginId" => $result["id"],
								"loginEmail" => $result["email"],
								"loginFName" => $result["first_name"],
								"loginLName" => $result["last_name"]
							);
							//$this->session->set("loginId", $result["id"]);		
							$this->session->set($sessArr);
							
						}
					}
				
				}
				
			}
			
			return $result;
		}else{
			return array();
		}
		
	}
	
}

?>