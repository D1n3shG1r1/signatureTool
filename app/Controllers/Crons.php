<?php

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

        $expiringSignersDocuments = $this->cron_model->getDocumentsToBeExpire();

        /*
        Template Content:
        Subject: <Owner name> has sent you a reminder to review and sign <Document Title>
        message: Hi <Signer name>,
                    <Owner name> has requested you to review and sign <Document Title>
                    Sender        owneremal@example.com
                    Document Title  abc contarct
                    Expires on    22may2023


                            [View Document]
        */
        die;
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
                echo "<pre>"; print_r($out);
            }
        }

    }

}