<?php include("header.php"); 
$masterDocument = $document["masterDocument"];
$sender = $document["sender"];
$recipentsData = $document["recipents"];

$expiryDate = $recipentsData["expiryDate"];
$expiryStatus = $recipentsData["expired"];
$recipents = json_decode($recipentsData["recipients"]);
$documentTitle = $recipentsData["documentTitle"];

$isComplete = $masterDocument["isComplete"];
$documentPath = $masterDocument["documentPath"];

$completedDocumentPath = str_replace(".pdf", "_completed.pdf",$documentPath);
$downloadurlCompltDoc = $completedDocumentPath;
$downloadurlAuditDoc = str_replace(".pdf", "_auditlog.pdf",$documentPath);

?>

<style>
.main-overview-body {
    display: flex;
    padding-top: 40px;
}
/*
.main-overview-body .container{
    margin-left:0px;
    border:1px solid;
}
*/

.main-overview-body .main-summary{
    padding-left: 60px;
    padding-right: 60px;
}

.document-overview-content {
    line-height: 1.43;
    line-height: 1.43;
    border-bottom: 1px solid #ddd;
    padding: 10px 0 45px 0px;
}

.overview-text, .overview-text-label{
    font-size: 17px;
    font-weight: 500;
    color:#666e80;
}

.document-overview-content .row{
    padding: 10px 0 10px 0px;
}

.document-overview-content .row .col-2{
    text-align: right;
}

.recipients-nav-row{
    margin-top: 45px;
}

.recipients-nav{width: auto;}

.recipients-nav .nav-item .nav-link{
    color:#666e80;
}

.recipients-row .table, .recipients-row .table thead{
    color:#666e80;
}

.recipients-row .table thead tr th{
    font-weight: 500;
}

.recipients-nav .nav-item .nav-link.active{
    color:#0D6EFD;
}

.recipients-nav .nav-item .inactive-nav-highlight{
    border-bottom: 1px solid #ddd;
    padding-top: 3px;
}

.recipients-nav .nav-item .active-nav-highlight{
    border: 2px solid #0D6EFD;
    border-radius: 4px;
}

.recipients-row{
    margin-top:25px;
}

.recipients-row .table .la-check-circle:before{
    font-size: 17px;
    padding: 0px 2px 0px 0px;
}

.recipients-row .table .recipeintEmail{
    color:#0D6EFD;
    max-width: 200px;
    word-break: break-all;
}

.recipients-row .table .recipeintName{
    max-width: 200px;
    word-break: break-all;
}

.documentNameContainer{
    color:#666e80;   
}

.top-right-btns .dropdown-menu{width: min-content;}
</style>

<main>
    
<div class="container-fluid" style="margin-bottom:61px;">
    
    <div class="top-menu" style="padding: 10px;">
        <figure class="logo-wrap">
            <span class="appName"><img src="<?php echo base_url("/assets/images/logocl.png"); ?>"></span>
        </figure>
        <div class="" style="width:69% !important;">
            <span class="documentNameContainer"><?php echo $documentTitle; ?></span>
        </div>
        <ul class="top-right-btns list-unstyled other-page-top-btns" style="width:auto;">
            <li>
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">More Actions</button>
                <ul class="dropdown-menu" style="">
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="downloadDocument('<?php echo base_url($downloadurlCompltDoc); ?>');">Download Document</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="downloadDocument('<?php echo base_url($downloadurlAuditDoc); ?>');">Download Audit Log</a></li>
                </ul>
            </li>
            <li>
                <button type="button" class="btn btn-outline-warning" onclick="viewMainDocument();">View Document</button>
            </li>
        </ul>
    </div>    
</div>
     
<div class="main-overview-body">
    <?php include("navbar.php"); ?>


    <div class="container-fluid main-summary">
        <div class="document-overview-content row group">
            <div class="row">
                <div class="col-2">
                    <label class="overview-text-label">Document ID:</label>
                </div>
                <div class="col-8">
                    <span class="overview-text"><?php echo $masterDocument["documentId"]; ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label class="overview-text-label">Status:</label>
                </div>
                <div class="col-8">
                    <span class="overview-text"><?php if($masterDocument["isComplete"] == 1){ echo "Completed"." (Signed by all ".$masterDocument["no_of_parties"]." signers)"; }else{ echo "Pending";} ?></span>
                </div>
            </div>
            
            <div class="row">
                <div class="col-2">
                    <label class="overview-text-label">Sent by:</label>
                </div>
                <div class="col-8">
                    <span class="overview-text"><?php echo ucfirst($sender["first_name"])." ". ucfirst($sender["last_name"]); ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label class="overview-text-label">Sent on:</label>
                </div>
                <div class="col-8">
                    <span class="overview-text"><?php echo date("M-d, Y h:i A", strtotime($masterDocument["created_at"])); ?> </span>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label class="overview-text-label">Expires on:</label>
                </div>
                <div class="col-8">
                    <span class="overview-text"><?php echo date("M-d, Y h:i A", strtotime($expiryDate)); ?> </span>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label class="overview-text-label">Expired:</label>
                </div>
                <div class="col-8">
                    <span class="overview-text"><?php  
                        if($expiryStatus == 1){
                            //expired
                            echo '<i class="las la-exclamation-circle"></i> Document has been expired on '.date("M-d, Y h:i A", strtotime($expiryDate));
                        }else{
                            
                           
                            $now = time(); // or your date as well
                            $your_date = strtotime($expiryDate);
                            $datediff = $now - $your_date;
                            $days = round($datediff / (60 * 60 * 24));
                            if($days > 1){
                                $daysStr = $days." days";
                            }else{
                                $daysStr = $days." day";
                            }
                            //not expired
                            echo '<i class="las la-exclamation-circle"></i> Document will expires after '.$daysStr;
                        }
                    ?> </span>
                </div>
            </div>
            

            <div class="row">
                <div class="col-2">
                    <label class="overview-text-label">Title:</label>
                </div>
                <div class="col-8">
                    <span class="overview-text"><?php echo $documentTitle; ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label class="overview-text-label">File:</label>
                </div>
                <div class="col-8">
                    <span class="overview-text"><?php echo $recipentsData["file_name"]; ?></span>
                </div>
            </div>
        </div>

        <div class="row recipients-nav-row"> 
            <ul class="nav recipients-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Recipients Details</a>
                    <div class="active-nav-highlight col-12"></div>
                </li>
                <li class="nav-item" style="display:none;">
                    <a class="nav-link" href="#">Document History</a>
                    <div class="inactive-nav-highlight col-12"></div>
                </li>
            </ul>
        </div>
        <div class="row recipients-row">
        
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Recipients</th>
                        <th scope="col">Email ID</th>
                        <th scope="col">Last Activity</th>
                        <th scope="col">Status</th>
                        <th scope="col">Authentication</th>
                        <th scope="col">View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                    if(!empty($recipents)){
                        foreach($recipents as $tmpRecipent){
                            $tmpEmail = $tmpRecipent->email;
                            $tmpName =  $tmpRecipent->name;
                            $tmpAuthType = $tmpRecipent->authType;
                            $tmpAccessCode = $tmpRecipent->accessCode;
                            
                            $tmpDocStatus = $tmpRecipent->document->document_status;
                            $tmpDocumentId = $tmpRecipent->document->documentId;
                            
                            if($tmpAuthType == 1){
                                //otp
                                $tmpAuthTypeTxt = "OTP";
                            }else if($tmpAuthType == 2){
                                //access code
                                $tmpAuthTypeTxt = "Access Code - <span style=\"letter-spacing:5px; font-weight:500;\" class=\"\">$tmpAccessCode</span>";
                            }else{
                                //nil
                                $tmpAuthTypeTxt = "-";
                            }

                            
                            if(strtolower($tmpDocStatus) == "signed"){
                                $tmpDocStatus = '<i class="las la-check-circle"></i>'.$tmpDocStatus;
                            }

                            
                            $tr .= '<tr>
                                <td class="recipeintName">'.$tmpName.'</td>
                                <td class="recipeintEmail">'.$tmpEmail.'</td>
                                <td>0000-00-00 00:00:00</td>
                                <td>'.$tmpDocStatus.'</td>
                                <td>'.$tmpAuthTypeTxt.'</td>
                                <td><button type="button" class="btn btn-outline-primary col-12" onclick="showDocument(\''.$tmpDocumentId.'\');">View</button></td>
                            </tr>';   
                            
                        }
                    }else{
                        $tr = '<tr><td>It seems that you have no recipients yet.</td></tr>';
                    }
                    echo $tr;                    
                    ?>
                </tbody>
            </table>
        </div>


    </div>


</div>

</main>

<script>
    
    function viewMainDocument(){
        var url = '<?php echo base_url("$completedDocumentPath"); ?> ';
        window.open(url,"_blank");
    }                    

    function showDocument(docId){
        var url = '<?php echo base_url("$folderId"); ?>/'+docId+'/'+docId+'.pdf';
        window.open(url,"_blank");
    }

    function downloadDocument(downloadurl){
        
        var downloadurlArr = downloadurl.split("/");
        var tmpFlNm = downloadurlArr[downloadurlArr.length - 1];
        var link = document.createElement('a');
        link.href = downloadurl;
        link.download = tmpFlNm;
        link.dispatchEvent(new MouseEvent('click'));

    }

</script>

<?php include("footer.php"); ?>