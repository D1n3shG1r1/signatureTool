<?php

namespace App\Controllers;
use App\Models\Document_Model; //load model
//use Spipu\Html2Pdf\Html2Pdf;

//use setasign\Fpdi\Fpdi;
//use setasign\Fpdf\Fpdf;
use App\Libraries\GeneratePdf; //import library
//use App\Libraries\PDF; //import library

use Config\Encryption;
use Config\Services;
//use setasign\Fpdi\FpdfTpl;

class Document extends BaseController
{
	public $request = null;
	public $session = null;
	public $admin_model = null;
	public $document_model = null;
	public $pdf = null;
	public $router = null;
	function __construct(){
		
		$this->session = \Config\Services::session();
		$this->session->start();
		
		//Request Object
		$this->request = \Config\Services::request();
		
		//Model Object
		$this->document_model = new Document_Model();
		
		$router = \CodeIgniter\Config\Services::router();
		
		$controllerNameUri = $router->controllerName();
		$controllerNameUriParts = explode("\\", $controllerNameUri);
		$controllerName = $controllerNameUriParts[count($controllerNameUriParts) - 1];
		$methodName = $router->methodName();
		
		
		helper("url");
		helper("kitchen");
		helper("EncryDcry");

		$loginId = $this->session->get('loginId');
		
		if(!$loginId || $loginId == "" || $loginId == null){
			
			if(strtolower($controllerName) == "document" && strtolower($methodName) != "sign"){
				//skip session for signing doc
				customredirect("signin");
			}
			
		}
		
	}

	private function is_session_available(){
        return redirect()->to("signin"); die;
    }

	function upload(){
		$data = array();
		$data["page_tilte"] = "Upload";
		return view('admin/upload', $data);	
	}

	function fileupload(){

		$loginId = $this->session->get('loginId');
		//$loginId = "1673874254153097";

		/*echo "loginId:" . $loginId . ",FILES:<pre>";
		print_r($_POST);
		print_r($_FILES);
		die;*/

		$RecipientNamesArr = $this->request->getPost('RecipientName');
		$RecipientEmailsArr = $this->request->getPost('RecipientEmail');
		$recipientAuthInputBttnsArr = $this->request->getPost('recipientAuthInputBttn');
		$accessCodeOtpOptArr = $this->request->getPost('accessCodeOtpOpt');
		$accessCodeArr = $this->request->getPost('accessCode');
		$documentTitle  = $this->request->getPost('documentTitle');
		$recipientMessage  = $this->request->getPost('recipientMessage');
		$expiresInDays  = $this->request->getPost('expiresInDays');
		$expiryDate = $this->request->getPost('expiryDate');
		//$alertOneDyBfrExp = $this->request->getPost('alertOneDyBfrExp');
		$alertOneDyBfrExp = $this->request->getPost('alertOneDyBfrExpInput');
		
		
		$recipientsArr = array();			
			
		foreach($RecipientEmailsArr as $k => $RcpntEmlRw){
			
			$tmpRwId = $k;
			$tmpNm = $RecipientNamesArr[$tmpRwId];
			$tmpEml = $RcpntEmlRw;

			$tmpAuthChecked = $recipientAuthInputBttnsArr[$tmpRwId];
			$tmpAccCodeOpt = $accessCodeOtpOptArr[$tmpRwId];
			$authType = 0;
			$tmpOtp = "";
			$tmpAccessCode = "";

			if($tmpAuthChecked == 1){
				//checked
				if($tmpAccCodeOpt == 1){
					//otp
					$authType = 1;
					$tmpOtp = genOtp();
				}else if($tmpAccCodeOpt == 2){
					//access code
					$authType = 2;
					$tmpAccessCode = $accessCodeArr[$tmpRwId];
				}
			}else{
				//unchecked
				$authType = 0;
			}

			$recipientsArr[$tmpEml]["email"] = $tmpEml;
			$recipientsArr[$tmpEml]["name"] = $tmpNm; 
			$recipientsArr[$tmpEml]["authType"] = $authType; 
			$recipientsArr[$tmpEml]["accessCode"] = $tmpAccessCode; 
			$recipientsArr[$tmpEml]["otp"] = $tmpOtp; 
		}

		//print_r($recipientsArr); die;
		
		$file = $_FILES["fileupload"];
		$fileName = $file['name'];
		$tmpFileName = $file['tmp_name'];
		$fileType = $file['type'];
		
		$fileId = db_randomnumber();
		//$documentPath = FCPATH . 'userassets\uploads\\' . $loginId;
		$documentPath = FCPATH . "userassets\uploads\\" . $loginId;
		if(!file_exists($documentPath)){
			create_local_folder($documentPath);
		}

		$fileNameParts = explode(".", $fileName);
		$fileExt = end($fileNameParts);
		$newFileName = $fileId.".".$fileExt;
		$file_path = $documentPath."/".$newFileName;

		move_uploaded_file($tmpFileName,$file_path);	

		$data = array(
			'id' => $fileId,
			'file_name' => $fileName,
			'system_file_name' => $newFileName,
			'file_type' => $fileType,
			'user_id'	=> $loginId,
			'recipients' => json_encode($recipientsArr),
			'documentTitle' => $documentTitle,
			'recipientMessage' => $recipientMessage,
			'expiresInDays' => $expiresInDays,
			'expiryDate' => date("Y-m-d H:i:s", strtotime($expiryDate)),
			'alertOneDyBfrExp' => $alertOneDyBfrExp
		);

		//echo "<pre>"; print_r($data); die;

		$uploadId = $this->document_model->InsertToDB($data);
		$parameters = array();
		$parameters[] = $uploadId;
		
		return redirect()->to("prepare/$uploadId");
		
		/*
		if($uploadId > 0){

			$result = array(
				"C" => 100,
				"R" => array("docId" => $uploadId),
				"M" => "success"
			);

		}else{
			$result = array(
				"C" => 101,
				"R" => array(),
				"M" => "error"
			);
		}
		
		echo json_encode($result); die;
		*/


	}

	function filedelete(){
		echo "<pre>";
		print_r($_FILES);
		die;
	}

	function pagenotfound(){
		echo "404 page not found"; die;
	}

    function prepare($fileId){

		$loginId = $this->session->get('loginId');
		$loginEmail = $this->session->get('loginEmail');

		$fileData = $this->document_model->getUploadedFileData($fileId, $loginId);
		
		//echo "fileData:<pre>"; print_r($fileData); die;
		
		if(!empty($fileData)){
			
			$tmprecipientsJson = $fileData["recipients"];
			$tmprecipientsArr = json_decode($tmprecipientsJson, true);
			$finalRecipients = array();
			$k = 0;
			foreach($tmprecipientsArr as $rcpntrw){
				$finalRecipients[$k]["name"] = $rcpntrw["name"];
				$finalRecipients[$k]["email"] = $rcpntrw["email"];
				$k++;
			}



			$documentPath = "/userassets/uploads/" . $loginId;
		
			$fileExt = ".pdf";
			$newFileName = $fileId.$fileExt;
			$file_path = $documentPath."/".$newFileName;

			$data = array();
			$data["loginId"] = $loginId;
			$data["loginEmail"] = $loginEmail;
			$data["page_tilte"] = "Document Prepare";
			$data["document"] = $file_path;
			$data["documentId"] = $fileId;
			$data["fileName"] = $fileData["file_name"];
			$data["documentTitle"] = $fileData["documentTitle"];
			$data["recipients"] = $finalRecipients;
			
			return view('admin/documentprepare', $data);

		}else{
			return redirect()->to("pagenotfound");
		}

    }

	function test(){
		
		$tmpDocumentId = "afad9cd64d8edc755ef426d56bc9522c";
		$homePath = FCPATH."index.php";
		$cmd = "php '$homePath emailengine sendDocuSingColl $tmpDocumentId' > /dev/null &";
		
		$fpath = FCPATH."/test.txt";
		$fp = fopen($fpath, "w");
		fwrite($fp, $cmd);
		fclose($fp);

		//exec("php $homePath emailengine sendDocuSingColl $tmpDocumentId > /dev/null &", $out);
		//echo $cmd;
		echo $cmd;
		exec("php $cmd", $out);

		echo "out:" . implode("\n", $out); die;

		/*	
		$jsonStrUrlEnc = "e1d46561da8c90973f0fd3c893d22fac";
		// $homePath = "digitalsignature/index.php/";
		//echo FCPATH; die;
		$homePath = publicFolder()."app/Controllers/Emailengine.php";
		$homePath = FCPATH."index.php";
		// echo "<pre>";
		// print_r(publicFolder()."app/Controllers/Emailengine.php");
		die;
		exec("php $homePath emailengine sendDocuSingColl $jsonStrUrlEnc > /dev/null &", $out);
		echo implode("\n", $out); die;
		*/

	}
	
	function saveandsenddocument(){
		$docId = $this->request->getPost('documentId'); //file id
		$docdata = $this->request->getPost('data');
		
		//echo "docId:".$docId."<br>";
		//echo "<pre>";
		//print_r($docdata);
		
		//die;
		
		$fileId = $docId;
		
		$loginId = $this->session->get('loginId');
		$documentPath = FCPATH."/userassets/uploads/" . $loginId;
		$destFolderPath = FCPATH."/userassets/mydocuments/" . $loginId;
		//echo "<pre>"; print_r($docdata); die;
		//get loggedIn user email
		//$uploadId = $this->document_model->InsertToDB($data);
		$user = $this->document_model->getUserById($loginId);
		$loginEmail = $user["email"];
		$loginFirstName = $user["first_name"];
		$loginLastName = $user["last_name"];
		

		$accessCodeMedia = "email";
		$documentData = array();
		$signersData = array();
		$signersNameEmailData = array();
		
		//document data
		$docInfo = $this->document_model->getDocumentTitle($fileId);
		$documentTitle = $docInfo["file_name"];
		$parentDocumentId = db_randomnumber(); //raw / source document Id primary key	
		$documentId = random_unique_string();  // unique alphanumeric has string
		
		//move the uploaded file to Mydocuments and remove from uploads folder and uploades table		
		create_local_folder($destFolderPath); //TEMPORARY COMMENTED DUE TO DEV PURPOSE
	
		$fileExt = ".pdf";
		$fileName = $fileId.$fileExt;
		$file_path = $documentPath."/".$fileName;

		$newFileName = $documentId . $fileExt;
		$destFile = $destFolderPath."/".$newFileName;
		$src = $file_path; //source file
		$dst = $destFile; //destination file
		moveFileOneDirToAnother($src, $dst);  //TEMPORARY COMMENTED DUE TO DEV PURPOSE
		
		//get recipients
		$recipientsResult = $this->document_model->getDocRecipients($docId);
		//echo "<pre>"; print_r($recipientsResult); die;

		$docExpiryInfo = $this->document_model->getDocExpiry($docId);
		$expiresInDays = $docExpiryInfo["expiresInDays"];
		$expiryDate = $docExpiryInfo["expiryDate"];
		$alertOneDyBfrExp = $docExpiryInfo["alertOneDyBfrExp"];
		
		

		//get sender and reciever Ids
		$tmpUserEmails = array();
		$tmpUserIdEmails = array();
		foreach($docdata as $key => $val){
			
			$keyParts = explode("#DK#", $key);
		
			$tmpName = $keyParts[0];
			$tmpEmail = $keyParts[1];
			$tmpTag = $keyParts[2];
			$tmpColor = $keyParts[3];

			$tmpUserEmails[] = $tmpEmail;

			$tmpUserIdEmails[$tmpEmail] = array("id" => 0, "email" => $tmpEmail);

		}
	
		//echo "<pre>"; print_r($tmpUserIdEmails); die;

		$usersResult = $this->document_model->getUsersByEmail($tmpUserEmails);
		if(!empty($usersResult)){
			
			//email wise users
			
			foreach($usersResult as $userRw){
				$id = $userRw["id"];
				$email = $userRw["email"];
				$tmpUserIdEmails[$email]["id"] = $id;
			}

		}
		
		$recieverIdsArr = array();
		foreach($tmpUserIdEmails as $tmpEmlKy => $tmpVl){
			//$tmpVl["email"];
			$recieverIdsArr[] = $tmpVl["id"];
		}
		
		//echo "<pre>"; print_r($recieverIdsArr); die;

		$partyAssets = array();
		$documentStatus = array();
		foreach($docdata as $asstsk => $asstsv){

			$asstskArr = explode("#DK#", $asstsk);
			$tmpName = $asstskArr[0];
			$tmpEmail = $asstskArr[1];
			$tmpTag = $asstskArr[2];
			$tmpClr = $asstskArr[3];

			$tmpRecipientRw = $recipientsResult[$tmpEmail];
			//$tmpRecipientRw["email"];
			//$tmpRecipientRw["name"];
			$tmpauthType = $tmpRecipientRw["authType"];
			$tmpaccessCode = $tmpRecipientRw["accessCode"];
			$tmpotp = $tmpRecipientRw["otp"];

			$tmpArr = array();
			$tmpArr["dataFeilds"] = $asstsv;
			$tmpArr["email"] = $tmpEmail;
			$tmpArr["name"] = $tmpName;
			$tmpArr["authType"] = $tmpauthType;
			$tmpArr["accesscode"] = $tmpaccessCode; 
			$tmpArr["otp"] = $tmpotp; 
			//Email, Name, Access code, data-feilds
		
			$partyAssets[] = $tmpArr;
			
			$documentStatus[] = array("email" => $tmpEmail, "status" => "pending");
		}

		$partyAssetsJson = json_encode($partyAssets);
		$documentStatusJson = json_encode($documentStatus);


		$documentSrcPath = "/userassets/mydocuments/" . $loginId."/".$newFileName;
		$senderId = $loginId;
		$recieverIds = implode(",", $recieverIdsArr);
		$noOfParties = count($docdata);
		$documentData["id"] = $parentDocumentId;
		$documentData["uploadId"] = $fileId;
		$documentData["documentId"] = $documentId;
		$documentData["documentTitle"] = $documentTitle;
		$documentData["documentPath"] = $documentSrcPath;
		$documentData["senderId"] = $senderId;
		$documentData["recieverId"] = $recieverIds;
		$documentData["no_of_parties"] = $noOfParties;
		$documentData["party_assets"] = $partyAssetsJson;
		$documentData["accessCodeMedia"] = $accessCodeMedia;
		$documentData["document_sent_status"] = 1;
		$documentData["email_order_sequence"] = "";
		$documentData["document_status"] = $documentStatusJson; //document status, Json in partywise and possible value is sent, viewed, signed
		$documentData["isComplete"] = 0; //0-if not all parties signed, 1-if signed by all
		$documentData["complete_documents"] = ""; //as a json {DocId, DocPath, signed by UserEmail}
		
		
		//signer's data
		foreach($docdata as $k => $v){
			
			$tmpUserStr = $k; //user name, email, tag, color details
			$tmpUserDocData = $v; //user document data
		
			$tmpUserStrArr = explode("#DK#", $tmpUserStr);
		
			$tmpUserName = $tmpUserStrArr[0]; //name
			$tmpUserEmail = $tmpUserStrArr[1]; //email
			$tmpUserTag = $tmpUserStrArr[2]; //tag
			$tmpUserColor = $tmpUserStrArr[3]; //color
		
			
			$tmpUserDocJsonData = json_encode($tmpUserDocData);
			
			$signerId = $tmpUserIdEmails[$tmpUserEmail]["id"];
			if($signerId > 0){
				$internalUser = 1;
			}else{
				$internalUser = 0;
			}
			
			$tmpRecipientRw = $recipientsResult[$tmpUserEmail];
			$tmpauthType = $tmpRecipientRw["authType"];
			$tmpaccessCode = $tmpRecipientRw["accessCode"];
			$tmpotp = $tmpRecipientRw["otp"];

			$crrDate = date("Y-m-d H:i:s");
			$documentExpiry = $expiryDate;
			$lastReminder = $crrDate;
			$documentSentDate = $crrDate;
			
			$documentId = random_unique_string(); // unique alphanumeric has string
			
			//signers data
			$singleSignerData = array();
			
			$singleSignerData["id"] = db_randomnumber(); 
			$singleSignerData["parentDocument"] = $parentDocumentId; // source/raw document
			$singleSignerData["documentId"] = $documentId; //unique long string
			$singleSignerData["signerEmail"] = $tmpUserEmail;
			$singleSignerData["signerName"] = $tmpUserName;
			$singleSignerData["signerId"] = $signerId; //0 for external user and numericvalue for internal user
			$singleSignerData["internalUser"] = $internalUser; // 0-external or 1-internal
			$singleSignerData["document_data"] = $tmpUserDocJsonData;

			$singleSignerData["authType"] = $tmpauthType;
			$singleSignerData["otp"] = $tmpotp;
			$singleSignerData["accessCode"] = $tmpaccessCode;
			
			$singleSignerData["accessCodeMedia"] = $accessCodeMedia;
			$singleSignerData["documentExpiry"] = $documentExpiry;
			$singleSignerData["documentExpired"] = 0; //0-not-expired, 1-expired
			$singleSignerData["lastReminder"] = $lastReminder;
			$singleSignerData["documentSentDate"] = $documentSentDate;
		

			$signersNameEmailData[] = array("name" => $tmpUserName,"email" => $tmpUserEmail);
			$signersData[] = $singleSignerData;

		}
		
		//--- save file data to db
		//echo "<pre>documentdata:"; print_r($documentData);
		//echo "signersData:<pre>"; print_r($signersData); 
		//die;
		$docResponse = $this->document_model->saveDocumentData($documentData);
		
		$signersDataResponse = $this->document_model->saveSignersDocumentData($signersData);
		
		/*
		echo "docResponse:" . $docResponse;
		echo "signersDataResponse:" . $signersDataResponse;
		die;
		*/
		
		if($docResponse > 0 && $signersDataResponse > 0){
			//generate document link and share via email
			
			foreach($signersData as $signersDataRw){
				
				$tmpDocumentId = $signersDataRw["documentId"];
				
				/*
				$fpath = FCPATH."/test.txt";
				$fp = fopen($fpath, "a+");
				fwrite($fp, "\n\n\n tmpDocumentId:".$tmpDocumentId);
				fclose($fp);


				$fpath = FCPATH."/test.txt";
				$homePath = FCPATH."index.php";
				$fp = fopen($fpath, "a+");
				
				fwrite($fp, "cmd:php $homePath emailengine sendDocuSingColl $tmpDocumentId > /dev/null &");
				fclose($fp);
				*/

				$homePath = FCPATH."index.php";
				//exec("php $homePath emailengine sendDocuSingColl $tmpDocumentId > /dev/null &", $out);
				exec("php $homePath emailengine sendDocuSingColl $tmpDocumentId");
				
			}

			$result = array("C" => 100, "R" => array(), "M" => "success");

		}else{
			// something went wrong try again
			$result = array("C" => 101, "R" => array(), "M" => "error");
		}
		
		echo json_encode($result); die;
	} 

	function sign(){
		//http://localhost/digitalsignature/sign/?documentId=086256e9e2d5e7549c8e44bd4b062e30
		
		if($this->request->is('get')){

			$docId = $this->request->getGet('documentId');
			$t = $this->request->getGet('t');
			$loginId = $this->session->get('loginId');

			//get document ready and all its elements	
			$signersData = $this->document_model->getSignerDocumentRawData($docId);
			
			//echo "<pre>"; print_r($signersData); die;

			if(!empty($signersData)){
				
				$authType = $signersData["signerData"]["authType"];
				if($authType > 0){

					if($t && $t == $this->session->get("docAccessToken")){

						//update status to viewed from sent
						$this->document_model->updateSignerDocStatus($docId, "viewed");
						
						$data = array();
						$data["page_tilte"] = "Document Sign";
						$data["document"] = $signersData["parentDoc"]["documentPath"];
						$data["documentId"] = $signersData["parentDoc"]["documentId"];
						$data["signersData"] = $signersData["signerData"];
						
						return view('admin/documentsign', $data);
						
					}else{
						
						//get email of document owner
						$parentDocId = $signersData["signerData"]["parentDocument"];
						$ownerResult = $this->document_model->getDocOwnerEmail($parentDocId);
						
						if($authType == 2){
							
							//access code form

							$data = array();
							$data["page_tilte"] = "Document Access Code";
							$data["documentId"] = $docId;
							$data["authType"] = $authType; //$signersData["signerData"]["authType"];
							$data["ownerEmail"] = $ownerResult["email"];

							return view('admin/accesscoderequired', $data);
						
						}else if($authType == 1){
							
							//otp form

							//send otp
							$this->sendDocAccessOtp($docId);
							
							$data = array();
							$data["page_tilte"] = "Document Access Code";
							$data["documentId"] = $docId;
							$data["authType"] = $authType; // $signersData["signerData"]["authType"];
							$data["ownerEmail"] = $ownerResult["email"];

							return view('admin/accesscoderequired', $data);
						}
					}


				}else{

					$data = array();
					$data["page_tilte"] = "Document Sign";
					$data["document"] = $signersData["parentDoc"]["documentPath"];
					$data["documentId"] = $signersData["parentDoc"]["documentId"];
					$data["signersData"] = $signersData["signerData"];
					
					//echo "data:<pre>"; print_r($data); die;

					return view('admin/documentsign', $data);
				}
		
			}else{

				//invalid link
				
				$data = array();
				$data["page_tilte"] = "Invalid Document Id";
				
				return view('admin/invalidlink', $data);

			}

			
		}else if($this->request->isAJAX()){
			//"accesscode":accesscode, "documentId":documentId
			$documentId = $this->request->getPost('documentId');
			$userAccessCode = $this->request->getPost('accesscode');
			$authType = $this->request->getPost('authType');

			$signersData = $this->document_model->getSignerDocumentRawData($documentId);
			//echo "<pre>"; print_r($signersData); die;
			if(!empty($signersData)){
					
				//$authType = $signersData["signerData"]["authType"];
				if($authType == 2){
					
					$accessCode = $signersData["signerData"]["accessCode"];
					
					if($userAccessCode == $accessCode){
						$accessToken = sha1(db_randomnumber());
						$sessArr = array(
							"docAccessToken" => $accessToken
						);
						
						$this->session->set($sessArr);

						$result = array(
							'C' => 100,
							'R' => array("accessToken" => $accessToken),
							'M' => 'success'
						);

					}else{
						$result = array(
							'C' => 101,
							'R' => array('message'=>'invalid access code'),
							'M' => 'error'
						);
					}

					echo json_encode($result); die;

				}else if($authType == 1){
					//validate otp
					$accessCode = $signersData["signerData"]["otp"];
					$otpDateTime = $signersData["signerData"]["otpDateTime"];
					$currDtTm = date("Y-m-d H:i:s");

					$fromDt = $otpDateTime;
					$toDt = $currDtTm;
					
					$diff = dateDiffMinutes($fromDt, $toDt);
					
					if($diff < 15){

						if($userAccessCode == $accessCode){
							$accessToken = sha1(db_randomnumber());
							$sessArr = array(
								"docAccessToken" => $accessToken
							);
							
							$this->session->set($sessArr);
	
							$result = array(
								'C' => 100,
								'R' => array("accessToken" => $accessToken),
								'M' => 'success'
							);
	
						}else{
							//ivalid otp
							$result = array(
								'C' => 101,
								'R' => array('message'=>'invalid otp'),
								'M' => 'error'
							);
						}
					}else{
						//expired otp
						$result = array(
							'C' => 102,
							'R' => array('message'=>'otp expired'),
							'M' => 'error'
						);
					}
					
					echo json_encode($result); die;

				}else{
					//invalid auth type	
					$result = array(
						'C' => 103,
						'R' => array('message'=>'invalid authentication'),
						'M' => 'error'
					);

					echo json_encode($result); die;
				}
			}

		}

	}

	function sendDocAccessOtp($docId = false){
		
		$isPost = 0;
		
		if($this->request->isAJAX()){
			$docId = $this->request->getPost('documentId');
			$isPost = 1;
		}

		if($docId){
			$otp = genOtp();
			$emailData = $this->document_model->updateDocAccessOTP($docId, $otp);
			//echo "<pre>"; print_r($emailData); die;

			$signerName = $emailData["signerName"];
			$signerEmail = $emailData["signerEmail"];
			$docTitle = $emailData["customDocTitle"];

			$data = array();
			$data["signerName"] = $signerName;
			$data["otp"] = $otp;
			$data["docTitle"] = $docTitle;
			
			$template = view('emailtemplates/DocuSingOTP_Template', $data);
			$message = $template;

			$to = $signerEmail;
			$subject = "Your verification code for signing $docTitle";
			
			$email = \Config\Services::email();
			$email->setTo($to);
			$email->setFrom('johndoe@gmail.com', 'Confirm Registration');
			
			$email->setSubject($subject);
			$email->setMessage($message);
			if ($email->send()) 
			{
				//echo 'Email successfully sent';
				if($isPost > 0){
					$result = array('C' => 100, 'R' => array(), 'M' => 'success');
					echo json_encode($result); die;
				}else{
					return 1;
				}
				
			} 
			else 
			{
				//write log if email failed
				//$data = $email->printDebugger(['headers']);
				
				if($isPost > 0){
					$result = array('C' => 101, 'R' => array('message' => 'try again'), 'M' => 'error');
					echo json_encode($result); die;
				}else{
					return 0;
				}
				
			}
		}

	}


	function processsignstatic(){
		
		$myStr = "0Dinesh123Kumar456Giri789";

		$ciphertext = $this->Encription($myStr);
		$decrptdStr = $this->Decryption($ciphertext);

		echo $myStr."====".$ciphertext."====".$decrptdStr;

		die;

		$signerDocData = Array
		(
			"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff" => Array
				(
					Array
						(
							"elmType" =>"textbox",
							"page" => 1,
							"pageTop" => 117,
							"style" => "z-index: 102; top: 117px; left: 57px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);",
							"font_size" => "13px",
							"font_family" => "CourierPrime-Regular",
							"font_style" => "normal",
							"font_weight" => "normal",
							"text_decoration" => "none",
							"default_value" => "Text",
							"default_user" =>"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"
						),
		
					 Array
						(
							"elmType" => "textbox",
							"page" => 1,
							"pageTop" => 330,
							"style" => "z-index: 104; top: 330px; left: 786px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);",
							"font_size" => "13px",
							"font_family" => "CourierPrime-Regular",
							"font_style" => "normal",
							"font_weight" => "normal",
							"text_decoration" => "none",
							"default_value" => "Text 2",
							"default_user" => "Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"
						),
		
					Array
						(
							"elmType" => "textbox",
							"page" => 2,
							"pageTop" => 2,
							"style" => "z-index: 116; top: 1276px; left: 843px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);",
							"font_size" => "13px",
							"font_family" => "CourierPrime-Regular",
							"font_style" => "normal",
							"font_weight" => "normal",
							"text_decoration" => "none",
							"default_value" => "Text 4",
							"default_user" => "Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"
						),

					Array
						(
							"elmType" => "textbox",
							"page" => 2,
							"pageTop" => 4,
							"style" => "z-index: 114; top: 1278px; left: 3px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);",
							"font_size" => "13px",
							"font_family" => "CourierPrime-Regular",
							"font_style" => "normal",
							"font_weight" => "normal",
							"text_decoration" => "none",
							"default_value" => "Text 3",
							"default_user" => "Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"
						),
		
					Array
						(
							"elmType" => "textbox",
							"page" => 1,
							"pageTop" => 666,
							"style" => "z-index: 110; top: 666px; left: 540px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);",
							"font_size" => "13px",
							"font_family" => "CourierPrime-Regular",
							"font_style" => "normal",
							"font_weight" => "normal",
							"text_decoration" => "none",
							"default_value" => "Text",
							"default_user" => "Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"
						)	
		
				)
		
		);

		$documentId = "5183472c3a4573cd1d85b040bf3edcf0";
		//echo $documentId."<pre>"; print_r($signerDocData); die;
		
		$docData = array_values($signerDocData);
		$docData = $docData[0];
		$rootFolder = publicFolder();
		
		$srcFilePath = $rootFolder."userassets/mydocuments/1673874254153097/5183472c3a4573cd1d85b040bf3edcf0.pdf";	
		
		$this->pdf = new GeneratePdf($srcFilePath);

		$this->pdf->genpdf();	
		
		//$resultFile = $this->pdf->preparePdf($rootFolder, $srcFilePath, $docData);
		/*
		// initiate PDF
		//$pdf = new PDF();
		$pdf = $this->pdf;
		// go to first page
		$pdf->nextPage();
		

		// add content to current page
		$pdf->SetFont("helvetica", "", 13);
		$pdf->SetTextColor(220, 20, 60);
		$pdf->Text(0, 3.5, "head");
		$pdf->Text(13, 30, "I should not be here!");
		$pdf->Text(169.5, 30, "Text2");


		// move to next page and add content
		$pdf->nextPage();

		$pdf->SetFont("arial", "", 13);
		$pdf->SetTextColor(65, 105, 225);
		$pdf->Text(13, 30, "hello!");
		$pdf->Text(169.5, 30, "Text3");
		$pdf->Text(0, 3.5, "head");
		//show the PDF in page
		$pdf->Output();
		*/

		die;
		/*$destFilePath = $rootFolder."userassets/mydocuments/1673874254153097/dk.pdf";	
		fileWrite($destFilePath,$resultFile);*/
		echo "<pre>";
		print_r($resultFile);
		die;


	}

	function processsign(){
		
		//$userId = $this->session->get('loginId');
		//$userEmail = $this->session->get('loginEmail');
		//$userId = $this->request->getPost('signerId');
		
		$rootFolder = publicFolder();
		$signerName = $this->request->getPost('signerName');
		$userEmail = $this->request->getPost('signerEmail');
		$userDir = genshastring($userEmail);

		$signerDocData = $this->request->getPost('data');
		$documentId = $this->request->getPost('documentId');
		$signerDocumentId = $this->request->getPost('signerDocumentId');
		$masterDocument = $documentId.".pdf"; //"6f57ccc8e5b29a2e07824607d4df0ae4.pdf";
		$initialsBS64 = $this->request->getPost('initials');
		$signBS64 = $this->request->getPost('sign');
		$signType = $this->request->getPost('signType');
		
		$userLocale = $this->request->getPost('userLocale');
		$userLocale["email"] = $userEmail;
		$userLocaleJson = json_encode($userLocale);
		
		//get sender id by document
		$senderResult = $this->document_model->getSenderIdByDoc($documentId);
		$ownerId = $senderResult["senderId"];

		//create signatures png
		$secretFolder = "650d5885e051cbf1361781a0366dab198ce52007";
		$folderPath = FCPATH . "$secretFolder\\" . $signerDocumentId; 
	
		create_local_folder($folderPath);
		if($initialsBS64 != "" && $initialsBS64 != null){
			//write initials
			$foldedChadarCode = chadarmodbs64($initialsBS64);
			$file = $folderPath."\\initials.txt";
			fileWrite($file,$foldedChadarCode);
			//echo $file."<br>";
		}

		if($signBS64 != "" && $signBS64 != null){
			//write full signatures
			$foldedChadarCode = chadarmodbs64($signBS64);
			$file = $folderPath."\\sign.txt";
			fileWrite($file,$foldedChadarCode);
			//echo $file."<br>";
		}

		
		//write sign png
		$filePath = $folderPath."\\sign.txt";
		$fileContent = fileRead($filePath);
		if($fileContent != "" && $fileContent != null){
			$fileContentOk = chadarsidhibs64($fileContent);
			$fileContentOkParts = explode("base64,", $fileContentOk);
			//$fileContentOkParts[0];
			$bs64Cont = $fileContentOkParts[1];
			$bs64DecodedCont = base64_decode($bs64Cont);
			$file = $folderPath."\\sign.png";
			fileWrite($file,$bs64DecodedCont);

		}
		
		//write initials png
		$filePath = $folderPath."\\initials.txt";
		$fileContent = fileRead($filePath);
		if($fileContent != "" && $fileContent != null){
			$fileContentOk = chadarsidhibs64($fileContent);
			$fileContentOkParts = explode("base64,", $fileContentOk);
			//$fileContentOkParts[0];
			$bs64Cont = $fileContentOkParts[1];
			$bs64DecodedCont = base64_decode($bs64Cont);
			$file = $folderPath."\\initials.png";
			fileWrite($file,$bs64DecodedCont);

		}
		
		$downloadUrl = site_url($secretFolder."/".$signerDocumentId."/".$signerDocumentId.".pdf");	


		$docData = array_values($signerDocData);
		$docData = $docData[0];
		
		
		$docData = array_values($signerDocData);
		$docData = $docData[0];
		
		$srcFilePath = $rootFolder."userassets/mydocuments/$ownerId//".$masterDocument;	
		
		$this->pdf = new GeneratePdf();	
		
		$hashCode = $this->Encription($userLocaleJson);
		
		

		//update user filled data to signer document
		$updt = $this->document_model->updatePartyFilledData($signerDocumentId, json_encode($signerDocData));
		
		$resultFile = $this->pdf->preparePdf($rootFolder, $srcFilePath, $docData, $hashCode, $signerDocumentId, $secretFolder);
		
		//--- Now saving log for signed document
	
		//update document status	
		$status = 'signed';

		$updated = $this->document_model->updatePartySignStatus($documentId, $userEmail, $status);
		//$updated = 1;
		if($updated > 0){
			
			$docStatusUpdated = $this->document_model->updateSignerDocStatus($signerDocumentId, $status);
			if($docStatusUpdated > 0){
				
				$documentInfo = $this->document_model->getDocumentByUser($userEmail, $signerDocumentId);	

				$insertData = array();
				$insertData["id"] = db_randomnumber();
				$insertData["signatureHash"] = $hashCode;
				$insertData["documentId"] = $documentInfo["id"];
				$insertData["parentDocumentId"] = $documentInfo["parentDocument"];
				$insertData["signerEmail"] = $documentInfo["signerEmail"];
				$insertData["signerId"] = $documentInfo["signerId"];
				$insertData["senderId"] = $documentInfo["senderId"];
				$insertData["documentStatus"] = 1; //signed
				$insertData["signType"] = $signType;
				
				$insertRowId = $this->document_model->saveDocumentESignHash($insertData);
				
				$result = array("C" => 100, "R" => array("downloadurl" => $downloadUrl), "M" => "success");
			
			}else{
				$result = array("C" => 102, "R" => "it seems something went wrong with signer's document status", "M" => "error");	
			}
			
		}else{
			$result = array("C" => 101, "R" => "it seems something went wrong", "M" => "error");
		}
		
		
		echo json_encode($result); die;
		
	}

	function processsignd(){
		/*
		$html2pdf = new Html2Pdf();
		$html2pdf->writeHTML('<h1>HelloWorld</h1>This is my first test<canvas id="cnv123"></canvas>');
		$html2pdf->output();
		*/

		//require("vendor/autoload.php");

		//echo "<pre>"; print_r($_SERVER);
		//$rootFolder = $_SERVER["DOCUMENT_ROOT"]."/digitalsignature";
		$rootFolder = publicFolder();
		
		$srcFilePath = $rootFolder."userassets/mydocuments/1673874254153097/5c960af14d51ddfe1c288a80cf305c98.pdf";	
		//echo file_get_contents($srcFilePath);
		
		//Library Object
		$this->pdf = new Fpdi();

		
		$numPages = $this->pdf->setSourceFile($srcFilePath);
        $fileIndex = $this->pdf->importPage(1);
		$this->pdf->AddPage();
		$this->pdf->useTemplate($fileIndex, 0, 0,200);
		// add content to current page
		$this->pdf->SetFont("helvetica", "", 20);
		$this->pdf->SetTextColor(220, 20, 60);
		$this->pdf->Text(50, 20, "I should not be here!");
		$this->pdf->Text(80, 40, '<img src="">');
		$this->pdf->Image( $rootFolder."userassets/mydocuments/1673874254153097/logo.png", 100, 60, 50, 50);

		// move to next page and add content
		$this->pdf->useTemplate($this->pdf->importPage(2), 0, 0,200);

		$this->pdf->SetFont("arial", "", 15);
		$this->pdf->SetTextColor(65, 105, 225);
		$this->pdf->Text(50, 20, "Me neither!!!");

		//show the PDF in page
		$this->pdf->Output();



		die;
	}

	function writesigndata(){
		
		$signData = $this->request->getPost('data');
		$signerDocumentId = $this->request->getPost('signerDocumentId');
		
		$elmId = $signData["elemId"];
		$bs64 = $signData["bs64"];
	}

    public function Encription($myStr)
    {
		$config         = new Encryption();
        $config->driver = 'OpenSSL';
        
        $config->key            = hex2bin('64c70b0b8d45b80b9eba60b8b3c8a34d0193223d20fea46f8644b848bf7ce67f');
        $config->rawData        = false;
        $config->encryptKeyInfo = 'encryption';
        $config->authKeyInfo    = 'authentication';
		$sck = "5707aeddca930850f94ee09cb46c19a4e85f8b0e";
        $encrypter = Services::encrypter($config, false);
        $ciphertext = $encrypter->encrypt($myStr, array("key" => $sck, "blockSize" => 32));

		return $ciphertext;
		
    }
	
    public function Decryption($ciphertext)
    {
		$config         = new Encryption();
        $config->driver = 'OpenSSL';
        
        $config->key            = hex2bin('64c70b0b8d45b80b9eba60b8b3c8a34d0193223d20fea46f8644b848bf7ce67f');
        $config->rawData        = false;
        $config->encryptKeyInfo = 'encryption';
        $config->authKeyInfo    = 'authentication';
		$sck = "5707aeddca930850f94ee09cb46c19a4e85f8b0e";
        $encrypter = Services::encrypter($config, false);
        
		$dcrptdTxt = $encrypter->decrypt($ciphertext, array("key" => $sck, "blockSize" => 32));
		return $dcrptdTxt;
    }

	function sendMail($data) { 

        $to = $data["to"];
        $subject = $data["subject"]; //Signature Request: You need to review and sign {Document title}
        $message = $data["message"]; //Hi Rashika, Dinesh Giri (upkit.dineshgiri@gmail.com) has requested you to review and sign the document {Document title}
		$link = $data["link"];
        
		$message = $message ."<br>". $link;



        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setFrom('johndoe@gmail.com', 'Confirm Registration');
        
        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) 
		{
            //echo 'Email successfully sent';
			//update date time in db when email is sent
        } 
		else 
		{
			//write log if email failed
            /*
			$data = $email->printDebugger(['headers']);
            print_r($data);
			*/
        }
    }

	function dashboard(){

		$userId = $this->session->get('loginId');
		$userEmail = $this->session->get('loginEmail');
		
		$offset = 0;	

		$result = $this->document_model->getMyDocuments($userId,$offset);
		
		$data = array();
		$data["page_tilte"] = "Dashboard";
		$data["documents"] = $result;
		return view('admin/dashboard', $data);	
	}
	
	function signeddocument($documentId){
		
		$userId = $this->session->get('loginId');
		$result = $this->document_model->getSignedDocument($documentId, $userId);

		//echo "result:<pre>"; print_r($result);

		$data = array();
		$data["page_tilte"] = "Overview";
		$data["document"] = $result;
		return view('admin/documentOverview', $data);		

	}

}
?>