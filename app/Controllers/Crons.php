<?php
//set all the exec with > dev/nul
namespace App\Controllers;
use App\Models\Cron_Model; //load model

class Crons extends BaseController
{
    public $esign_config = null;
    public $cron_model = null;

    function __construct(){
        //config object
		$this->esign_config = new \Config\Esign();

        //Model Object
		$this->cron_model = new Cron_Model();

    }

    function getExpireDocuments(){
        
        //Ok report needs to be call with cron 4-6times in a day
        //Notify document owner for document expiry
        $homePath = FCPATH."index.php";
        $rootFolder = publicFolder();
        $CRONASSETSDIR = $this->esign_config->CRONASSETSDIR;
        //$secretFolder = $this->esign_config->SECRETFOLDER;
        //$srcFilePath = $rootFolder.$srcFilePath;

        $expiredDocuments = $this->cron_model->getExpiredDocuments();
        
        if(!empty($expiredDocuments)){
            foreach($expiredDocuments as $expiredDocRw){
                
                $expiredDocTxt = json_encode($expiredDocRw);
                $tmpFileUploadId = $expiredDocRw["id"];
                
                $tmpDirPath = $rootFolder.$CRONASSETSDIR."/$tmpFileUploadId/";
                create_local_folder($tmpDirPath);
                $tmpFilePath = $tmpDirPath.$tmpFileUploadId.".txt";
                fileWrite($tmpFilePath, $expiredDocTxt);

                //fire exec to send email
                //exec("php $homePath emailengine sendDocuExpiredOwner $tmpFileUploadId > /dev/null &", $out);
				exec("php $homePath emailengine sendDocuExpiredOwner $tmpFileUploadId", $out);
                //echo "<pre>"; print_r($out);
            }
        }

    }

    function reminderToSign(){
        //Ok report needs to be call with cron 4-6times in a day
        //Reminder notify signer to sign the document
        $homePath = FCPATH."index.php";
        $rootFolder = publicFolder();
        $CRONASSETSDIR = $this->esign_config->CRONASSETSDIR;
        //$secretFolder = $this->esign_config->SECRETFOLDER;
        //$srcFilePath = $rootFolder.$srcFilePath;

        $expiringSignersDocuments = $this->cron_model->getDocumentsToBeExpire();
        
        if(!empty($expiringSignersDocuments)){
            foreach($expiringSignersDocuments as $expiringSignersDocumentRw){
                
                $expiringSignersDocTx = json_encode($expiringSignersDocumentRw);
                $tmpSignerId = $expiringSignersDocumentRw["id"];

                $tmpDirPath = $rootFolder.$CRONASSETSDIR."/$tmpSignerId/";
                create_local_folder($tmpDirPath);
                $tmpFilePath = $tmpDirPath.$tmpSignerId."_reminder.txt";
                fileWrite($tmpFilePath, $expiringSignersDocTx);

                //fire exec to send email
                //exec("php $homePath emailengine sendDocuExpiredReminder $tmpSignerId > /dev/null &", $out);
				exec("php $homePath emailengine sendDocuExpiredReminder $tmpSignerId", $out);
               // echo "<pre>"; print_r($out);

            }
        }
        
    }

    function setDocumentToBeExpired(){
        //Ok report needs to be call with cron 4-6times in a day
        //update expired flag if document is expired today
        $expiredDocuments = $this->cron_model->setDocumentToBeExpired();
    }    

}