<?php

namespace App\Controllers;
use App\Models\Cron_Model; //load model

class Crons extends BaseController
{
    public $cron_model = null;

    function __construct(){
        //Model Object
		$this->cron_model = new Cron_Model();

    }

    function getExpireDocuments(){
        $this->cron_model->getExpiredDocuments();
    }

}

