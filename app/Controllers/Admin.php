<?php

namespace App\Controllers;
//use CodeIgniter\Controller; //load base controller
use App\Models\Admin_Model; //load model

class Admin extends BaseController
{
	public $request = null;
	public $session = null;
	public $admin_model = null;
	
	function __construct(){
		
		$this->session = \Config\Services::session();
		$this->session->start();
		
		$this->request = \Config\Services::request();
		
		$this->admin_model = new Admin_Model();
		
		helper("kitchen");
	}
	
	
	function signup(){

		if($this->request->isAJAX()){
			
			$id = db_randomnumber();
			$first_name = $this->request->getPost('registerFName');
			$last_name = $this->request->getPost('registerLName');
			//$isd = $this->request->getPost('registerIsd');
			//$phone = $this->request->getPost('registerPhone');
			$email = $this->request->getPost('registerEmail');
			$password = $this->request->getPost('registerPassword');
			$confirmpassword = $this->request->getPost('registerRepeatPassword');
			
			$insertData = array();
			$insertData["id"] = $id;
			$insertData["first_name"] = $first_name;
			$insertData["last_name"] = $last_name;
			//$insertData["phone"] = $isd."_".$phone;
			$insertData["email"] = $email;
			$insertData["password"] = $password;
			
			$saveId = $this->admin_model->signup($insertData);
			
			if($saveId > 0){
				
				//send email for account  verification
				
				$result = array("C" => 100, "R" => "", "M" => "success");
			}else{
				$result = array("C" => 101, "R" => "", "M" => "error");
			}
			
			echo json_encode($result); die;
			
			
		}else{
			
			$data = array();
			$data["page_tilte"] = "Sign Up";
			return view('admin/signup', $data);
		
		}
	}
	
	function signin(){
		
		if($this->request->isAJAX()){
			$email = $this->request->getPost('email');
			$password = $this->request->getPost('password');
			
			$insertData = array();
			$insertData["email"] = $email;
			$insertData["password"] = $password;
			
			$resp = $this->admin_model->signin($insertData);
			
			if(!empty($resp)){
				
				if($resp["email_verified"] > 0){
					$result = array("C" => 100, "R" => $resp, "M" => "success");	
				}else{
					$result = array("C" => 102, "R" => "un-verified account", "M" => "error");	
				}
				
			}else{
				$result = array("C" => 101, "R" => array(), "M" => "error");
			}
			
			echo json_encode($result); die;	
		
		}else{
			
			$data = array();
			$data["page_tilte"] = "Sign In";
			$data["accountVerification"] = 0;
			return view('admin/signin', $data);
		
		}
		
	}
	
	function accountverify(){
		
		if($this->request->isAJAX()){
			
			$email = $this->request->getPost('email');
			$password = $this->request->getPost('password');
			
			$insertData = array();
			$insertData["email"] = $email;
			$insertData["password"] = $password;
		
			$resp = $this->admin_model->accountverify($insertData);
			
			if(!empty($resp)){
				if($resp["email_verified"] > 0){
					$result = array("C" => 100, "R" => $resp, "M" => "success");	
				}else{
					$result = array("C" => 102, "R" => "un-verified account", "M" => "error");	
				}
			}else{
				$result = array("C" => 101, "R" => array(), "M" => "error");
			}		
			echo json_encode($result); die;	
		
		}else{
			
			$data = array();
			$data["page_tilte"] = "Verify";
			$data["accountVerification"] = 1;
			return view('admin/signin', $data);
		
		}
	
	}
	
	function logout(){
		
		$this->session->destroy();

		return redirect()->to("signin");
	}

}
?>