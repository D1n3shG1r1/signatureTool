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
	function __construct(){
		
		$this->session = \Config\Services::session();
		$this->session->start();
		
		//Request Object
		$this->request = \Config\Services::request();
		
		//Model Object
		$this->document_model = new Document_Model();
		
		helper("kitchen");

		helper("EncryDcry");

		
	}

	function upload(){
		$data = array();
		$data["page_tilte"] = "Upload";
		return view('admin/upload', $data);	
	}

	function fileupload(){

		$loginId = $this->session->get('loginId');
		$loginId = "1673874254153097";

		//echo "loginId:" . $loginId . ",FILES:<pre>";
		//print_r($_FILES);
		
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
			'user_id'	=> $loginId
		);

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

    function prepare($fileId){

		// $uploadedFile = $this->e_sign_upload->GetData($id);

        // echo "<pre>"; print_r($uploadedFile);
		// die;
		$fileId = "1674218633467744";
		$loginId = "1673874254153097";
		$documentPath = "/userassets/uploads/" . $loginId;
	
		$fileExt = ".pdf";
		$newFileName = $fileId.$fileExt;
		$file_path = $documentPath."/".$newFileName;

		$data = array();
		$data["page_tilte"] = "Document Prepare";
		$data["document"] = $file_path;
		$data["documentId"] = $fileId;
		return view('admin/documentprepare', $data);
		//return view('admin/header');
		
		//return view('welcome_message');
    }
	
	function saveandsenddocument(){
		$docId = $this->request->getPost('documentId'); //file id
		$docdata = $this->request->getPost('data');
		$fileId = $docId;
		$fileId = "1674218633467744";
		$loginId = "1673874254153097";
		$documentPath = FCPATH."/userassets/uploads/" . $loginId;
		$destFolderPath = FCPATH."/userassets/mydocuments/" . $loginId;
		//echo "<pre>"; print_r($docdata); die;
		//get loggedIn user email
		//$uploadId = $this->document_model->InsertToDB($data);
		$user = $this->document_model->getUserById($loginId);
		$loginEmail = $user["email"];

		$accessCodeMedia = "email";
		$documentData = array();
		$signersData = array();
		
		//document data
		$parentDocumentId = db_randomnumber(); //raw / source document Id primary key	
		$documentId = random_unique_string();  // unique alphanumeric has string
		/*		
		TEMPORARY STOPED
		//move the uploaded file to Mydocuments and remove from uploads folder and uploades table		
		create_local_folder($destFolderPath);
	
		$fileExt = ".pdf";
		$fileName = $fileId.$fileExt;
		$file_path = $documentPath."/".$fileName;

		$newFileName = $documentId . $fileExt;
		$destFile = $destFolderPath."/".$newFileName;
		$src = $file_path; //source file
		$dst = $destFile; //destination file
		moveFileOneDirToAnother($src, $dst);
		*/
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
		

		$partyAssets = array();
		$documentStatus = array();
		foreach($docdata as $asstsk => $asstsv){

			$asstskArr = explode("#DK#", $asstsk);
			$tmpName = $asstskArr[0];
			$tmpEmail = $asstskArr[1];
			$tmpTag = $asstskArr[2];
			$tmpClr = $asstskArr[3];

			$tmpArr = array();
			$tmpArr["dataFeilds"] = $asstsv;
			$tmpArr["email"] = $tmpEmail;
			$tmpArr["name"] = $tmpName;
			$tmpArr["accesscode"] = ""; 
			//Email, Name, Access code, data-feilds
		
			$partyAssets[] = $tmpArr;
			
			$documentStatus[] = array("email" => $tmpEmail, "status" => "pending");
		}

		$partyAssetsJson = json_encode($partyAssets);
		$documentStatusJson = json_encode($documentStatus);


		$documentSrcPath = "/userassets/mydocuments/" . $loginId."/".$destFile;
		$senderId = $loginId;
		$recieverIds = implode(",", $recieverIdsArr);
		$noOfParties = count($docdata);
		$documentData["id"] = $parentDocumentId;
		$documentData["documentId"] = $documentId;
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
			
			$accessCode = 123456; //$tmpUserDocData["accessCode"];
			
			
			$crrDate = date("Y-m-d H:i:s");
			$documentExpiry = date("Y-m-d H:i:s",  strtotime($crrDate." + 10 days"));
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
			$singleSignerData["accessCode"] = $accessCode;
			$singleSignerData["accessCodeMedia"] = $accessCodeMedia;
			$singleSignerData["documentExpiry"] = $documentExpiry;
			$singleSignerData["documentExpired"] = 0; //0-not-expired, 1-expired
			$singleSignerData["lastReminder"] = $lastReminder;
			$singleSignerData["documentSentDate"] = $documentSentDate;
		
			$signersData[] = $singleSignerData;
		
		}
		
		//--- save file data to db
		//echo "<pre>documentdata:"; print_r($documentData);
		//echo "signersData:"; print_r($signersData); 
		$docResponse = $this->document_model->saveDocumentData($documentData);
		
		$signersDataResponse = $this->document_model->saveSignersDocumentData($signersData);
		
		//echo "docResponse:" . $docResponse;
		//echo "signersDataResponse:" . $signersDataResponse;
		
		if($docResponse > 0 && $signersDataResponse > 0){
			//generate document link and share via email
			$documentId = $signersData[0]["documentId"]; //link for first signer
			$link = site_url("sign/?documentId=$documentId");	
			echo $link;


			$result = array("C" => 100, "R" => array(), "M" => "success");

		}else{
			// something went wrong try again
			$result = array("C" => 101, "R" => array(), "M" => "error");
		}
		
		die;
		//echo json_encode($result); die;

	}

	function sign(){
		//http://localhost/digitalsignature/sign/?documentId=ccca43b66c9b41b249c46d2ba96612a4
		//if($request->is('get')){
		//if($request->isGet()){
			$docId = $this->request->getGet('documentId'); //file id
			$loginId = "1673874254153097";

			//get document ready and all its elements	
			$signersData = $this->document_model->getSignerDocumentRawData($docId);
			if(!empty($signersData)){
					
				//echo "<pre>"; print_r($signersData); die;
		
				$data = array();
				$data["page_tilte"] = "Document Sign";
				$data["document"] = $signersData["parentDoc"]["documentPath"];
				$data["documentId"] = $signersData["parentDoc"]["documentId"];
				$data["signersData"] = $signersData["signerData"];

				//echo "<pre>"; print_r($data); die;

				/*
					//https://www.zoho.com/creator/newhelp/app-settings/understand-download-mobile-app.html
				[parentDoc] => Array
					(
						[documentId] => 5c960af14d51ddfe1c288a80cf305c98
						[documentPath] => /userassets/mydocuments/1673874254153097/5c960af14d51ddfe1c288a80cf305c98.pdf
					)

				[signerData] => Array
					(
						[id] => 1674558124690129
						[parentDocument] => 167455812489382
						[documentId] => e4fa0acd8b66ff416544a7d52057a08c
						[signerEmail] => upkit.dineshgiri@gmail.com
						[signerName] => Dinesh Kumar
						[signerId] => 1673874254153097
						[internalUser] => 1
						[document_status] => 
						[document_data] => [{"elmType":"signature","style":"z-index: 102; top: 81px; left: 181px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);","font_size":"13px","font_family":"CourierPrime-Regular","font_style":"normal","font_weight":"normal","text_decoration":"none","default_value":"Signature of Dinesh Kumar","default_user":"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"},{"elmType":"signaturein","style":"z-index: 102; top: 79px; left: 378px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);","font_size":"13px","font_family":"CourierPrime-Regular","font_style":"normal","font_weight":"normal","text_decoration":"none","default_value":"DK","default_user":"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"},{"elmType":"textbox","style":"z-index: 102; top: 73px; left: 501px; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(0, 123, 255); border-image: initial; background-color: rgba(0, 123, 255, 0.5);","font_size":"13px","font_family":"CourierPrime-Regular","font_style":"normal","font_weight":"normal","text_decoration":"none","default_value":"Text","default_user":"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##007bff"}]
						[accessCode] => 123456
						[accessCodeMedia] => email
						[documentExpiry] => 2023-02-03 11:02:04
						[documentExpired] => 0
						[lastReminder] => 2023-01-24 11:02:04
						[documentSentDate] => 2023-01-24 11:02:04
						[created_at] => 2023-01-24 16:32:04
						[updated_at] => 2023-01-24 16:32:04
					)
	
				*/
				
				return view('admin/documentsign', $data);
				
			}else{

				//invalid link
				die("It seems that the link has expiered!");

			}
			
		//}else{
		//	die("Invalid URL");
		//}
		
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
		$userId = "1673874254153097";
		$signerDocData = $this->request->getPost('data');
		$documentId = $this->request->getPost('documentId');
		$signerDocumentId = $this->request->getPost('signerDocumentId');
		$masterDocument = "6f57ccc8e5b29a2e07824607d4df0ae4.pdf";
		$initialsBS64 = $this->request->getPost('initials');
		$signBS64 = $this->request->getPost('sign');
		echo $documentId."<pre>";
		print_r($signerDocData);
		
		//echo "initialsBS64:".$initialsBS64.",signBS64:".$signBS64;
		die;
	
		//create signatures png
		//db_randomnumber();
		
		$folderPath = FCPATH . "650d5885e051cbf1361781a0366dab198ce52007\\" . $signerDocumentId; 
	
		create_local_folder($folderPath);
		if($initialsBS64 != "" && $initialsBS64 != null){
			//write initials
			$foldedChadarCode = chadarmodbs64($initialsBS64);
			$file = $folderPath."\\initials.txt";
			fileWrite($file,$foldedChadarCode);
		}

		if($signBS64 != "" && $signBS64 != null){
			//write full signatures
			$foldedChadarCode = chadarmodbs64($signBS64);
			$file = $folderPath."\\sign.txt";
			fileWrite($file,$foldedChadarCode);
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
		
		$fullPathToFile = $rootFolder."userassets/mydocuments/$userId//".$masterDocument;	
		$docData = array_values($signerDocData);
		$docData = $docData[0];
		
		
		
		$docData = array_values($signerDocData);
		$docData = $docData[0];
		$rootFolder = publicFolder();
		
		
		$srcFilePath = $rootFolder."userassets/mydocuments/$userId//".$masterDocument;	
		
		$this->pdf = new GeneratePdf();	
		$myStr = "";
		//$ciphertext = $this->Encription();
		//die;
		//$decrptdStr = $this->Decryption($ciphertext);

		$resultFile = $this->pdf->preparePdf($rootFolder, $srcFilePath, $docData);
		
		
		
		die;
		include("testpdf.php");

/*		
		$cmd = 'curl -d '{"e-mail":"rafia@gmail.com", "password":"password123"}' -H "Content-Type: multipart/form-data" -X POST https://reqbin.com/echo/post/json -o test.html';
		exec($cmd, $output);
		echo "out:".$output;
*/

		die;
		$docData = array_values($signerDocData);
		$docData = $docData[0];
		$rootFolder = publicFolder();
		
		//$srcFilePath = $rootFolder."userassets/mydocuments/1673874254153097/5c960af14d51ddfe1c288a80cf305c98.pdf";	
		$srcFilePath = $rootFolder."userassets/mydocuments/1673874254153097//".$masterDocument;	
		
		$this->pdf = new GeneratePdf();	
		
		$resultFile = $this->pdf->preparePdf($rootFolder, $srcFilePath, $docData);
		/*$destFilePath = $rootFolder."userassets/mydocuments/1673874254153097/dk.pdf";	
		fileWrite($destFilePath,$resultFile);*/
		echo "<pre>";
		print_r($resultFile);
		die;

		$rootFolder = publicFolder();
		
		$srcFilePath = $rootFolder."userassets/mydocuments/1673874254153097/5c960af14d51ddfe1c288a80cf305c98.pdf";	
		
		$this->pdf = new GeneratePdf();	
		$data = array("hello! how are you?", "New text is added.");
		$this->pdf->preparePdf($srcFilePath, $data);

		/*
		
		//Library Object
		//$this->pdf = new Fpdi();
		$this->pdf = new GeneratePdf($srcFilePath);

		
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

		*/

		die;
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

}
?>