<?php

namespace App\Controllers;
use App\Models\Document_Model; //load model
use App\Libraries\GeneratePdf; //import library


use Config\Encryption;
use Config\Services;
use Config\Esign;
use DateTime;
use DateTimeZone;

class Document extends BaseController
{
	public $request = null;
	public $session = null;
	public $admin_model = null;
	public $document_model = null;
	public $pdf = null;
	public $router = null;
	public $esign_config = null;
	
	function __construct(){
		//session object
		$this->session = \Config\Services::session();
		$this->session->start();
	
		//config object
		$this->esign_config = new \Config\Esign();

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
			
			if(strtolower($controllerName) == "document" && (strtolower($methodName) != "sign" && strtolower($methodName) != "processsign")){
				//skip session for signing doc
				//TEMPORARY COMMENTED DUE TO DEVELOPMENT
				//customredirect("signin");
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
		/*
		$cont = file_get_contents($tmpFileName);
		$fp = fopen($file_path, "w+");
		fwrite($fp, $cont);
		fclose($fp);
		echo $file_path;
		die;*/
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


	
	function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
		$output = NULL;
		if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
			$ip = $_SERVER["REMOTE_ADDR"];
			if ($deep_detect) {
				if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
					$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
		}
		$purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
		$support    = array("country", "countrycode", "state", "region", "city", "location", "address");
		$continents = array(
			"AF" => "Africa",
			"AN" => "Antarctica",
			"AS" => "Asia",
			"EU" => "Europe",
			"OC" => "Australia (Oceania)",
			"NA" => "North America",
			"SA" => "South America"
		);
		if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
			$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
			if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
				switch ($purpose) {
					case "location":
						$output = array(
							"city"           => @$ipdat->geoplugin_city,
							"state"          => @$ipdat->geoplugin_regionName,
							"country"        => @$ipdat->geoplugin_countryName,
							"country_code"   => @$ipdat->geoplugin_countryCode,
							"continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
							"continent_code" => @$ipdat->geoplugin_continentCode
						);
						break;
					case "address":
						$address = array($ipdat->geoplugin_countryName);
						if (@strlen($ipdat->geoplugin_regionName) >= 1)
							$address[] = $ipdat->geoplugin_regionName;
						if (@strlen($ipdat->geoplugin_city) >= 1)
							$address[] = $ipdat->geoplugin_city;
						$output = implode(", ", array_reverse($address));
						break;
					case "city":
						$output = @$ipdat->geoplugin_city;
						break;
					case "state":
						$output = @$ipdat->geoplugin_regionName;
						break;
					case "region":
						$output = @$ipdat->geoplugin_regionName;
						break;
					case "country":
						$output = @$ipdat->geoplugin_countryName;
						break;
					case "countrycode":
						$output = @$ipdat->geoplugin_countryCode;
						break;
				}
			}
		}
		return $output;
	}


	function test(){
				/*
		$arr = $this->ip_info("122.161.198.145");
		$countryCode = $arr["country_code"];
		$timezoneArr = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $countryCode);
		$timezone = $timezoneArr[0];
		$crrDt = date("Y-m-d H:i:s");
		//$date = new DateTime($crrDt, new DateTimeZone($timezone));
		$date = new DateTime($crrDt);
		//print_r($date->getTimezone());
		$date->setTimezone(new DateTimeZone($timezone));
		//$userCrrDtTm = $date->format('Y-m-d H:i:sP'); //with GMT
		$userCrrDtTm = date("Y-m-d h:i:s a", strtotime($date->format('Y-m-d H:i:sP')));
		echo $crrDt."-------".$userCrrDtTm; die;
		
		$documentId = "3230ca8473e2d077ec9579170727cfee";
		$senderResult = $this->document_model->getSenderIdByDoc($documentId);
		echo "Arr:<pre>";
		print_r($senderResult);
		
		$docId = "1332f5043a37f6218c8f4c18536c34ff";
		$signersData = $this->document_model->getSignerDocumentRawData($docId);

		print_r($signersData);

		die;
		*/
		$rootFolder = publicFolder();
		$secretFolder = $this->esign_config->SECRETFOLDER; 
		$srcFilePath = $rootFolder."systemtemplates/CertificateOfCompletion.pdf";
		//$srcFilePath = $rootFolder."systemtemplates/1681966643464579.pdf";
		//$srcFilePath = $rootFolder."systemtemplates/uncompressed.pdf";
		
		
		//echo("pdftk $srcFilePath output $newFilePath uncompress > dev/nul &");
		//exec("pdftk $srcFilePath output $newFilePath uncompress > dev/nul &", $out);
		
		$signerDocumentId = "c84b3c8d1a55dfd4fc89092de4d8d273";
		$data = array();
		$data["documentId"] = "37a3235a19a98fd3e4bf7b0bcbe74669";
		$data["documentName"] = "Certificate of completion";
		$data["sentAt"] = "Apr 03, 2023 09:26:12 UTC";
		$data["title"] = "Sample Aggrement";
		$data["signType"] = "Selef Signed";
		$data["status"] = "signed";
		$data["signerCount"] = 1;
		$data["hash"] = "e0a306a4ee830b4e4b579c9530f50a045bf129f9a6223526803b489e4a77ed2bd5675c03b624530aecad4fcf696d5dac19d666e0437bd4d0e596b4c5d34ab328bkc2BcW9iJquBUm08X1mppdLG14uVQcAQyfI/m6uztK1I+APrCmbGL/tzAIQZaJWRDVJJZ4becQKV3ta0YkCfzF7DLjaS1vTcemjAQSZPpqRIanh9K4MGTdJBy10JJ0AG+yOTjjux7Kl0IEZy7SPmXa0Xn7O2L6hJorWdRFJZHwzUC6VS3xJn7dL7bQMYQf/qOpKoLoeforRuKtjKEeZbRmki3PbfWe/IB/Axu3wC9H3AQApb7f0UouJwhhsimYBvAni/fHX1fuNarJ0";
		$data["recipientName"] = "Rashika Sapru";
		$data["signatureType"] = "Type";
		$data["timeStamp"] = "Apr 03, 2023 09:26:12 UTC";
		$data["recipientEmail"] = "upkit.rashikasapru@gmail.com";
		$data["signatureAuth"] = "OTP";
		$data["signaturePng"] = $rootFolder."$secretFolder/c84b3c8d1a55dfd4fc89092de4d8d273/sign.png";
		$data["signedAt"] = "Apr 03, 2023 09:26:12 UTC";
		$data["signedByName"] = "Rashika Sapru signed the document.";
		$data["signedByEmail"] = "upkit.rashikasapru@gmail.com";
		$data["completedAt"] = "Apr 03, 2023 09:26:12 UTC";
		$this->pdf = new GeneratePdf();
		$this->pdf->prepareCompletionCertificate($rootFolder, $srcFilePath, $data, $signerDocumentId, $secretFolder);
		

		die;

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
						$viewedDtTm = date("Y-m-d H:i:s");
						$this->document_model->updateSignerDocStatus($docId, "viewed", $viewedDtTm);
						
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

					//update status to viewed from sent
					$viewedDtTm = date("Y-m-d H:i:s");
					$this->document_model->updateSignerDocStatus($docId, "viewed", $viewedDtTm);

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
		
		//echo "<pre>"; print_r($this->request); die;

		$rootFolder = publicFolder();
		$signerName = $this->request->getPost('signerName');
		$userEmail = $this->request->getPost('signerEmail');
		$userDir = genshastring($userEmail);
		
		$signerDocData = $this->request->getPost('data');
		$documentId = $this->request->getPost('documentId');
		$signerDocumentId = $this->request->getPost('signerDocumentId');
		$masterDocument = $documentId.".pdf";
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
		$secretFolder = $this->esign_config->SECRETFOLDER;
		
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
		
		$downloadUrl = base_url($secretFolder."/".$signerDocumentId."/".$signerDocumentId.".pdf");	

		$docData = array_values($signerDocData);
		$docData = $docData[0];
		
		
		$docData = array_values($signerDocData);
		$docData = $docData[0];
		
		$srcFilePath = $rootFolder."userassets/mydocuments/$ownerId/".$masterDocument;	
		
		$this->pdf = new GeneratePdf();	
		
		$hashCode = $this->Encription($userLocaleJson);
		
		//update user filled data to signer document
		$updt = $this->document_model->updatePartyFilledData($signerDocumentId, json_encode($signerDocData));
		
		$resultFile = $this->pdf->preparePdf($rootFolder, $srcFilePath, $docData, $hashCode, $signerDocumentId, $secretFolder);
		
		
		//--- Now saving log for signed document
	
		//update document status	
		$status = 'signed';
		$signedDtTm = date("Y-m-d H:i:s");
		
		$updated = $this->document_model->updatePartySignStatus($documentId, $userEmail, $status);
		//$updated = 1;
		
		if($updated > 0){
			
			$certfctResult = $this->document_model->getDocDataForCompletionCertificate($signerDocumentId);
			
			if(!empty($certfctResult)){

				$docAuthType = $certfctResult["authType"];
				if($docAuthType == 1){
					$docAuthTypeTxt = "OTP";
				}else if($docAuthType == 1){
					$docAuthTypeTxt = "Access Code";	
				}else{
					$docAuthTypeTxt = "-";
				}
				
				
				$srcCertificateFilePath = $rootFolder."systemtemplates/CertificateOfCompletion.pdf";
				$signFilePath = $rootFolder."$secretFolder/$signerDocumentId/sign.png";
				
				$data = array();
				$data["documentId"] = $certfctResult["documentId"];
				$data["documentName"] = $certfctResult["fileName"];
				$data["sentAt"] = $certfctResult["documentSentDate"]; //"Apr 03, 2023 09:26:12 UTC"
				$data["title"] = ucwords($certfctResult["documentTitle"]);
				$data["signType"] = "Self Signature";
				$data["status"] = ucfirst($certfctResult["document_status"]);
				$data["signerCount"] = 1;
				$data["hash"] = $hashCode;
				$data["recipientName"] = $certfctResult["signerName"];
				$data["signatureType"] = ucfirst("$signType"); //"Type";
				$data["timeStamp"] = $signedDtTm; //"Apr 03, 2023 09:26:12 UTC";
				$data["recipientEmail"] = $certfctResult["signerEmail"];
				$data["signatureAuth"] = $docAuthTypeTxt; //"OTP";
				$data["signaturePng"] = $signFilePath; //$rootFolder."systemtemplates/sign.png";
				$data["signedAt"] = $signedDtTm; //"Apr 03, 2023 09:26:12 UTC";
				$data["signedByName"] = $certfctResult["signerName"];
				$data["signedByEmail"] = $certfctResult["signerEmail"];
				$data["completedAt"] = $signedDtTm; //"Apr 03, 2023 09:26:12 UTC";
				
				$this->pdf = new GeneratePdf();
				$this->pdf->prepareCompletionCertificate($rootFolder, $srcCertificateFilePath, $data, $signerDocumentId, $secretFolder);
				
			}
			
			$docStatusUpdated = $this->document_model->updateSignerDocStatus($signerDocumentId, $status, $signedDtTm);

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
				
				//send completed document to signer
				
				$homePath = FCPATH."index.php";
				//exec("php $homePath emailengine sendCompletedDocumentToSigner $signerDocumentId > /dev/null &", $out);
				exec("php $homePath emailengine sendCompletedDocumentToSigner $signerDocumentId", $out);
				//echo "out:<pre>"; print_r($out); die;

				$result = array("C" => 100, "R" => array("downloadurl" => $downloadUrl), "M" => "success");
			
			}else{
				$result = array("C" => 102, "R" => "it seems something went wrong with signer's document status", "M" => "error");	
			}
			
		}else{
			$result = array("C" => 101, "R" => "it seems something went wrong", "M" => "error");
		}
		
		
		echo json_encode($result); die;
		
	}

	function prepareConsolidatePdfs($documentID=false){
		//function is ok for creating and sending the complete document

		$rootFolder = publicFolder();
		$secretFolder = $this->esign_config->SECRETFOLDER;
	
		$bgImg = $rootFolder."systemtemplates/kapda.jpeg";
		
		$documentID = "085dc7ff38220e7bcbc07cf6999cb729";
		
		$result = $this->document_model->getDocumentSignersData($documentID);
		//echo "<pre>"; print_r($result); die;
		if(!empty($result)){
			$signerDocumentIds = array();
			
			$parentDocument = $result["parentDocument"];
			$signerDocuments = $result["signerDocuments"];
			$uploadedFile = $result["uploadedFile"];
			$userId = $parentDocument["senderId"];
			
			$srcFilePath = $parentDocument["documentPath"];
			$pdfSourceData = array();
			foreach($signerDocuments as $signerDocRw)	{
				//echo "<pre>"; print_r($signerDocRw); die;
				$tmpSignrDocId = $signerDocRw["documentId"];
				
				$tmpSignrDocStatus = $signerDocRw["document_status"];
				
				if(strtolower($tmpSignrDocStatus) == "signed"){

					$tmpSignerDocData = json_decode($signerDocRw["userfilled_documentdata"], true);
					
					foreach($tmpSignerDocData as &$tmpDcRws){
						$signerDocumentIds[] = $tmpSignrDocId;	
						foreach($tmpDcRws as &$tmpDcRw){
							$tmpDcRw["documentId"] = $tmpSignrDocId;
							
						}
						
					}

					foreach($tmpSignerDocData as $tmpDcRws){
						foreach($tmpDcRws as $tmpDcRw){
							$pdfSourceData[] = 	$tmpDcRw;
						}
						
					}
				}
				
			}
			
			if(!empty($pdfSourceData)){
				//Consolidate document
				$rootFolder = publicFolder();
				$secretFolder = $this->esign_config->SECRETFOLDER;
				$srcFilePath = $rootFolder.$srcFilePath;
				$this->pdf = new GeneratePdf();	
				$resultFile = $this->pdf->prepareConsolidatePdf($rootFolder, $srcFilePath, $pdfSourceData, $secretFolder, $userId, $documentID);
			
			
				//Consolidate Audit Trial
				$auditResult = $this->document_model->getSignersAuditTrialData($documentID);
				if(!empty($auditResult)){
					$auditData = array();
					$auditData["recipientsData"] = array();
					$auditData["auditTrialData"] = array();
					$auditData["documentData"] = array();

					$tmpDocIdsArr = array();
					$tmpMainDocFileName = $auditResult["uploadInfo"]["file_name"];
					$tmpMainDocTitle = $auditResult["uploadInfo"]["documentTitle"];
					$tmpMainDocumentId = $auditResult["documentId"];
					$tmpNoOfParties = $auditResult["no_of_parties"];
					$isComplete = $auditResult["isComplete"];
					$createdAt = $auditResult["created_at"];
					$comletedAt = $auditResult["updated_at"];
	
					foreach($auditResult["signerDocuments"] as $signerDocumentRw){
						
						$tmpDocumentId = $signerDocumentRw["documentId"];
						$tmpSignImg = $rootFolder. $secretFolder."/".$tmpDocumentId."/sign.png";
						$tmpName = $signerDocumentRw["signerName"];
						$tmpEmail = $signerDocumentRw["signerEmail"];
						$tmpDateTime = $signerDocumentRw["documentSignDate"];
						$tmpAuthType = $signerDocumentRw["authType"];
						if($tmpAuthType == 1){
							$tmpAuthTypeTxt = "OTP";
						}else if($tmpAuthType == 2){
							$tmpAuthTypeTxt = "Access Code";
						}else{
							$tmpAuthTypeTxt = "-";
						}
						
						$tmpSignType = $signerDocumentRw["hashData"]["signType"];
						$tmpHsh = $signerDocumentRw["hashData"]["signatureHash"];
						$tmpHshJson = $this->Decryption($tmpHsh);
						$tmpHshObj = json_decode($tmpHshJson);
						$tmpIP = $tmpHshObj->ip;
	
						$tmpDocIdsArr[] = $tmpDocumentId;
	
						$auditData["recipientsData"][] = array(
							"Name" => $tmpName,
							"Email ID" => $tmpEmail,
							"Signature Type" => ucfirst($tmpSignType),
							"Security Authentication" => $tmpAuthTypeTxt,
							"Timestamps" => $tmpDateTime,
							"Signature" => $tmpSignImg
						);
						
	
						$auditData["auditTrialData"][] = array(
							"Section" => "Signed",
							"Name" => $tmpName,
							"Email ID" => $tmpEmail,
							"DateTime" => $tmpDateTime,
							"IP" => $tmpIP
						);
					}
					
					$auditData["auditTrialData"][] = array(
						"Section" => "Completed",
						"Name" => "",
						"Email ID" => "",
						"DateTime" => $comletedAt,
						"IP" => ""
					);
	
	
					$tmpMainHash =  $this->Encription(json_encode($tmpDocIdsArr));
					$auditData["documentData"] = array(
						"documentId" => $tmpMainDocumentId,
						"documentName" => $tmpMainDocFileName, 
						"numPages" => "",
						"sentAt" => $createdAt,
						"title" => $tmpMainDocTitle,
						"signType" => "Signature Collection",
						"status" => "Completed",
						"signerCount" => $tmpNoOfParties,
						"completedAt" => $comletedAt,
						"hash" => $tmpMainHash 
					);
					
				}
	
				//echo "<pre>"; print_r($auditData); die;
				$srcFilePath = "systemtemplates/ConsolidateCertificateOfCompletion.pdf";
				$srcFilePath = $rootFolder.$srcFilePath;
				$this->pdf = new GeneratePdf();	
				$this->pdf->prepareConsolidateCompletionCertificate($rootFolder, $srcFilePath, $bgImg, $auditData, $userId);
			
			}
			
		}
		
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