<?php include("header.php"); 

$masterDocument = $document["masterDocument"];
$sender = $document["sender"];
$recipentsData = $document["recipents"];
$recipents = json_decode($recipentsData["recipients"]);

?>

<style>
.main-overview-body {
    display: flex;
    padding-top: 40px;
}

.main-overview-body .container{
    margin-left:0px;
    border:1px solid;
}

.document-overview-content {
    line-height: 1.43;
    line-height: 1.43;
    border-bottom: 1px solid #ddd;
    padding: 10px 0 30px 0px;
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
</style>

<main>
    
<div class="container-fluid" style="margin-bottom:61px;">
    
    <div class="top-menu" style="padding: 10px;">
        <figure class="logo-wrap">
            <span class="appName"><img src="<?php echo base_url("/assets/images/logocl.png"); ?>"></span>
        </figure>
        <div class="">
            <!--<span class="documentNameContainer conf-fields">Configure fields</span>-->
            <span class="documentNameContainer">sample</span>
        </div>
    </div>    
</div>
     
<div class="main-overview-body">
    <?php include("navbar.php"); ?>


    <div class="container">
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
                    <label class="overview-text-label">Title:</label>
                </div>
                <div class="col-8">
                    <span class="overview-text">123154fsfgd456468dgf</span>
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

        <div class="row">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Recipients Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Document History</a>
                </li>
            </ul>
        </div>
        <div class="row">
        
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Recipients</th>
                        <th scope="col">Email ID</th>
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
                            $tmpDocStatus = $tmpRecipent->document->document_status;
                            $tmpDocumentId = $tmpRecipent->documentId;
                            
                            $tr .= '<tr>
                                <td>'.$tmpName.'</td>
                                <td>'.$tmpEmail.'</td>
                                <td>'.$tmpDocStatus.'</td>
                                <td>'.$tmpAuthType.'</td>
                                <td>'.$tmpDocumentId.'</td>
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
<?php include("footer.php"); ?>