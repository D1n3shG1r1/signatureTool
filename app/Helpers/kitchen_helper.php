<?php

if(!function_exists('touchFile')){
    function touchFile($fileName){
        if($fileName){
            $cmd = "touch $fileName"; 
            exec($cmd, $output);
        }
    }
}

if(!function_exists('removeExpiredAccessToken')){
    function removeExpiredAccessToken(){

        $path = publicFolder()."gmail_access_token/";
        $srch = 'ATKN-*';
        exec('cd '.$path.' && find . -name "'.$srch.'"', $output);

        if(!empty($output)){

            foreach($output as $tmpObj){

                $fileNM = $tmpObj;

                if($fileNM != ''){
                    $rmvFl = $path.$fileNM;
                    exec("rm -f $rmvFl");
                }
            }
        }
    }
}

if(!function_exists('publicFolder')){
    function publicFolder(){

      //$documentRoot = $_SERVER["DOCUMENT_ROOT"];
      $documentRoot = $_SERVER["SCRIPT_FILENAME"];
      $documentRootArray = explode("/", $documentRoot);

      unset($documentRootArray[count($documentRootArray) - 1]);
      $documentRoot = implode("/", $documentRootArray);
      return $documentRoot."/";
    }
}

if(!function_exists('db_randomnumber')){
    function db_randomnumber(){
		$uniqNum = time().rand(10000,999999);
		return $uniqNum;
	}
}

if(!function_exists('random_unique_string')){
	function random_unique_string() {
		
		// md5 the timestamps and returns substring
		return md5(time().rand(10000,999999));
	}
}

if(!function_exists('create_local_folder')){
    function create_local_folder($path){
      if(!is_dir($path)){
        mkdir($path,0777);
        exec("chmod 777 $path");
      }
    }
}

if(!function_exists('fileWrite')){
    function fileWrite($file,$data,$mode='w+'){
      $fp = fopen($file,$mode);
      fwrite($fp,$data);
      fclose($fp);
    }
}

if(!function_exists('chadarmodbs64')){
    function chadarmodbs64($str){
        $newstr = str_replace("+","#DKG#",$str);
        return $newstr;
    }
}

if(!function_exists('chadarsidhibs64')){
    function chadarsidhibs64($str){
        $newstr = str_replace("#DKG#","+",$str);
        return $newstr;
    }
}


if(!function_exists('getPrevAccessToken')){
    function getPrevAccessToken(){

        $path = publicFolder()."gmail_access_token/";
        $srch = 'ATKN-*';
        exec('cd '.$path.' && find . -name "'.$srch.'"', $output);

        $filePath = $output[0];
        $accssToknArr = explode("ATKN-", $filePath);
        $accessToken = $accssToknArr[1];
        return $accessToken;
    }
}

if(!function_exists('getPrevRefreshToken')){
    function getPrevRefreshToken(){

        $path = publicFolder()."gmail_access_token/";
        $srch = 'RTKN-*';
        exec('cd '.$path.' && find . -name "'.$srch.'"', $output);

        $filePath = $output[0];
        $rfrshToknArr = explode("RTKN-", $filePath);
        $refreshToken = $rfrshToknArr[1];
        $refreshToken = str_replace("#DK#", "/", $refreshToken);
        return $refreshToken;
    }
}


if(!function_exists('moveFileOneDirToAnother')){
    function moveFileOneDirToAnother($src, $dst){
        
        //if( !copy($src, $dst) ) { 
        if( !rename($src, $dst) ) { 
            
            //echo "File can't be copied! \n"; 
            return 0;
        } 
        else { 
            //echo "File has been copied! \n"; 
            return 1;
        } 

    }
}


if(!function_exists('fileRead')){
    function fileRead($path){

        $content = file_get_contents($path);
        return $content;
    }
}


if(!function_exists('genOtp')){
    function genOtp(){

        $otp = rand(000000,999999);
        return $otp;
    }
}

if(!function_exists('customredirect')){
    function customredirect($routeName){
        
        header('Location: '.site_url($routeName)); die;
    }
}

if(!function_exists('dateDiffMinutes')){
    function dateDiffMinutes($dt1, $dt2){
        
        $from = strtotime($dt1);
        $to = strtotime($dt2);

        //$hourdiff = round((strtotime($time1) - strtotime($time2))/3600, 1);

        $diff = round(abs($to - $from) / 60,2);
        return $diff;
    }
}

if(!function_exists('genshastring')){
    function genshastring($str){
        return sha1($str);
    }
}
?>